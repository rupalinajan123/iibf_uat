<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Approver extends CI_Controller
{
  public $UserID;
  public function __construct()
  {
    parent::__construct();
    /*	if($this->session->userdata('kyc_id') == ""){
			redirect('admin/kyc/Login');
		}		*/
    $this->load->model('UserModel');
    $this->load->model('Master_model');
    $this->UserID = $this->session->id;
    $this->load->helper('pagination_helper');
    $this->load->library('pagination');
    $this->load->helper('upload_helper');
    $this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->model('Chk_KYC_session');
    $this->Chk_KYC_session->chk_approver_session();
    $this->load->model('KYC_Log_model');
  }

  public function get_client_ip(){
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
         $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = 'UNKNOWN';
      return $ipaddress;
  }

  public function index()
  {
  }

  public function pending_member_list()
  {
    $count = 1;
    $kyc_start_date = '2017-06-01';
    $this->db->select('DISTINCT(mk.regnumber)');
    $this->db->where('mk.kyc_status', '0');
    $this->db->where('mk.kyc_state', '1');
    $this->db->where('mk.kyc_state !=', '3');
    $this->db->where('mk.field_count', '0');
    $this->db->where(" mk.regnumber IN (SELECT regnumber FROM member_registration mr WHERE mr.isactive = '1' AND mr.isdeleted ='0' AND mr.kyc_status = '0' AND DATE(mr.editedon) != '0000-00-00' ) ");
    $this->db->group_by('mk.regnumber');
    $this->db->limit('1000');
    $members = $this->master_model->getRecords('member_kyc mk');
    // echo $this->db->last_query();die;

    foreach ($members as $regnumber)
    {

      $regnumber = $regnumber['regnumber'];

      if (!in_array($regnumber, $str_regnumber))
      {
        $str_regnumber[] = $regnumber;
      }
      $count++;
    }
    $data['str_regnumber'] = $str_regnumber;

    $this->load->view('admin/kyc/Approver/pending_member_list', $data);
  }

  //Home page 
  public function dashboard()
  {
    $this->load->view('admin/kyc/Approver/dashboard');
  }

  // pending member allocation by pooja mane(26-12-2023)
  public function pending_allocation_type()
  {
    $new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id' => ''));

    if (count($new_allocated_member_list) > 0)
    {
      if ($new_allocated_member_list[0]['allotted_member_id'] == '')
      {
        redirect(base_url() . 'admin/kyc/Approver/next_pending_allocation_type');
      }
    }

    $kyc_start_date = $this->config->item('kyc_start_date');
    $allocated_member_list = $members = array();
    $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''));

    //allocated_count
    if (count($allocated_member_list))
    {

      if (count($allocated_member_list) > 0)
      {
        $data['count'] = $allocated_member_list[0]['allocated_count'];
        $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      }

      foreach ($arraid as $row)
      {

        $this->db->select('exam_code');
        $exam_code = $this->master_model->getRecords("member_exam", array('regnumber' => $row, 'pay_status' => '1'));
        $exam_code = $exam_code[0]['exam_code'];

        $this->db->select('registrationtype');
        $reg_type = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
        $type = $reg_type[0]['registrationtype'];

        $this->db->where('isactive', '1');
        $this->db->where('kyc_status', '0');
        $this->db->where('DATE(createdon)!=', '00-00-0000'); //&& 'DATE(createdon)>=', $kyc_start_date removed 


        if ($type == 'NM' && $exam_code != '')
        {   //echo $type;die;
          $this->db->select('me.exam_code,member_registration.*');
          $this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber');
          $this->db->where('me.exam_code', $exam_code);
          $this->db->group_by('me.regnumber');
          $this->db->where('me.pay_status', '1');
        }

        $members = $this->master_model->getRecords("member_registration", array('member_registration.regnumber' => $row));
        $members_arr[] = $members;
      }

      $emptylistmsg = ' ';
      $data['emptylistmsg']  = $emptylistmsg;
      $data['result'] = call_user_func_array('array_merge', $members_arr);
      $data['reset']  = '1'; //flag for reset btn added by pooja mane on 11-04-23

      /* Start Code To Get Recent Allotted Member Total Count */
      $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
      if (!empty($pagination_total_count))
      {
        foreach ($pagination_total_count[0] as $k => $value)
        {
          if ($k == "pagination_total_count")
          {
            $data['totalRecCount'] = $value;
          }
          if ($k == "original_allotted_member_id")
          {
            $data['original_allotted_member_id'] = $value;
          }
        }
      }
      /* Close Code To Get Recent Allotted Member Total Count */

      $this->load->view('admin/kyc/Approver/pending_alocated_member', $data);
    }
    else
    {
      $count = 1;
      $kyc_start_date = '2017-06-01';

      $this->db->select('DISTINCT(mk.regnumber)');
      $this->db->where('mk.kyc_status', '0');
      $this->db->where('mk.kyc_state', '1');
      $this->db->where('mk.kyc_state !=', '3');
      $this->db->where('mk.field_count', '0');
      $this->db->where(" mk.regnumber IN (SELECT regnumber FROM member_registration mr WHERE mr.isactive = '1' AND mr.isdeleted ='0' AND mr.kyc_status = '0' AND DATE(mr.editedon) != '0000-00-00' ) ");
      $this->db->group_by('mk.regnumber');
      $this->db->limit('1000');
      $members = $this->master_model->getRecords('member_kyc mk');


      foreach ($members as $regnumber)
      {

        $regnumber = $regnumber['regnumber'];

        if (!in_array($regnumber, $str_regnumber))
        {
          $str_regnumber[] = $regnumber;
        }
        $count++;
      }
      $data['str_regnumber'] = $str_regnumber;

      $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'Pending', 'user_type' => 'recommender'), 'original_allotted_member_id');

      if (count($kyc_data) > 0)
      {
        foreach ($kyc_data  as $row)
        {
          $allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
        }
      }

      if (count($allocatedmemberarr) > 0)
      {
        // get the column data in a single array
        $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
      }


      $this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
      $this->db->where_in('mr.regnumber', array_map('stripslashes', $str_regnumber));
      $this->db->where('mr.isactive', '1');
      $this->db->where('mr.kyc_status', '0');
      $this->db->where('mr.registrationtype', 'NM');
      $this->db->where('me.pay_status', '1');
      $this->db->join('member_exam me', 'me.regnumber = mr.regnumber');
      $this->db->join('exam_master e', 'e.exam_code = me.exam_code');
      $this->db->group_by('me.exam_code');
      $this->db->order_by('mr.regid', 'ASC');

      if (count($data_array) > 0)
      {
        $this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
      };

      $mem_list = $this->master_model->getRecords("member_registration mr");
      $mem_exm_arr[] = $mem_list;
      $data['mem_exm_arr'] = $mem_exm_arr;

      $this->load->view('admin/kyc/Approver/pending_allocation_type', $data);
    }
  }

  public function next_pending_allocation_type()
  {
    $count = 1;
    $kyc_start_date = '2017-06-01';
    $this->db->select('DISTINCT(mk.regnumber)');
    $this->db->where('mk.kyc_status', '0');
    $this->db->where('mk.kyc_state', '1');
    $this->db->where('mk.kyc_state !=', '3');
    $this->db->where('mk.field_count', '0');
    $this->db->where(" mk.regnumber IN (SELECT regnumber FROM member_registration mr WHERE mr.isactive = '1' AND mr.isdeleted ='0' AND mr.kyc_status = '0' AND DATE(mr.editedon) != '0000-00-00' ) ");
    $this->db->group_by('mk.regnumber');
    $this->db->limit('1000');
    $members = $this->master_model->getRecords('member_kyc mk');

    foreach ($members as $regnumber)
    {

      $regnumber = $regnumber['regnumber'];

      if (!in_array($regnumber, $str_regnumber))
      {
        $str_regnumber[] = $regnumber;
      }
      $count++;
    }
    $data['str_regnumber'] = $str_regnumber;

    $this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
    $this->db->where_in('mr.regnumber', array_map('stripslashes', $str_regnumber));
    $this->db->where('mr.isactive', '1');
    $this->db->where('mr.kyc_status', '0');
    $this->db->where('mr.registrationtype', 'NM');
    $this->db->where('me.pay_status', '1');
    $this->db->join('member_exam me', 'me.regnumber = mr.regnumber');
    $this->db->join('exam_master e', 'e.exam_code = me.exam_code');
    $this->db->group_by('me.exam_code');
    $this->db->order_by('mr.regid', 'ASC');

    if (count($allocatedmemberarr) > 0)
    {
      // get the column data in a single array
      $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
    }

    $data_array = array_merge($data_array, $recommendedmemberarr);

    if (count($recommendedmemberarr) > 0)
    {
      $this->db->where_not_in('mr.regnumber', array_map('stripslashes', $recommendedmemberarr));
    };
    if (count($data_array) > 0)
    {
      $this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
    };

    $mem_list = $this->master_model->getRecords("member_registration mr");
    $next_mem_exm_arr[] = $mem_list;
    $data['next_mem_exm_arr'] = $next_mem_exm_arr;

    $this->load->view('admin/kyc/Approver/next_pending_allocation_type', $data);
  }

  public function approver_pending_list()
  {

    $tilte = $type = '';
    $description = $emptylistmsg = '';
    $allocates_arr = $members_arr = $result = $array = array();
    $data['result'] = array();
    $regstr = $searchText = $searchBy = '';
    $searchBy_regtype = '';
    $today = date('Y-m-d H:i:s');
    $per_page = 100;
    $last = 99;
    $start = 0;
    $list_type = 'Pending';
    $exam_code = $_POST['select_exm_cd'];


    $page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
    if ($this->input->post('regnumber') != '')
    {
      $searchBy = $this->input->post('regnumber');
    }
    if ($this->input->post('registrationtype') != '')
    {
      $searchBy_regtype = $this->input->post('registrationtype');
    }

    $registrationtype = '';
    $data['reg_no'] = ' ';

    if ($page != 0)
    {
      $start = $page - 1;
    }
    $allocates = array();
    //get  all  user loging today 
    if (isset($_POST['selectby']))
    {

      $type = $_POST['selectby'];
      $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_type' => 'approver', 'list_type' => 'Pending'), 'original_allotted_member_id');

      $allocatedmemberarr = array();
      if (count($kyc_data) > 0)
      {
        foreach ($kyc_data as $row)
        {
          $allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
        }
      }


      $count = 1;
      $kyc_start_date = '2017-06-01';
      $this->db->select('DISTINCT(mk.regnumber)');
      $this->db->where('mk.kyc_status', '0');
      $this->db->where('mk.kyc_state', '1');
      $this->db->where('mk.kyc_state !=', '3');
      $this->db->where('mk.field_count', '0');
      $this->db->where(" mk.regnumber IN (SELECT regnumber FROM member_registration mr WHERE mr.isactive = '1' AND mr.isdeleted ='0' AND mr.kyc_status = '0' AND DATE(mr.editedon) != '0000-00-00' ) ");
      $this->db->group_by('mk.regnumber');
      $this->db->limit('1000');
      $members = $this->master_model->getRecords('member_kyc mk');

      foreach ($members as $regnumber)
      {

        $regnumber = $regnumber['regnumber'];

        if (!in_array($regnumber, $str_regnumber))
        {
          $str_regnumber[] = $regnumber;
        }

        $count++;
      }
      $data['str_regnumber'] = $str_regnumber;
      $this->db->where_in('member_registration.regnumber', array_map('stripslashes', $str_regnumber));

      $data_array = array();
      if (count($allocatedmemberarr) > 0)
      {
        // get the column data in a single array
        $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
      }

      // merge allocated member array with recommended members array
      $data_array = array_merge($data_array, $recommendedmemberarr);

      if (count($data_array) > 0)
      {
        $this->db->where_not_in('member_kyc.regnumber', array_map('stripslashes', $data_array));
      }

      if ($type == 'NM')
      {   //echo $exam_code;echo'<br>';die;
        $this->db->select('exam_code');
        $this->db->join('member_exam', 'member_exam.regnumber = member_kyc.regnumber', 'INNER');
        $this->db->where('member_exam.exam_code', $exam_code);
        $this->db->where('member_exam.pay_status', '1');
        $this->db->group_by('member_exam.regnumber');
      }

      $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
      $this->db->where('member_registration.kyc_status', '0');
      $this->db->where('member_kyc.kyc_status', '0');
      $this->db->where('member_registration.isactive', '1');
      $this->db->where('member_registration.isdeleted', '0');
      $this->db->where('member_registration.registrationtype', $type);
      $this->db->where_in('kyc_state', '1');


      $r_list = $this->master_model->getRecords("member_kyc", array('field_count' => 0), 'member_kyc.regnumber,kyc_id,namesub,dateofbirth,associatedinstitute,createdon,firstname,middlename,lastname,createdon,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,isactive,mem_associate_inst,field_count', array('kyc_id' => 'ASC'), $start, $per_page);//,employee_proof,mem_declaration added by pooja mane 2024-08-13


      $today = date("Y-m-d H:i:s");
      $row_count = $this->master_model->getRecordCount("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending'));

      if ($row_count == 0)
      {
        $regstr = '';
        foreach ($r_list  as $row)
        {
          $allocates_arr[] = $row['regnumber'];
        }
        if (count($allocates_arr) > 0)
        {
          $regstr = implode(',', $allocates_arr);
        }
        //print_r($regstr);exit;
        if ($regstr != '')
        {

          $insert_data = array(
            'user_type'      => $this->session->userdata('role'),
            'user_id'        => $this->session->userdata('kyc_id'),
            'allotted_member_id'  => $regstr,
            'original_allotted_member_id'  => $regstr,
            'allocated_count'     => count($allocates_arr),
            'allocated_list_count'     => '1',
            'date'                  => $today,
            'list_type'             => 'Pending',
            'pagination_total_count ' => count($allocates_arr)
          );
          $this->master_model->insertRecord('admin_kyc_users', $insert_data);
          //log activity 
          $tilte = 'Approver  KYC  member list allocation';
          $description = 'Approver has allocated ' . count($allocates_arr) . ' member';
          $user_id = $this->session->userdata('kyc_id');
          $this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);
        }
      }
    }
    $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''));

    if (count($allocated_member_list) > 0)
    {
      $data['count'] = $allocated_member_list[0]['allocated_count'];
    }
    else
    {
      $data['count'] = 0;
    }

    if (count($allocated_member_list) > 0)
    {
      $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

      if (count($arraid) > 0)
      {
        if ($searchBy != '' || $searchBy_regtype != '')
        {
          if ($searchBy != '' && $searchBy_regtype != '')
          {
            $this->db->where('regnumber', $searchBy);
            $this->db->where('registrationtype', $searchBy_regtype);
            $members = $this->master_model->getRecords("member_registration");
          }
          ///search by registration number
          else if ($searchBy != '')
          {
            $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
            $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
            $this->db->where('regnumber', $searchBy);
            $members = $this->master_model->getRecords("member_registration");
            //$row=$searchBy;
          }
          ///search by registration type
          else if ($searchBy_regtype != '')
          {
            $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
            $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
            $this->db->where('registrationtype', $searchBy_regtype);
            $members = $this->master_model->getRecords("member_registration");
          }
          if (count($members) > 0)
          {
            foreach ($members as $row)
            {
              $members_arr[][] = $row;
            }
          }
        }
        else
        {

          //default allocation list for 100 member
          foreach ($arraid as $row)
          {

            $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
            $this->db->where('member_registration.isactive', '1');
            $this->db->where('member_registration.isdeleted', '0');
            $this->db->where('member_kyc.kyc_status', '0');
            $this->db->where('member_registration.kyc_status', '0');
            $members = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'member_kyc.field_count' => '0', 'kyc_state' => 1), '', array('kyc_id' => 'DESC'), '0', '1');
            $members_arr[]  = $members;
          }
        }
      }

      $data['result'] = call_user_func_array('array_merge', $members_arr);
    }
    $total_row = 100;
    $url = base_url() . "admin/kyc/Approver/approver_pending_list/";
    $config = pagination_init($url, $total_row, $per_page, 2);
    $this->pagination->initialize($config);
    $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
    $str_links = $this->pagination->create_links();

    $data["links"] = $str_links;

    if (($start + $per_page) > $total_row)
      $end_of_total = $total_row;
    else
      $end_of_total = $start + $per_page;

    if ($total_row)
      $data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
    else
      $data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

    $data['index'] = $start + 1;

    $emptylistmsg = ' No records available...!!<br />
		<a href=' . base_url() . 'admin/kyc/Approver/pending_allocation_type/>Back</a>';

    /* Start Code To Get Recent Allotted Member Total Count */
    $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
    if (!empty($pagination_total_count))
    {
      foreach ($pagination_total_count[0] as $k => $value)
      {
        if ($k == "pagination_total_count")
        {
          $data['totalRecCount'] = $value;
        }
        if ($k == "original_allotted_member_id")
        {
          $data['original_allotted_member_id'] = $value;
        }
      }
    }
    /* Close Code To Get Recent Allotted Member Total Count */


    $data['emptylistmsg']  = $emptylistmsg;

    $this->db->distinct('registrationtype');
    $data['mem_type'] = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('registrationtype' => 'ASC'));

    $this->load->view('admin/kyc/Approver/approver_pending_list', $data);
  }

  public function approver_next_pending_list()
  {

    if (isset($_POST['selectby']))
    {
      $allocatedmemberarr = $data_array = $recommendedmemberarr = $member_kyc_lastest_record = $edit_recommended_list = array();
      $type = $_POST['selectby'];
      $data['count'] = 0;
      $tilte = $allocated_count = $emptylistmsg = '';
      $description = $allotted_member_id = '';
      $allocates_arr = $members_arr = $result = $array = $allocated_member_list = array();
      $data['result'] = array();
      $regstr = $searchText = $searchBy = '';
      $searchBy_regtype = '';
      $today = date('Y-m-d H:i:s');
      $per_page = 100;
      // $per_page = 10;
      $last = 99;
      // $last = 9;
      $start = 0;
      $list_type = 'Pending';
      $page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
      $check = $kyc_data = array();
      $date = date('Y-m-d H:i:s');
      $exam_code = $_POST['select_exm_cd'];


      $check = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id' => ''));

      if (count($check) > 0)
      {
        if ($check[0]['allotted_member_id'] == '')
        {
          $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'Pending', 'user_type' => 'approver'), 'allotted_member_id');

          if (count($kyc_data) > 0)
          {
            foreach ($kyc_data  as $row)
            {
              if ($row['allotted_member_id'] != '')
              {
                $allocatedmemberarr[] = explode(',', $row['allotted_member_id']);
              }
            }
          }

          $count = 1;
          $kyc_start_date = '2017-06-01';
          $this->db->select('DISTINCT(mk.regnumber)');
          $this->db->where('mk.kyc_status', '0');
          $this->db->where('mk.kyc_state', '1');
          $this->db->where('mk.kyc_state !=', '3');
          $this->db->where('mk.field_count', '0');
          $this->db->where(" mk.regnumber IN (SELECT regnumber FROM member_registration mr WHERE mr.isactive = '1' AND mr.isdeleted ='0' AND mr.kyc_status = '0' AND DATE(mr.editedon) != '0000-00-00' ) ");
          $this->db->group_by('mk.regnumber');
          $this->db->limit('1000');
          $members = $this->master_model->getRecords('member_kyc mk');

          foreach ($members as $regnumber)
          {

            $regnumber = $regnumber['regnumber'];

            if (!in_array($regnumber, $str_regnumber))
            {
              $str_regnumber[] = $regnumber;
            }

            $count++;
          }
          $data['str_regnumber'] = $str_regnumber;

          $this->db->where_in('member_registration.regnumber', array_map('stripslashes', $str_regnumber));
          $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber');
          $this->db->where('member_registration.kyc_status', '0');
          $this->db->where('member_registration.isactive', '1');
          $this->db->where('member_registration.isdeleted', '0');
          $this->db->where('member_registration.registrationtype', $type);
          $this->db->group_by('member_kyc.regnumber');

          if (count($allocatedmemberarr) > 0)
          {  // get the column data in a single array
            $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
          }

          // merge allocated member array with recommended members array
          $data_array = array_merge($data_array, $recommendedmemberarr);

          //print_r($recommendedmemberarr);//DIE;
          if (count($data_array) > 0)
          {
            $this->db->where_not_in('member_registration.regnumber', array_map('stripslashes', $data_array));
          }

          // Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-31
          if ($type == 'NM')
          {
            $this->db->select('exam_code');
            $this->db->join('member_exam', 'member_exam.regnumber = member_kyc.regnumber', 'INNER');
            $this->db->where('member_exam.exam_code', $exam_code);
            $this->db->where('member_exam.pay_status', '1');
          }
          // Added exam code condition End Pooja Mane 2023-10-31

          $members = $this->master_model->getRecords("member_kyc", array('field_count' => 0, 'member_kyc.kyc_status' => '0', 'kyc_state' => 1), 'kyc_id,member_kyc.regnumber,kyc_id,kyc_state,member_registration.dateofbirth,member_registration.associatedinstitute,namesub,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,mem_associate_inst', array('kyc_id' => 'DESC'), '', $per_page);//,employee_proof,mem_declaration added by pooja mane 2024-08-13


          $array_string1 = $check[0]['original_allotted_member_id'];
          $allocates_arr1 = explode(',', $array_string1);
          foreach ($members as $row)
          {
            $allocates_arr[] .= $row['regnumber'];
          }
          $count = count($allocates_arr);
          $allocated_count = $count + $check[0]['allocated_count'];
          if (count($allocates_arr) > 0)
          {
            $allotted_member_id = implode(',', $allocates_arr);
          }
          $new_array = array_merge($allocates_arr1, $allocates_arr);
          $original_allotted_member_id = implode(',', $new_array);
          //get the  allocated list count
          if ($allotted_member_id == '')
          {
            $list_count = $check[0]['allocated_list_count'];
          }
          else
          {
            $list_count = $check[0]['allocated_list_count'] + 1;
          }
          $update_data = array(
            'user_type'            => $this->session->userdata('role'),
            'user_id'              => $this->session->userdata('kyc_id'),
            'allotted_member_id'    => $allotted_member_id,
            'original_allotted_member_id' => $original_allotted_member_id,
            'allocated_count'          => $allocated_count,
            'allocated_list_count'     => $list_count,
            'date'                         => $today,
            'list_type'                  => $list_type,
            'pagination_total_count ' => $count
          );
          $this->db->where('list_type', 'Pending');
          $this->db->where('user_id', $this->session->userdata('kyc_id'));
          $this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d')));
          //log activity 
          $tilte = 'Approver got next  New member list allocation ';
          $user_id = $this->session->userdata('kyc_id');
          $this->KYC_Log_model->create_log($tilte, $user_id, '', serialize($update_data));
        }
        $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''));

        //allocated_count
        if (count($allocated_member_list) > 0)
        {
          $data['count'] = $allocated_member_list[0]['allocated_count'];
          $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

          if (count($arraid) > 0)
          {
            if ($searchBy != '' || $searchBy_regtype != '')
            {
              if ($searchBy != '' && $searchBy_regtype != '')
              {
                $this->db->where('regnumber', $searchBy);
                $this->db->where('registrationtype', $searchBy_regtype);
                $members = $this->master_model->getRecords("member_registration");
              }
              ///search by registration number
              else if ($searchBy != '')
              {
                $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
                $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
                $this->db->where('regnumber', $searchBy);
                $members = $this->master_model->getRecords("member_registration");
                //$row=$searchBy;
              }
              ///search by registration type
              else if ($searchBy_regtype != '')
              {
                $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
                $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
                $this->db->where('registrationtype', $searchBy_regtype);
                $members = $this->master_model->getRecords("member_registration");
              }
              if (count($members) > 0)
              {
                foreach ($members as $row)
                {
                  $members_arr[][] = $row;
                }
              }
            }
            else
            {
              $this->db->where('member_registration.registrationtype', $type);
              //default allocation list for 100 member
              foreach ($arraid as $row)
              {

                $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
                $this->db->where('member_registration.isactive', '1');
                $members   = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'field_count' => '0', 'kyc_state' => 1), 'member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,mem_associate_inst', array('kyc_id' => 'DESC'), '', 1);
                //,employee_proof,mem_declaration added by pooja mane 2024-08-13
                $members_arr[] = $members;
              }
            }
          }

          $data['result'] = call_user_func_array('array_merge', $members_arr);
        }
        $total_row = 100;
        $url = base_url() . "admin/kyc/Approver/approver_pending_list/";
        $config = pagination_init($url, $total_row, $per_page, 2);
        $this->pagination->initialize($config);
        $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
        $str_links = $this->pagination->create_links();
        //var_dump($str_links);
        $data["links"] = $str_links;

        if (($start + $per_page) > $total_row)
          $end_of_total = $total_row;
        else
          $end_of_total = $start + $per_page;

        if ($total_row)
          $data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
        else
          $data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

        $data['index'] = $start + 1;

        $emptylistmsg = ' No records available...!!<br /><a href=' . base_url() . 'admin/kyc/Approver/next_allocation_type/>Back</a>';

        /* Start Code To Get Recent Allotted Member Total Count */
        $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
        if (!empty($pagination_total_count))
        {
          foreach ($pagination_total_count[0] as $k => $value)
          {
            if ($k == "pagination_total_count")
            {
              $data['totalRecCount'] = $value;
            }
            if ($k == "original_allotted_member_id")
            {
              $data['original_allotted_member_id'] = $value;
            }
          }
        }
        /* Close Code To Get Recent Allotted Member Total Count */

        $data['emptylistmsg']  = $emptylistmsg;

        $this->db->distinct('registrationtype');
        $data['mem_type'] = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('registrationtype' => 'ASC'));
        //print_r($data['result']);die;
        $this->load->view('admin/kyc/Approver/approver_pending_list', $data);
      }
      else
      {
        redirect(base_url() . 'admin/kyc/Approver/approver_pending_list');
      }
    }
  }

  public function approver_pending_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
  {

    if ($regnumber)
    {
      $oldfilepath = $file_path = $photo_file = '';
      $state = $next_id = $success = $error = $description = '';
      $data['result'] = $name = $update_data = $old_user_data = $member_kyc_lastest_record = $sql = array();
      $new_arrayid = $noarray = array();
      $today = $date = date('Y-m-d H:i:s');
      $registrationtype = '';
      $data['reg_no'] = ' ';
      $field_count = 0;

      // recommendation submit

      if (isset($_POST['btnSubmitRecmd']))
      {
        $select = 'regnumber,registrationtype,email,createdon,excode';//excode added by pooja mane for Fedai 2024-08-06 
        $data = $this->master_model->getRecords('member_registration', array(
          'regnumber' => $regnumber,
          'isactive' => '1',
          'kyc_status' => '0'
        ), $select);
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }

        $regnumber = $data[0]['regnumber'];

        // optional
        // echo "You chose the following color(s): <br />";

        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {

            // echo $cbox."<br />";

            $check_arr[] = $cbox;
          }
        }
        // print_r($check_arr);die;
        $msg = 'Edit your profile as :-';
        if (count($check_arr) > 0)
        {
          if (in_array('name_checkbox', $check_arr))
          {
            $name_checkbox = '1';
          }
          else
          {
            $name_checkbox = '0';
            $field_count++;
            $update_data[] = 'Name';
            $msg .= 'Name,';
          }

          if (in_array('dob_checkbox', $check_arr))
          {
            $dob_checkbox = '1';
          }
          else
          {
            $dob_checkbox = '0';
            $field_count++;
            $update_data[] .= 'DOB';
            $msg .= 'Date of Birth ,';
          }

          if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM')
          {
            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
            }
          }
          elseif ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A')
          {
            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
            }
          }

          if (in_array('photo_checkbox', $check_arr))
          {
            $photo_checkbox = '1';
          }
          else
          {
            $photo_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Photo';
            $msg .= 'Photo,';
          }

          if (in_array('sign_checkbox', $check_arr))
          {
            $sign_checkbox = '1';
          }
          else
          {
            $sign_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Sign';
            $msg .= 'Sign,';
          }

          if (in_array('idprf_checkbox', $check_arr))
          {
            $idprf_checkbox = '1';
          }
          else
          {
            $idprf_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Id-proof';
            $msg .= 'Id-proof';
          }

          if ($data[0]['registrationtype'] == 'O')
          {
            if ($data[0]['createdon'] >= '2022-04-01')
            {
              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                $field_count++;
                $update_data[] .= 'Declaration';
                $msg .= 'Declaration';
              }
            }
            else
            {
              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                // no field_count is required here (declaration optional for old members)
              }
              // echo $declaration_checkbox;DIE;
            }
          }
          else
          {
            $declaration_checkbox = '0';
          }
        }
        else
        {
          $name_checkbox = '0';
          $msg .= 'Name,';
          $field_count++;
          $update_data[] .= 'Name';
          $dob_checkbox = '0';
          $msg .= 'Date of Birth ,';
          $field_count++;
          $update_data[] .= 'DOB';
          $emp_checkbox = '1';
          $msg .= 'Employer,';
          $field_count++;
          $update_data[] .= 'Employer';
          $photo_checkbox = '0';
          $msg .= 'Photo,';
          $field_count++;
          $update_data[] .= 'Photo';
          $sign_checkbox = '0';
          $msg .= 'Sign,';
          $field_count++;
          $update_data[] .= 'Sign';
          $idprf_checkbox = '0';
          $msg .= 'Id-proof,';
          $field_count++;
          $update_data[] .= 'Id-proof';

          if ($data[0]['registrationtype'] == 'O')
          {
            if ($data[0]['createdon'] >= '2022-04-01')
            {
              $declaration_checkbox = '0';
              $msg .= 'Declaration';
              $field_count++;
              $update_data[] .= 'Declaration';
            }
            else
            {
              $declaration_checkbox = '0';
              // no field_count is required here (declaration optional for old members)
            }
          }
          else
          {
            $declaration_checkbox = '0';
          }
        }

        $email = $data[0]['email'];
        if ($data[0]['registrationtype'] == 'O')
        {
          // Declaration mandatory for those users who are registered from 1 april 2022 
          // (its a date of declaration feature upload date on live)
          if ($data[0]['createdon'] >= '2022-04-01')
          {
            if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1' && $declaration_checkbox == '1')
            {
              // $error='Please  unchecked atleast one checkbox!!';
              $this->session->set_flashdata('error', 'Please uncheck atleast one checkbox!!');
            }
            else
            {
              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_user_data = $this->master_model->getRecords('member_registration', array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));

              if ($data[0]['kyc_edit'] == '0')
              {
                $record_source = 'New';
              }
              else
              {
                $record_source = 'Edit';
              }

              $insert_data = array(
                'regnumber' => $data[0]['regnumber'],
                'mem_type' => $data[0]['registrationtype'],
                'mem_name' => $name_checkbox,
                // 'email_address'			=> $data[0]['email'],
                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => $field_count,
                'old_data' => serialize($old_user_data),
                'kyc_status' => '0',
                'kyc_state' => '1',
                'recommended_by' => $this->session->userdata('kyc_id'),
                'user_type' => $this->session->userdata('role'),
                'recommended_date' => $today,
                'record_source' => $record_source
              );

              // insert the record and get latest  kyc_id
              $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => $userdata[0]['email'],
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );

              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

                // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
                // $success='KYC  recommend for the candidate & Email sent successfully !!';
                // log activity
                // get recommended fields data from member registration -

                $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
                $old_data = $this->master_model->getRecords("member_registration", array(
                  'regnumber' => $regnumber,
                  'isactive' => '1'
                ), $select);
                $log_desc['old_data'] = $old_data;
                $log_desc['inserted_data'] = $insert_data;
                $description = serialize($log_desc);
                $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

                // email log

                $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

                // make recommended fields empty  -

                if (in_array('Name', $update_data))
                {

                  $updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                // if (in_array('DOB', $update_data))
                // {
                //   $updatedata['dateofbirth'] = '0000-00-00';
                // }

                // if (in_array('Employer', $update_data))
                // {
                //   $updatedata['associatedinstitute'] = '';
                // }
                // -------check for  photo -----------#

                if (in_array('Photo', $update_data))
                {
                  $updatedata['scannedphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'p');
                  $noarray = explode('/p_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_p_' . $noarray[1];

                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                // -------end check for  photo -----------#

                // ------- check for  signature-----------#

                if (in_array('Sign', $update_data))
                {

                  $updatedata['scannedsignaturephoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 's');
                  $noarray = explode('/s_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_s_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                      //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }

                // -------End check for  photo -----------#


                // ------- check for  idproof-----------#

                if (in_array('Id-proof', $update_data))
                {

                  $updatedata['idproofphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'pr');
                  $noarray = explode('/pr_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_pr_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                  // -------End  check for id proof -----------#


                }

                // ------- check for declaration -----------#
                if (in_array('Declaration', $update_data))
                {
                  $updatedata['declaration'] = '';
                  $oldfilepath = get_img_name($regnumber, 'declaration');
                  $noarray = explode('/declaration_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath;
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_declaration_' . $noarray[1];
                    $description .= ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended declaration rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member declaration  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  }
                }
                // -------End  check for declaration -----------#

                if (!empty($updatedata))
                {
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                $member = $this->master_model->getRecords("admin_kyc_users", array(
                  'DATE(date)' => date('Y-m-d'),
                  'list_type' => 'Pending',
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                $arrayid = explode(',', $member[0]['allotted_member_id']);
                $index = array_search($regnumber, $arrayid, true);

                // get next record

                $currentid = $index;
                $nextid = $currentid + 1;
                if (array_key_exists($nextid, $arrayid))
                {
                  $next_id = $arrayid[$nextid];
                }
                else
                {
                  $next_id = $arrayid[0];
                }

                // end of next record
                // unset the  current id index

                unset($arrayid[$index]);
                if (count($arrayid) > 0)
                {
                  foreach ($arrayid as $row)
                  {
                    $new_arrayid[] = $row;
                  }
                }

                if (count($new_arrayid) > 0)
                {
                  $regstr = implode(',', $new_arrayid);
                }
                else
                {
                  $regstr = '';
                  $next_id = '';
                }

                $update_data = array(
                  'allotted_member_id' => $regstr
                );
                $this->db->where('DATE(date)', date('Y-m-d'));
                $this->db->where('list_type', 'Pending');
                $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                /* Start Code To Showing Count On Member List*/
                if ($next_id == '')
                {
                  $next_id = 0;
                }

                if ($srno > $totalRecCount)
                {

                  // $srno=$totalRecCount;

                  $srno = 1;
                }
                else
                {
                  $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                  $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                  $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                  $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                  $memberNo = $next_id;
                  $updated_list_index = array_search($memberNo, $reversedArr_list);
                  $srno = $updated_list_index;
                }

                redirect(base_url() . 'admin/kyc/Approver/approver_pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
                /* Close Code To Showing Count On Member List*/
              }
            }
          }
          else
          {
            if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1')
            {
              // $error='Please  unchecked atleast one checkbox!!';
              $this->session->set_flashdata('error', 'Please uncheck atleast one checkbox!! (except declaration - its optional for this user)');
            }
            else
            {
              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_user_data = $this->master_model->getRecords('member_registration', array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));

              if ($data[0]['kyc_edit'] == '0')
              {
                $record_source = 'New';
              }
              else
              {
                $record_source = 'Edit';
              }

              $insert_data = array(
                'regnumber' => $data[0]['regnumber'],
                'mem_type' => $data[0]['registrationtype'],
                'mem_name' => $name_checkbox,
                // 'email_address'			=> $data[0]['email'],
                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => $field_count,
                'old_data' => serialize($old_user_data),
                'kyc_status' => '0',
                'kyc_state' => '1',
                'recommended_by' => $this->session->userdata('kyc_id'),
                'user_type' => $this->session->userdata('role'),
                'recommended_date' => $today,
                'record_source' => $record_source
              );

              // insert the record and get latest  kyc_id
              $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => $userdata[0]['email'],
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );


              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

                // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
                // $success='KYC  recommend for the candidate & Email sent successfully !!';
                // log activity
                // get recommended fields data from member registration -

                $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
                $old_data = $this->master_model->getRecords("member_registration", array(
                  'regnumber' => $regnumber,
                  'isactive' => '1'
                ), $select);
                $log_desc['old_data'] = $old_data;
                $log_desc['inserted_data'] = $insert_data;
                $description = serialize($log_desc);
                $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

                // email log

                $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

                // make recommended fields empty  -

                if (in_array('Name', $update_data))
                {

                  $updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                // if (in_array('DOB', $update_data))
                // {
                //   $updatedata['dateofbirth'] = '0000-00-00';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }

                // if (in_array('Employer', $update_data))
                // {
                //   $updatedata['associatedinstitute'] = '';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }
                // -------check for  photo -----------#

                if (in_array('Photo', $update_data))
                {
                  $updatedata['scannedphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'p');
                  $noarray = explode('/p_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_p_' . $noarray[1];

                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                // -------end check for  photo -----------#

                // ------- check for  signature-----------#

                if (in_array('Sign', $update_data))
                {

                  $updatedata['scannedsignaturephoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 's');
                  $noarray = explode('/s_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_s_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                      //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }

                // -------End check for  photo -----------#


                // ------- check for  idproof-----------#

                if (in_array('Id-proof', $update_data))
                {

                  $updatedata['idproofphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'pr');
                  $noarray = explode('/pr_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_pr_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                  // -------End  check for id proof -----------#


                }

                if (!empty($updatedata))
                {
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                $member = $this->master_model->getRecords("admin_kyc_users", array(
                  'DATE(date)' => date('Y-m-d'),
                  'list_type' => 'Pending',
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                // echo $this->db->last_query();//die;
                $arrayid = explode(',', $member[0]['allotted_member_id']);
                $index = array_search($regnumber, $arrayid, true);
                // echo'<pre>';print_r($arrayid);die;
                // get next record

                $currentid = $index;
                $nextid = $currentid + 1;
                if (array_key_exists($nextid, $arrayid))
                {
                  $next_id = $arrayid[$nextid];
                }
                else
                {
                  $next_id = $arrayid[0];
                }

                // end of next record
                // unset the  current id index

                unset($arrayid[$index]);
                if (count($arrayid) > 0)
                {
                  foreach ($arrayid as $row)
                  {
                    $new_arrayid[] = $row;
                  }
                }

                if (count($new_arrayid) > 0)
                {
                  $regstr = implode(',', $new_arrayid);
                }
                else
                {
                  $regstr = '';
                  $next_id = '';
                }

                $update_data = array(
                  'allotted_member_id' => $regstr
                );
                $this->db->where('DATE(date)', date('Y-m-d'));
                $this->db->where('list_type', 'Pending');
                $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                  'user_id' => $this->session->userdata('kyc_id')
                ));

                /* Start Code To Showing Count On Member List*/
                if ($next_id == '')
                {
                  $next_id = 0;
                }

                if ($srno > $totalRecCount)
                {

                  // $srno=$totalRecCount;

                  $srno = 1;
                }
                else
                {
                  $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                  $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                  $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                  $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                  $memberNo = $next_id;
                  $updated_list_index = array_search($memberNo, $reversedArr_list);
                  $srno = $updated_list_index;
                }

                redirect(base_url() . 'admin/kyc/Approver/approver_pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
                /* Close Code To Showing Count On Member List*/
              }
            }
          } // ($data[0]['createdon'] >= '2022-04-01') (else closing)
        }
        else
        {
          if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1')
          {
            // $error='Please  unchecked atleast one checkbox!!';
            $this->session->set_flashdata('error', 'Please  uncheck atleast one checkbox!!');
          }
          else
          {
            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
            $old_user_data = $this->master_model->getRecords('member_registration', array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));

            if ($data[0]['kyc_edit'] == '0')
            {
              $record_source = 'New';
            }
            else
            {
              $record_source = 'Edit';
            }

            $insert_data = array(
              'regnumber' => $data[0]['regnumber'],
              'mem_type' => $data[0]['registrationtype'],
              'mem_name' => $name_checkbox,

              // 'email_address'			=> $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => $emp_checkbox,
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'field_count' => $field_count,
              'old_data' => serialize($old_user_data),
              'kyc_status' => '0',
              'kyc_state' => '1',
              'recommended_by' => $this->session->userdata('kyc_id'),
              'user_type' => $this->session->userdata('role'),
              'recommended_date' => $today,
              'record_source' => $record_source
            );

            // insert the record and get latest  kyc_id

            $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);
            if ($data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A')
            {
              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => $userdata[0]['email'],
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );
            }
            elseif ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM')
            {
              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_NM'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //'to'=> "kyciibf@gmail.com",

                'to' => $userdata[0]['email'],
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );
            }

            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

              // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
              // $success='KYC  recommend for the candidate & Email sent successfully !!';
              // log activity
              // get recommended fields data from member registration -

              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_data = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $log_desc['old_data'] = $old_data;
              $log_desc['inserted_data'] = $insert_data;
              $description = serialize($log_desc);
              $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

              // email log

              $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

              // make recommended fields empty  -

              if (in_array('Name', $update_data))
              {

                $updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                $this->db->where('isactive', '1');
                $this->master_model->updateRecord('member_registration', $updatedata, array(
                  'regnumber' => $regnumber
                ));
              }

              // if (in_array('DOB', $update_data))
              // {
              //   $updatedata['dateofbirth'] = '0000-00-00';

              //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

              // }

              // if (in_array('Employer', $update_data))
              // {
              //   $updatedata['associatedinstitute'] = '';

              //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

              // }
              // -------check for  photo -----------#

              if (in_array('Photo', $update_data))
              {
                $updatedata['scannedphoto'] = '';
                $oldfilepath = get_img_name($regnumber, 'p');
                $noarray = explode('/p_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_p_' . $noarray[1];

                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                }
              }
              // -------end check for  photo -----------#

              // ------- check for  signature-----------#

              if (in_array('Sign', $update_data))
              {

                $updatedata['scannedsignaturephoto'] = '';
                $oldfilepath = get_img_name($regnumber, 's');
                $noarray = explode('/s_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_s_' . $noarray[1];
                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                    //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                }
              }

              // -------End check for  photo -----------#


              // ------- check for  idproof-----------#

              if (in_array('Id-proof', $update_data))
              {

                $updatedata['idproofphoto'] = '';
                $oldfilepath = get_img_name($regnumber, 'pr');
                $noarray = explode('/pr_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_pr_' . $noarray[1];
                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                }
                // -------End  check for id proof -----------#


              }

              if (!empty($updatedata))
              {
                $this->db->where('isactive', '1');
                $this->master_model->updateRecord('member_registration', $updatedata, array(
                  'regnumber' => $regnumber
                ));
              }

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'Pending',
                'user_id' => $this->session->userdata('kyc_id')
              ));
              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);

              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }

              // end of next record
              // unset the  current id index

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );
              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'Pending');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));
              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }


              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }

              redirect(base_url() . 'admin/kyc/Approver/approver_pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              /* Close Code To Showing Count On Member List*/
            }
          }
        } // ($data[0]['registrationtype'] == 'O') (else closing)
      }

      // kyc submit

      if (isset($_POST['btnSubmitkyc']))
      {
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }

        // $regnumber=$data[0]['regnumber'];
        // optional
        // echo "You chose the following color(s): <br />";

        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {

            // echo $cbox."<br />";

            $check_arr[] = $cbox;
          }
        }

        $regnumber = $this->input->post('regnumber');
        $this->db->where('regnumber', $regnumber);
        $this->db->where('isactive', '1');
        $member_regtype = $this->master_model->getRecords('member_registration', '', 'registrationtype,createdon,excode');

        // Kyc complet for DB and NM  member only 5 fileds are consider

        if ($member_regtype[0]['registrationtype'] == 'NM' || $member_regtype[0]['registrationtype'] == 'DB')
        {
          if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
          {

            $new_arrayid = $members = $old_user_data = array();
            $status = '0';
            $state = '1';
            $date = date('Y-m-d H:i:s');
            $declaration_checkbox = '0';

            // $this->db->where('recommended_date',$date);

            $this->db->where('regnumber', $regnumber);
            $member_kyc_details = $this->master_model->getRecords('member_kyc');
            if (isset($_POST['cbox']))
            {
              $name = $this->input->post('cbox');
            }

            // $regnumber=$data[0]['regnumber'];
            // optional
            // echo "You chose the following color(s): <br />";

            $check_arr = array();
            if (count($name) > 0)
            {
              foreach ($name as $cbox)
              {

                // echo $cbox."<br />";

                $check_arr[] = $cbox;
              }
            }

            $msg = 'Edit your profile as :-';
            if (in_array('name_checkbox', $check_arr))
            {
              $name_checkbox = '1';
            }
            else
            {
              $name_checkbox = '0';
              $msg .= 'Name,';
            }

            if (in_array('dob_checkbox', $check_arr))
            {
              $dob_checkbox = '1';
            }
            else
            {
              $dob_checkbox = '0';
              $msg .= 'Date of Birth ,';
            }

            if (in_array('photo_checkbox', $check_arr))
            {
              $photo_checkbox = '1';
            }
            else
            {
              $photo_checkbox = '0';
              $msg .= 'Photo,';
            }

            if (in_array('sign_checkbox', $check_arr))
            {
              $sign_checkbox = '1';
            }
            else
            {
              $sign_checkbox = '0';
              $msg .= 'Sign,';
            }

            if (in_array('idprf_checkbox', $check_arr))
            {
              $idprf_checkbox = '1';
            }
            else
            {
              $idprf_checkbox = '0';
              $msg .= 'Id-proof';
            }

            // get the old_data

            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
            $old_user_data = $this->master_model->getRecords('member_registration', array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));

            // $email=$data[0]['email'];

            $update_data = array(
              'mem_name' => $name_checkbox,

              // 'email_address'			=> $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => '0',
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'old_data' => serialize($old_user_data),
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id'),
              'approved_date' => $today,

              // 'record_source'			=>'Edit'

            );

            $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
            $this->db->where($sql);
            $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '0'
            ), 'regnumber,kyc_state,kyc_id', array(
              'kyc_id' => 'DESC'
            ));

            $this->db->where('isactive', '1');
            $this->master_model->updateRecord('member_registration', array(
              'kyc_status' => '1'
            ), array(
              'regnumber' => $regnumber
            ));

            // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

            $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
            $this->master_model->updateRecord('member_kyc', $update_data, array(
              'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            ));
            /*reset the dowanload count*/
            $where1 = array(
              'member_number' => $regnumber
            );
            $this->master_model->updateRecord('member_idcard_cnt', array(
              'card_cnt' => '0'
            ), $where1);

            // email send on KYC complete  for DB & NM

            $last_insterid = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id')
            ), 'kyc_id', array(
              'kyc_id' => 'DESC'
            ), '0', '1');

            $nomsg = '';

            $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $msg = implode(',', $update_data);
            $emailerstr = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'KYC_completion_email_to_NM'
            ));

            // echo $emailerstr[0]['emailer_text'];exit;

            $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr = array(

              // 'to'=> "kyciibf@gmail.com",

              'to' => $userdata[0]['email'],
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str
            );
            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)   & Email sent successfully !!');

              // $success='KYC Completed for the candidate & Email sent successfully !!';
              // log activity

              $regnumber = $regnumber;
              $user_id = $this->session->userdata('kyc_id');
              $tilte = 'Member KYC completed';
              $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
              $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid['kyc_id'], $regnumber, $description);

              // $this->session->set_flashdata('success','kyc completed Successfully  !!');
              // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
              // email log

              $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            }

            // rebulide the array

            $member = $this->master_model->getRecords("admin_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'list_type' => 'Pending',
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);

            // get next record

            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }

            // end of next record

            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }

            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }

            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'Pending');
            $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }

            // $totalRecCount=$this->get_App_allocation_type_cnt();

            if ($srno > $totalRecCount)
            {

              // $srno=$totalRecCount;

              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/approver_pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
          else
          {

            // $error='Select all check box to complete the Kyc !!';

            $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

            // $error='Select all check box to complete the Kyc !!';
            // redirect(base_url().'admin/kyc/Approver/approver_pending_member/'.$regnumber);

          }
        } //Kyc complet forO,A,F  member only 5 fileds are consider
        //&& in_array('emp_checkbox', $check_arr)
        elseif ($member_regtype[0]['registrationtype'] == 'A' || $member_regtype[0]['registrationtype'] == 'F')
        {
          if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
          {
            $new_arrayid = $members = array();
            $status = '0';
            $state = '1';
            $date = date("Y-m-d H:i:s");
            $declaration_checkbox = '0';

            // $this->db->where('recommended_date',$date);

            $this->db->where('regnumber', $regnumber);
            $member_kyc_details = $this->master_model->getRecords('member_kyc');
            if (isset($_POST['cbox']))
            {
              $name = $this->input->post('cbox');
            }

            //					$regnumber=$data[0]['regnumber'];
            // optional
            // echo "You chose the following color(s): <br />";

            $check_arr = array();
            if (count($name) > 0)
            {
              foreach ($name as $cbox)
              {

                // echo $cbox."<br />";

                $check_arr[] = $cbox;
              }
            }

            $msg = 'Edit your profile as :-';
            if (in_array('name_checkbox', $check_arr))
            {
              $name_checkbox = '1';
            }
            else
            {
              $name_checkbox = '0';
              $msg .= 'Name,';
            }

            if (in_array('dob_checkbox', $check_arr))
            {
              $dob_checkbox = '1';
            }
            else
            {
              $dob_checkbox = '0';
              $msg .= 'Date of Birth ,';
            }

            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
              // $msg .= 'Associate institude ,';
            }

            if (in_array('photo_checkbox', $check_arr))
            {
              $photo_checkbox = '1';
            }
            else
            {
              $photo_checkbox = '0';
              $msg .= 'Photo,';
            }

            if (in_array('sign_checkbox', $check_arr))
            {
              $sign_checkbox = '1';
            }
            else
            {
              $sign_checkbox = '0';
              $msg .= 'Sign,';
            }

            if (in_array('idprf_checkbox', $check_arr))
            {
              $idprf_checkbox = '1';
            }
            else
            {
              $idprf_checkbox = '0';
              $msg .= 'Id-proof';
            }

            // $email=$data[0]['email'];

            $update_data = array(
              'mem_name' => $name_checkbox,

              // 'email_address'			=> $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => $emp_checkbox,
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id'),
              'approved_date' => $today,

              // 'record_source'			=>'Edit'

            );

            // query to update the latest record of the regnumber

            $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
            $this->db->where($sql);
            $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber
            ), 'regnumber,kyc_state,kyc_id', array(
              'kyc_id' => 'DESC'
            ));

            // print_r($member_kyc_lastest_record );exit;

            $this->db->where('isactive', '1');
            $this->master_model->updateRecord('member_registration', array(
              'kyc_status' => '1'
            ), array(
              'regnumber' => $regnumber
            ));

            // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

            $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
            $this->master_model->updateRecord('member_kyc', $update_data, array(
              'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            ));
            /*reset the dowanload count*/
            $where1 = array(
              'member_number' => $regnumber
            );
            $this->master_model->updateRecord('member_idcard_cnt', array(
              'card_cnt' => '0'
            ), $where1);
            $last_insterid = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id')
            ), 'kyc_id', array(
              'kyc_id' => 'DESC'
            ), '0', '1');


            $nomsg = '';
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));
            $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $msg = implode(',', $update_data);
            $emailerstr = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'KYC_completion_email_to_O'
            ));

            // echo $emailerstr[0]['emailer_text'];exit;

            $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr = array(

              //	'to'=> "kyciibf@gmail.com",
              'to' => $userdata[0]['email'],
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str
            );

            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

              // $success='KYC Completed for the candidate & Email sent successfully !!';
              // log activity

              $regnumber = $regnumber;
              $user_id = $this->session->userdata('kyc_id');
              $tilte = 'Member KYC completed';
              $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
              $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

              // $this->session->set_flashdata('success','kyc completed Successfully  !!');
              // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
              // email log

              $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            }

            // rebulide the array

            $member = $this->master_model->getRecords("admin_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'list_type' => 'Pending',
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);

            // get next record

            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }

            // end of next record

            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }

            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }

            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'Pending');
            $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }

            // $totalRecCount=$this->get_App_allocation_type_cnt();

            if ($srno > $totalRecCount)
            {

              // $srno=$totalRecCount;

              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/approver_pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
          else
          {
            $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

            // $error='Select all check-box to complete the Kyc !!';
            // redirect(base_url().'admin/kyc/Approver/approver_pending_member/'.$regnumber);

          }
        }
        elseif ($member_regtype[0]['registrationtype'] == 'O')
        {
          // Declaration mandatory for those users who are registered from 1 april 2022 
          // (its a date of declaration feature upload date on live)
          //&& in_array('emp_checkbox', $check_arr)
          if ($data[0]['createdon'] >= '2022-04-01')
          {
            if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr) && in_array('declaration_checkbox', $check_arr))
            {
              $new_arrayid = $members = array();
              $status = '0';
              $state = '1';
              $date = date("Y-m-d H:i:s");

              // $this->db->where('recommended_date',$date);

              $this->db->where('regnumber', $regnumber);
              $member_kyc_details = $this->master_model->getRecords('member_kyc');
              if (isset($_POST['cbox']))
              {
                $name = $this->input->post('cbox');
              }

              //					$regnumber=$data[0]['regnumber'];
              // optional
              // echo "You chose the following color(s): <br />";

              $check_arr = array();
              if (count($name) > 0)
              {
                foreach ($name as $cbox)
                {

                  // echo $cbox."<br />";

                  $check_arr[] = $cbox;
                }
              }

              $msg = 'Edit your profile as :-';
              if (in_array('name_checkbox', $check_arr))
              {
                $name_checkbox = '1';
              }
              else
              {
                $name_checkbox = '0';
                $msg .= 'Name,';
              }

              if (in_array('dob_checkbox', $check_arr))
              {
                $dob_checkbox = '1';
              }
              else
              {
                $dob_checkbox = '0';
                $msg .= 'Date of Birth ,';
              }

              if (in_array('emp_checkbox', $check_arr))
              {
                $emp_checkbox = '1';
              }
              else
              {
                $emp_checkbox = '1';
                // $msg .= 'Associate institude ,';
              }

              if (in_array('photo_checkbox', $check_arr))
              {
                $photo_checkbox = '1';
              }
              else
              {
                $photo_checkbox = '0';
                $msg .= 'Photo,';
              }

              if (in_array('sign_checkbox', $check_arr))
              {
                $sign_checkbox = '1';
              }
              else
              {
                $sign_checkbox = '0';
                $msg .= 'Sign,';
              }

              if (in_array('idprf_checkbox', $check_arr))
              {
                $idprf_checkbox = '1';
              }
              else
              {
                $idprf_checkbox = '0';
                $msg .= 'Id-proof';
              }

              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                $msg .= 'Declaration';
              }

              // $email=$data[0]['email'];

              $update_data = array(
                'mem_name' => $name_checkbox,

                // 'email_address'			=> $data[0]['email'],

                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id'),
                'approved_date' => $today,

                // 'record_source'			=>'Edit'

              );

              // query to update the latest record of the regnumber

              $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
              $this->db->where($sql);
              $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber
              ), 'regnumber,kyc_state,kyc_id', array(
                'kyc_id' => 'DESC'
              ));

              $this->db->where('isactive', '1');
              $this->master_model->updateRecord('member_registration', array(
                'kyc_status' => '1'
              ), array(
                'regnumber' => $regnumber
              ));

              // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

              $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
              $this->master_model->updateRecord('member_kyc', $update_data, array(
                'regnumber' => $member_kyc_lastest_record[0]['regnumber']
              ));
              /*reset the dowanload count*/
              $where1 = array(
                'member_number' => $regnumber
              );
              $this->master_model->updateRecord('member_idcard_cnt', array(
                'card_cnt' => '0'
              ), $where1);
              $last_insterid = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id')
              ), 'kyc_id', array(
                'kyc_id' => 'DESC'
              ), '0', '1');

              $nomsg = '';
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'KYC_completion_email_to_O'
              ));

              // echo $emailerstr[0]['emailer_text'];exit;

              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //	'to'=> "kyciibf@gmail.com",
                'to' => $userdata[0]['email'],
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );

              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

                // $success='KYC Completed for the candidate & Email sent successfully !!';
                // log activity

                $regnumber = $regnumber;
                $user_id = $this->session->userdata('kyc_id');
                $tilte = 'Member KYC completed';
                $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
                $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

                // $this->session->set_flashdata('success','kyc completed Successfully  !!');
                // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
                // email log

                $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
              }

              // rebulide the array

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'Pending',
                'user_id' => $this->session->userdata('kyc_id')
              ));
              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);

              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }

              // end of next record

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );
              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'Pending');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));
              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }

              // $totalRecCount=$this->get_App_allocation_type_cnt();

              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }

              redirect(base_url() . 'admin/kyc/Approver/approver_pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              /* Close Code To Showing Count On Member List*/
            }
            else
            {
              $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

              // $error='Select all check-box to complete the Kyc !!';
              // redirect(base_url().'admin/kyc/Approver/approver_pending_member/'.$regnumber);

            }
          }
          else
          {
            // && in_array('emp_checkbox', $check_arr)
            if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
            {
              $new_arrayid = $members = array();
              $status = '0';
              $state = '1';
              $date = date("Y-m-d H:i:s");

              // $this->db->where('recommended_date',$date);

              $this->db->where('regnumber', $regnumber);
              $member_kyc_details = $this->master_model->getRecords('member_kyc');
              if (isset($_POST['cbox']))
              {
                $name = $this->input->post('cbox');
              }

              //					$regnumber=$data[0]['regnumber'];
              // optional
              // echo "You chose the following color(s): <br />";

              $check_arr = array();
              if (count($name) > 0)
              {
                foreach ($name as $cbox)
                {
                  // echo $cbox."<br />";
                  $check_arr[] = $cbox;
                }
              }

              $msg = 'Edit your profile as :-';
              if (in_array('name_checkbox', $check_arr))
              {
                $name_checkbox = '1';
              }
              else
              {
                $name_checkbox = '0';
                $msg .= 'Name,';
              }

              if (in_array('dob_checkbox', $check_arr))
              {
                $dob_checkbox = '1';
              }
              else
              {
                $dob_checkbox = '0';
                $msg .= 'Date of Birth ,';
              }

              if (in_array('emp_checkbox', $check_arr))
              {
                $emp_checkbox = '1';
              }
              else
              {
                $emp_checkbox = '1';
                // $msg .= 'Associate institude ,';
              }

              if (in_array('photo_checkbox', $check_arr))
              {
                $photo_checkbox = '1';
              }
              else
              {
                $photo_checkbox = '0';
                $msg .= 'Photo,';
              }

              if (in_array('sign_checkbox', $check_arr))
              {
                $sign_checkbox = '1';
              }
              else
              {
                $sign_checkbox = '0';
                $msg .= 'Sign,';
              }

              if (in_array('idprf_checkbox', $check_arr))
              {
                $idprf_checkbox = '1';
              }
              else
              {
                $idprf_checkbox = '0';
                $msg .= 'Id-proof';
              }

              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
              }

              // $email=$data[0]['email'];

              $update_data = array(
                'mem_name' => $name_checkbox,

                // 'email_address'			=> $data[0]['email'],

                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => 0,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id'),
                'approved_date' => $today,

                // 'record_source'			=>'Edit'

              );

              // query to update the latest record of the regnumber

              $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
              $this->db->where($sql);
              $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber
              ), 'regnumber,kyc_state,kyc_id', array(
                'kyc_id' => 'DESC'
              ));

              // print_r($member_kyc_lastest_record );exit;

              $this->db->where('isactive', '1');
              $this->master_model->updateRecord('member_registration', array(
                'kyc_status' => '1'
              ), array(
                'regnumber' => $regnumber
              ));

              // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

              $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
              $this->master_model->updateRecord('member_kyc', $update_data, array(
                'regnumber' => $member_kyc_lastest_record[0]['regnumber']
              ));
              /*reset the dowanload count*/
              $where1 = array(
                'member_number' => $regnumber
              );
              $this->master_model->updateRecord('member_idcard_cnt', array(
                'card_cnt' => '0'
              ), $where1);
              $last_insterid = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id')
              ), 'kyc_id', array(
                'kyc_id' => 'DESC'
              ), '0', '1');

              // print_r($last_insterid[0]['kyc_id']);exit;

              $nomsg = '';
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'KYC_completion_email_to_O'
              ));

              // echo $emailerstr[0]['emailer_text'];exit;

              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //	'to'=> "kyciibf@gmail.com",
                'to' => $userdata[0]['email'],
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );

              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

                // $success='KYC Completed for the candidate & Email sent successfully !!';
                // log activity

                $regnumber = $regnumber;
                $user_id = $this->session->userdata('kyc_id');
                $tilte = 'Member KYC completed';
                $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
                $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

                // $this->session->set_flashdata('success','kyc completed Successfully  !!');
                // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
                // email log

                $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
              }

              // rebulide the array

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'Pending',
                'user_id' => $this->session->userdata('kyc_id')
              ));

              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);
              // print_r($arrayid);die;
              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }

              // end of next record

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );

              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'Pending');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));

              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }

              // $totalRecCount=$this->get_App_allocation_type_cnt();

              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }

              redirect(base_url() . 'admin/kyc/Approver/approver_pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              /* Close Code To Showing Count On Member List*/
            }
            else
            {

              $this->session->set_flashdata('error', 'Select all check box to complete the Kyc (Declaration is optional for this user) !!');

              // $error='Select all check-box to complete the Kyc !!';
              // redirect(base_url().'admin/kyc/Approver/approver_pending_member/'.$regnumber);

            }
          }
        }
      }

      if ($regnumber)
      {
        $select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode,createdon';
        $members = $this->master_model->getRecords("member_registration a", array(
          'regnumber' => $regnumber,
          'isactive' => '1'
        ), $select, "", '0', '1');

        /*if(count($members))
				{
				$data['result'] = $members;
				$data['reg_no'] = $members[0]['regnumber'];
				$id=$data['reg_no'];
				}*/
      }

      // $this->db->where('field_count','0');

      $recommnended_members_data = $this->master_model->getRecords("member_kyc", array(
        'regnumber' => $regnumber
      ), '', array(
        'kyc_id' => 'DESC'
      ), '0', '1');

      // $data['recomended_mem_data']=$recommnended_members_data;

      $data = array(
        'result' => $members,
        'next_id' => $next_id,
        'recomended_mem_data' => $recommnended_members_data,
        'error' => $error,
        'success' => $success
      );
      $data['srno'] = $srno;
      $data['totalRecCount'] = $totalRecCount;
      $this->load->view('admin/kyc/Approver/approver_pending_screen', $data);
    }
    else
    {
      $this->session->set_flashdata('success', $this->session->flashdata('success'));

      // $this->session->set_flashdata('error','Invalid record!!');

      redirect(base_url() . 'admin/kyc/Approver/approver_pending_list');
    }
  }

  public function allocation_type()
  {

    $new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));

    if (count($new_allocated_member_list) > 0)
    {
      if ($new_allocated_member_list[0]['allotted_member_id'] == '')
      {
        redirect(base_url() . 'admin/kyc/Approver/next_allocation_type');
      }
    }
    $kyc_start_date = $this->config->item('kyc_start_date');
    $allocated_member_list = $members = array();
    $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));

    //allocated_count
    if (count($allocated_member_list))
    {
      if (count($allocated_member_list) > 0)
      {
        $data['count'] = $allocated_member_list[0]['allocated_count'];
        $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      }

      if (isset($_POST['reset']))
      {
        $data['reset'] = $reset = '1'; //flag for reset btn added by pooja mane on 11-04-23
      }

      if (isset($_POST['btnSearch']))
      {
        $key = $_POST['searchBy'];
        $value = str_replace(' ', '', $_POST['SearchVal']);

        if ($key == '01' && !empty($value))
        {
          $this->db->where("member_kyc.regnumber = '$value'");
          //$this->db->where("member_registration.regnumber = '$value'");	
        }
      }

      $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
      $this->db->where('member_registration.isactive', '1');
      $this->db->where('member_registration.kyc_edit', '0');
      $this->db->where('member_registration.isdeleted', '0');
      $this->db->where('member_registration.kyc_status', '0');
      $this->db->where_in('member_kyc.regnumber', array_map('stripslashes', $arraid));
      $this->db->group_by('member_kyc.regnumber');
      $members = $this->master_model->getRecords("member_kyc", array('member_kyc.field_count' => '0'), '', array('kyc_id' => 'ASC'), '0');
      
      if (isset($_POST['btnSearch']))
      {
        if (count($members))
        {
          $this->session->set_flashdata('success', $value . ' present in the current list');
        }
      }


      //CUSTOM SEARCH 
      if (isset($_POST['btnSearch']))
      {
        if (count($members) == 0)
        {
          //check if valid entry for appover
          $members = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $value), '', array('kyc_id' => 'DESC'));
          $kyc_done = $this->master_model->getRecords("member_registration", array('kyc_status' => '1', 'regnumber' => $value));

          if ($kyc_done)
          {
            $this->session->set_flashdata('success', 'KYC of ' . $value . ' this record is already completed');

            /* to show list  for  3 days back dated data */
            $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
            $this->db->where('member_registration.isactive', '1');
            $this->db->where('member_registration.isdeleted', '0');
            $this->db->where('member_registration.kyc_status', '0');
            $this->db->where_in('member_kyc.regnumber', array_map('stripslashes', $arraid));
            $this->db->group_by('member_kyc.regnumber');
            $members = $this->master_model->getRecords("member_kyc", array('member_kyc.field_count' => '0'), '', array('kyc_id' => 'DESC'), '0');
          }
          elseif (empty(count($members)))
          {

            $mem_exist = $this->master_model->getRecords("member_registration", array('regnumber' => $value));

            if (isset($_POST['btnSearch']) && count($mem_exist) == 0)
            {

              $this->session->set_flashdata('error', '' . $value . ' Member number does not exist');
            }
            else
            {

            $this->session->set_flashdata('success', $value . ' This Member is not recommended yet');
            }

            $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
            $this->db->where('member_registration.isactive', '1');
            $this->db->where('member_registration.isdeleted', '0');
            $this->db->where('member_registration.kyc_status', '0');
            $this->db->where_in('member_kyc.regnumber', array_map('stripslashes', $arraid));
            $this->db->group_by('member_kyc.regnumber');
            $members = $this->master_model->getRecords("member_kyc", array('member_kyc.field_count' => '0'), '', array('kyc_id' => 'DESC'), '0');
          }
          elseif (count($members) > 0)
          {
            if ($members[0]['kyc_status'] == 1 || $members[0]['field_count'] != '0')
            {
              if ($members[0]['kyc_status'] == 1)
              {
                $this->session->set_flashdata('success', 'KYC of ' . $value . ' this record is already completed');
              }
              if ($members[0]['field_count'] != '0')
              {
                $this->session->set_flashdata('success', $value . ' This record is sent for Rectification');
              }
              //print_r($arraid);die;
              $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
              $this->db->where('member_registration.isactive', '1');
              $this->db->where('member_registration.isdeleted', '0');
              $this->db->where('member_registration.kyc_status', '0');
              $this->db->where_in('member_kyc.regnumber', array_map('stripslashes', $arraid));
              $this->db->group_by('member_kyc.regnumber');
              $members = $this->master_model->getRecords("member_kyc", array('member_kyc.field_count' => '0'), '', array('kyc_id' => 'DESC'), '0');
            }
            else
            {
              if (!in_array($value, $arraid))
              {
                array_push($arraid, $value);
              }


              $allotted_member_id = implode(',', $arraid);
              $update_data = array(
                'allotted_member_id' => $allotted_member_id
              );

              //Update searched member in alloted list- pooja mane : 02-02-2023
              $members = $this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d'),'list_type' => 'New', 'user_id' => $this->session->userdata('kyc_id')));

              //log search member addition activity : pooja mane : 23-05-2023
              $tilte = 'Member added through custom search';
              $description = 'Approver has added ' . $value . ' member';
              $user_id = $this->session->userdata('kyc_id');
              $result = $this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description); //log end


              $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
              //$this->db->where("member_kyc.regnumber = '$value'");
              $this->db->where('member_registration.isactive', '1');
              $this->db->where('member_registration.isdeleted', '0');
              $this->db->where('member_registration.kyc_status', '0');
              $this->db->group_by('member_kyc.regnumber');
              $members = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $value, 'member_kyc.field_count' => '0'), '', array('kyc_id' => 'ASC'), '0', '1');

              $this->session->set_flashdata('success', $value . ' Member added to the current list');
              //$members_arr[]  = $members;
            }
          }
        }
      }


      $emptylistmsg = ' ';
      $data['emptylistmsg']  = $emptylistmsg;
      //$data['result'] = call_user_func_array('array_merge', $members_arr);
      // 
      $data['result'] = $members;
      
      /* Start Code To Get Recent Allotted Member Total Count */
      $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");

      if (!empty($pagination_total_count))
      {
        foreach ($pagination_total_count[0] as $k => $value)
        {
          if ($k == "pagination_total_count")
          {
            $data['totalRecCount'] = $value;
          }
          if ($k == "original_allotted_member_id")
          {
            $data['original_allotted_member_id'] = $value;
          }
        }
      }

      /* Close Code To Get Recent Allotted Member Total Count */
      $this->load->view('admin/kyc/Approver/approver_allocated_list', $data);
    }
    else
    {

      $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New', 'user_type' => 'approver'), 'original_allotted_member_id');

      $allocatedmemberarr = array();
      if (count($kyc_data) > 0)
      {
        foreach ($kyc_data as $row)
        {
          $allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
        }
      }

      $member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and (kyc_state = 2 OR   kyc_state = 1 )AND field_count > 0");

      $recommendedmemberarr = array();
      if ($member_kyc->num_rows() > 0)
      {
        foreach ($member_kyc->result_array()  as $row)
        {
          $recommendedmemberarr[] = $row['regnumber'];
        }
      }

      $data_array = array();
      if (count($allocatedmemberarr) > 0)
      {
        // get the column data in a single array
        $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
      }

      // merge allocated member array with recommended members array
      $data_array = array_merge($data_array, $recommendedmemberarr);
      //print_r($data_array);die;
      if (count($data_array) > 0)
      {
        $this->db->where_not_in('mk.regnumber', array_map('stripslashes', $data_array));
      }

      $this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
      $this->db->join('member_registration mr', 'mr.regnumber=mk.regnumber', 'LEFT');
      $this->db->where('mr.registrationtype', 'NM');
      $this->db->where('mr.kyc_edit', '0');
      $this->db->where('mr.isactive', '1');
      $this->db->where('mr.isdeleted', '0');
      $this->db->where('mr.kyc_status', '0');
      $this->db->where('mk.kyc_status', '0');
      $this->db->where_in('mk.kyc_state', '1'); //CHECK FOR STATE 2
      $this->db->where('me.pay_status', '1');
      $this->db->join('member_exam me', 'me.regnumber = mr.regnumber');
      $this->db->join('exam_master e', 'e.exam_code = me.exam_code');
      $this->db->group_by('me.exam_code');

      $mem_list = $this->master_model->getRecords("member_kyc mk", array('mk.field_count' => '0'), '', array('mk.kyc_id' => 'ASC'), '0');

      // echo $this->db->last_query();die;
      $mem_exm_arr[] = $mem_list;
      $data['mem_exm_arr'] = $mem_exm_arr;

      $this->load->view('admin/kyc/Approver/allocation_type', $data);
    }
  }

  public function approver_allocated_list()
  {

    $tilte = $type = '';
    $description = $emptylistmsg = '';
    $allocates_arr = $members_arr = $result = $array = array();
    $data['result'] = array();
    $regstr = $searchText = $searchBy = '';
    $searchBy_regtype = '';
    $today = date('Y-m-d H:i:s');
    $per_page = 100;
    $last = 99;
    $start = 0;
    $list_type = 'New';
    $exam_code = $_POST['select_exm_cd'];
    //print_r($_POST);die;

    $page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
    if ($this->input->post('regnumber') != '')
    {
      $searchBy = $this->input->post('regnumber');
    }
    if ($this->input->post('registrationtype') != '')
    {
      $searchBy_regtype = $this->input->post('registrationtype');
    }

    $registrationtype = '';
    $data['reg_no'] = ' ';

    if ($page != 0)
    {
      $start = $page - 1;
    }
    $allocates = array();
    //get  all  user loging today 
    if (isset($_POST['selectby']))
    {

      $type = $_POST['selectby'];
      $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_type' => 'approver','list_type' => 'New'), 'original_allotted_member_id');

      $allocatedmemberarr = array();
      if (count($kyc_data) > 0)
      {
        foreach ($kyc_data as $row)
        {
          $allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
        }
      }

      $member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and (kyc_state = 2 OR   kyc_state = 1 )AND field_count > 0");
      // echo $this->db->last_query();die;
      $recommendedmemberarr = array();
      if ($member_kyc->num_rows() > 0)
      {
        foreach ($member_kyc->result_array()  as $row)
        {
          $recommendedmemberarr[] = $row['regnumber'];
        }
      }

      $data_array = array();
      if (count($allocatedmemberarr) > 0)
      {
        // get the column data in a single array
        $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
      }

      // merge allocated member array with recommended members array
      $data_array = array_merge($data_array, $recommendedmemberarr);

      if (count($data_array) > 0)
      {
        $this->db->where_not_in('member_kyc.regnumber', array_map('stripslashes', $data_array));
      }

      // Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
      if ($type == 'NM')
      {
        $this->db->select('exam_code');
        $this->db->join('member_exam', 'member_exam.regnumber = member_kyc.regnumber', 'INNER');
        $this->db->where('member_exam.exam_code', $exam_code);
        $this->db->where('member_exam.pay_status', '1');
        $this->db->group_by('member_exam.regnumber');
      }
      // Added exam code condition End Pooja Mane 2023-10-13

      $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
      $this->db->where('member_registration.kyc_status', '0');
      $this->db->where('member_registration.kyc_edit', '0');
      $this->db->where('member_kyc.kyc_status', '0');
      $this->db->where('member_registration.isactive', '1');
      $this->db->where('member_registration.isdeleted', '0');
      $this->db->where('member_registration.registrationtype', $type);
      $this->db->where_in('kyc_state', '1'); //array(1,2)

      $recommender = 'recommender';
      $this->db->where("user_type LIKE '%$recommender%'");
      $r_list = $this->master_model->getRecords("member_kyc", array('field_count' => 0, 'approved_by' => 0), 'member_kyc.regnumber,kyc_id,namesub,dateofbirth,associatedinstitute,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
				mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,isactive,mem_associate_inst,field_count', array('kyc_id' => 'ASC'), $start, $per_page);//,employee_proof,mem_declaration added by pooja mane 2024-08-13
      // print_r($r_list);//DIE;
      // echo '<br><br><br>'.count($r_list).$this->db->last_query();die;
      
      $today = date("Y-m-d H:i:s");
      $row_count = $this->master_model->getRecordCount("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New'));


      if ($row_count == 0)
      {
        $regstr = '';
        foreach ($r_list  as $row)
        {
          $allocates_arr[] = $row['regnumber'];
        }
        if (count($allocates_arr) > 0)
        {
          $regstr = implode(',', $allocates_arr);
        }
        //print_r($regstr);exit;
        if ($regstr != '')
        {

          $insert_data = array(
            'user_type'      => $this->session->userdata('role'),
            'user_id'        => $this->session->userdata('kyc_id'),
            'allotted_member_id'  => $regstr,
            'original_allotted_member_id'  => $regstr,
            'allocated_count'     => count($allocates_arr),
            'allocated_list_count'     => '1',
            'date'                  => $today,
            'list_type'             => 'New',
            'pagination_total_count ' => count($allocates_arr)
          );
          $this->master_model->insertRecord('admin_kyc_users', $insert_data);
          // 
          //log activity 
          $tilte = 'Approver  KYC  member list allocation';
          $description = 'Approver has allocated ' . count($allocates_arr) . ' member';
          $user_id = $this->session->userdata('kyc_id');
          $this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);
        }
      }
    }
    $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));

    if (count($allocated_member_list) > 0)
    {
      $data['count'] = $allocated_member_list[0]['allocated_count'];
    }
    else
    {
      $data['count'] = 0;
    }

    if (count($allocated_member_list) > 0)
    {
      $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

      if (count($arraid) > 0)
      {
        if ($searchBy != '' || $searchBy_regtype != '')
        {
          if ($searchBy != '' && $searchBy_regtype != '')
          {
            $this->db->where('regnumber', $searchBy);
            $this->db->where('registrationtype', $searchBy_regtype);
            $members = $this->master_model->getRecords("member_registration");
          }
          ///search by registration number
          else if ($searchBy != '')
          {
            $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
            $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
            $this->db->where('regnumber', $searchBy);
            $members = $this->master_model->getRecords("member_registration");
            //$row=$searchBy;
          }
          ///search by registration type
          else if ($searchBy_regtype != '')
          {
            $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
            $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
            $this->db->where('registrationtype', $searchBy_regtype);
            $members = $this->master_model->getRecords("member_registration");
          }
          if (count($members) > 0)
          {
            foreach ($members as $row)
            {
              $members_arr[][] = $row;
            }
          }
        }
        else
        {

          if (isset($_POST['reset']))
          {
            $data['reset'] = $reset = '1'; //flag for reset btn added by pooja mane on 11-04-23
          }

          if (isset($_POST['btnSearch']))
          {
            $key = $_POST['searchBy'];
            $value = str_replace(' ', '', $_POST['SearchVal']);

            if ($key == '01' && !empty($value))
            {
              $this->db->where("regnumber = '$value'");
            }
          }

          if (in_array($value, $arraid))
          {
            $this->session->set_flashdata('success', $value . ' present in the current list');
          }

          if (isset($_POST['btnSearch']))
          {

            $kyc_done = $this->master_model->getRecords("member_registration", array('kyc_status' => '1', 'regnumber' => $value)); //check is kyc is done

            $mem_exist = $this->master_model->getRecords("member_registration", array('regnumber' => $value, 'isactive' => '1')); //check if member does not exist

            if (count($kyc_done))
            {
              $this->session->set_flashdata('success', 'KYC of ' . $value . ' this record is completed');
            }
            elseif (count($mem_exist) == 0)
            {
              $this->session->set_flashdata('error', '' . $value . ' Member number does not exist');
            }
            else
            {
              $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
              $today = date('Y-m-d');
              $kyc_data = $this->db->query("SELECT * FROM admin_kyc_users WHERE user_type = 'approver' AND DATE(date) LIKE '%$today%' AND find_in_set('" . $value . "', original_allotted_member_id)")->result_array();

              if (count($kyc_data) > 0)
              {
                $this->session->set_flashdata('error', $value . ' Member already allocated to other user');
              }
              elseif (!in_array($value, $arrstr && count($kyc_data) < 0))
              {
                array_push($arrstr, $value);
                $this->session->set_flashdata('success', $value . ' Member added to the current list');
              }

              $allotted_member_id = implode(',', $arrstr);
              //print_r($arrstr);DIE;
              $update_data = array(
                'allotted_member_id' => $allotted_member_id
              );

              //Update searched member in alloted list- pooja mane : 02-02-2023
              $arr = $this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d'), 'user_type' => 'approver', 'list_type' => 'New', 'user_id' => $this->session->userdata('kyc_id')));

              //log search member addition activity : pooja mane : 23-05-2023
              $tilte = 'Member added through custom search';
              $description = 'Recommender has added ' . $value . ' member';
              $user_id = $this->session->userdata('kyc_id');
              $result = $this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);

              //removed 3 days buffer condition
              if (count($kyc_data) == 0)
              {
                $this->db->where("regnumber = '$value'");
              }

              //$this->db->where('kyc_edit', '0');
              $this->db->where('isactive', '1');
              $this->db->where('kyc_status', '0');
              $this->db->where('DATE(createdon) !=', '00-00-0000');
              /// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
              if ($type == 'NM')
              {   //echo $type;die;
                $this->db->select('me.exam_code,member_registration.*');
                $this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber', 'INNER');
                $this->db->where('me.exam_code', $exam_code);
                $this->db->where('me.pay_status', '1');
              }
              // Added exam code condition End Pooja Mane 2023-10-13
              $this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arrstr));
              $members = $this->master_model->getRecords("member_registration");
            }
          }

          //default allocation list for 100 member
          foreach ($arraid as $row)
          {

            $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
            $this->db->where('member_registration.isactive', '1');
            $this->db->where('member_registration.isdeleted', '0');
            $this->db->where('member_kyc.kyc_status', '0');
            $this->db->where('member_registration.kyc_status', '0');
            //$this->db->where('member_registration.registrationtype', $type);
            $members = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'member_kyc.field_count' => '0', 'kyc_state' => 1), '', array('kyc_id' => 'DESC'), '0', '1');
            $members_arr[]  = $members;
          } //echo $this->db->last_query();die;
        }
      }

      $data['result'] = call_user_func_array('array_merge', $members_arr);
    }
    $total_row = 100;
    $url = base_url() . "admin/kyc/Approver/approver_allocated_list/";
    $config = pagination_init($url, $total_row, $per_page, 2);
    $this->pagination->initialize($config);
    $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
    $str_links = $this->pagination->create_links();
    //var_dump($str_links);
    $data["links"] = $str_links;

    if (($start + $per_page) > $total_row)
      $end_of_total = $total_row;
    else
      $end_of_total = $start + $per_page;

    if ($total_row)
      $data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
    else
      $data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

    $data['index'] = $start + 1;

    $emptylistmsg = ' No records available...!!<br />
		<a href=' . base_url() . 'admin/kyc/Approver/allocation_type/>Back</a>';

    /* Start Code To Get Recent Allotted Member Total Count */
    $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'),'user_type' => 'approver', 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
    if (!empty($pagination_total_count))
    {
      foreach ($pagination_total_count[0] as $k => $value)
      {
        if ($k == "pagination_total_count")
        {
          $data['totalRecCount'] = $value;
        }
        if ($k == "original_allotted_member_id")
        {
          $data['original_allotted_member_id'] = $value;
        }
      }
    }
    /* Close Code To Get Recent Allotted Member Total Count */


    $data['emptylistmsg']  = $emptylistmsg;
    $this->db->distinct('registrationtype');
    $data['mem_type'] = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('registrationtype' => 'ASC'));
    //print_r($data);die;
    $this->load->view('admin/kyc/Approver/approver_allocated_list', $data);
  }

  // Function added end for new member KYC list by Pooja mane : 2023-10-31
  public function approver_allocated_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
  {

    if ($regnumber)
    {
      $oldfilepath = $file_path = $photo_file = '';
      $state = $next_id = $success = $error = $description = '';
      $data['result'] = $name = $update_data = $old_user_data = $member_kyc_lastest_record = $sql = array();
      $new_arrayid = $noarray = array();
      $today = $date = date('Y-m-d H:i:s');
      $registrationtype = '';
      $data['reg_no'] = ' ';
      $field_count = 0;
      $fedai_array = array(1009);//added by pooja mane 2024-08-12

      // recommendation submit

      if (isset($_POST['btnSubmitRecmd']))
      {
        $select = 'regnumber,registrationtype,email,createdon,excode';
        $data = $this->master_model->getRecords('member_registration', array(
          'regnumber' => $regnumber,
          'isactive' => '1',
          'kyc_status' => '0'
        ), $select);
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }

        $regnumber = $data[0]['regnumber'];

        // optional
        // echo "You chose the following color(s): <br />";

        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {

            // echo $cbox."<br />";

            $check_arr[] = $cbox;
          }
        }
        // print_r($check_arr);die;
        $msg = 'Edit your profile as :-';
        if (count($check_arr) > 0)
        {
          if (in_array('name_checkbox', $check_arr))
          {
            $name_checkbox = '1';
          }
          else
          {
            $name_checkbox = '0';
            $field_count++;
            $update_data[] = 'Name';
            $msg .= 'Name,';
          }

          if (in_array('dob_checkbox', $check_arr))
          {
            $dob_checkbox = '1';
          }
          else
          {
            $dob_checkbox = '0';
            $field_count++;
            $update_data[] .= 'DOB';
            $msg .= 'Date of Birth ,';
          }

          if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM')
          {
            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
            }
          }

          elseif ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A')
          {
            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
              $field_count++;
              $update_data[] .= 'Employer';
              $msg .= 'Employer,';
            }
          }

          if (in_array('photo_checkbox', $check_arr))
          {
            $photo_checkbox = '1';
          }
          else
          {
            $photo_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Photo';
            $msg .= 'Photo,';
          }

          if (in_array('sign_checkbox', $check_arr))
          {
            $sign_checkbox = '1';
          }
          else
          {
            $sign_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Sign';
            $msg .= 'Sign,';
          }

          if (in_array('idprf_checkbox', $check_arr))
          {
            $idprf_checkbox = '1';
          }
          else
          {
            $idprf_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Id-proof';
            $msg .= 'Id-proof';
          }

          if ($data[0]['registrationtype'] == 'O')
          {
            if ($data[0]['createdon'] >= '2022-04-01')
            {
              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                $field_count++;
                $update_data[] .= 'Declaration';
                $msg .= 'Declaration';
              }
            }
            else
            {
              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                // no field_count is required here (declaration optional for old members)
              }
            }
          }
          else
          {
            $declaration_checkbox = '0';
          }

          if (in_array('idprf_checkbox', $check_arr))
          {
            $idprf_checkbox = '1';
          }
          else
          {
            $idprf_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Id-proof';
            $msg .= 'Id-proof';
          }

          // EMP ID/DECLARATION CHECK FOR NM FEDAI CODE ADDED BY POOJA MANE 2024-08-13
          if ($data[0]['registrationtype'] == 'NM' && in_array($data[0]['excode'], $fedai_array)) {
            if (in_array('empidprf_checkbox', $check_arr)) {
              $empidprf_checkbox = '1';
            } else {
              $empidprf_checkbox = '0';
              $field_count++;
              $update_data[] = 'Employment-proof';
              $msg .= 'Employment-proof';
            }
          }
          
          if ($data[0]['registrationtype'] == 'NM' && in_array($data[0]['excode'], $fedai_array)) {
            if (in_array('declaration_checkbox', $check_arr)) {
              $declaration_checkbox = '1';
            } else {
              $declaration_checkbox = '0';
              $field_count++;
              $update_data[] = 'Declaration';
              $msg .= 'Declaration';
            }
          }
          // EMP ID/DECLARATION CHECK FOR NM FEDAI CODE ADDED BY POOJA MANE 2024-08-13
        }
        else
        {
          $name_checkbox = '0';
          $msg .= 'Name,';
          $field_count++;
          $update_data[] .= 'Name';
          $dob_checkbox = '0';
          $msg .= 'Date of Birth ,';
          $field_count++;
          $update_data[] .= 'DOB';
          $emp_checkbox = '1';
          $msg .= 'Employer,';
          $field_count++;
          $update_data[] .= 'Employer';
          $photo_checkbox = '0';
          $msg .= 'Photo,';
          $field_count++;
          $update_data[] .= 'Photo';
          $sign_checkbox = '0';
          $msg .= 'Sign,';
          $field_count++;
          $update_data[] .= 'Sign';
          $idprf_checkbox = '0';
          $msg .= 'Id-proof,';
          $field_count++;
          $update_data[] .= 'Id-proof';

          if ($data[0]['registrationtype'] == 'O')
          {
            if ($data[0]['createdon'] >= '2022-04-01')
            {
              $declaration_checkbox = '0';
              $msg .= 'Declaration';
              $field_count++;
              $update_data[] .= 'Declaration';
            }
            else
            {
              $declaration_checkbox = '0';
              // no field_count is required here (declaration optional for old members)
            }
          }
          else
          {
            $declaration_checkbox = '0';
          }

          // EMP ID/DECLARATION CHECK FOR NM FEDAI CODE ADDED BY POOJA MANE 2024-08-13
          if ($data[0]['registrationtype'] == 'NM') 
          {
            if (in_array($data[0]['excode'],$fedai_array))
            {
              $declaration_checkbox = '0';
              $msg .= 'Declaration';
              $field_count++;
              $update_data[] = 'Declaration';

              $empidprf_checkbox = '0';
              $msg .= 'Employment-proof,';
              $field_count++;
              $update_data[] = 'Employment-proof';

            } else 
            {
              $declaration_checkbox = '0';
              $empidprf_checkbox = '0';
            }
          }
          // EMP ID/DECLARATION CHECK FOR NM FEDAI CODE ADDED BY POOJA MANE 2024-08-13
        }
        //ECHO '****';//DIE;
        $email = $data[0]['email'];
        if ($data[0]['registrationtype'] == 'O')
        {
          // Declaration mandatory for those users who are registered from 1 april 2022 
          // (its a date of declaration feature upload date on live)
          if ($data[0]['createdon'] >= '2022-04-01')
          {
            if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1' && $declaration_checkbox == '1')
            {
              // $error='Please  unchecked atleast one checkbox!!';
              $this->session->set_flashdata('error', 'Please uncheck atleast one checkbox!!');
            }
            else
            {
              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_user_data = $this->master_model->getRecords('member_registration', array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $insert_data = array(
                'regnumber' => $data[0]['regnumber'],
                'mem_type' => $data[0]['registrationtype'],
                'mem_name' => $name_checkbox,
                // 'email_address'			=> $data[0]['email'],
                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => $field_count,
                'old_data' => serialize($old_user_data),
                'kyc_status' => '0',
                'kyc_state' => '1',
                'recommended_by' => $this->session->userdata('kyc_id'),
                'user_type' => $this->session->userdata('role'),
                'recommended_date' => $today,
                'record_source' => 'New'
              );

              // insert the record and get latest  kyc_id
              $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );

              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

                // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
                // $success='KYC  recommend for the candidate & Email sent successfully !!';
                // log activity
                // get recommended fields data from member registration -

                $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
                $old_data = $this->master_model->getRecords("member_registration", array(
                  'regnumber' => $regnumber,
                  'isactive' => '1'
                ), $select);
                $log_desc['old_data'] = $old_data;
                $log_desc['inserted_data'] = $insert_data;
                $description = serialize($log_desc);
                $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

                // email log

                $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

                // make recommended fields empty  -

                if (in_array('Name', $update_data))
                {

                  $updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                // if (in_array('DOB', $update_data))
                // {
                //   $updatedata['dateofbirth'] = '0000-00-00';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }

                // if (in_array('Employer', $update_data))
                // {
                //   $updatedata['associatedinstitute'] = '';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }
                // -------check for  photo -----------#

                if (in_array('Photo', $update_data))
                {
                  $updatedata['scannedphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'p');
                  $noarray = explode('/p_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_p_' . $noarray[1];

                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                // -------end check for  photo -----------#

                // ------- check for  signature-----------#

                if (in_array('Sign', $update_data))
                {

                  $updatedata['scannedsignaturephoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 's');
                  $noarray = explode('/s_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_s_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                      //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }

                // -------End check for  photo -----------#


                // ------- check for  idproof-----------#

                if (in_array('Id-proof', $update_data))
                {

                  $updatedata['idproofphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'pr');
                  $noarray = explode('/pr_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_pr_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                  // -------End  check for id proof -----------#


                }

                // ------- check for declaration -----------#
                if (in_array('Declaration', $update_data))
                {
                  $updatedata['declaration'] = '';
                  $oldfilepath = get_img_name($regnumber, 'declaration');
                  $noarray = explode('/declaration_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath;
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_declaration_' . $noarray[1];
                    $description .= ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended declaration rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member declaration  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  }
                }
                // -------End  check for declaration -----------#

                if (!empty($updatedata))
                {
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                $member = $this->master_model->getRecords("admin_kyc_users", array(
                  'DATE(date)' => date('Y-m-d'),
                  'list_type' => 'New',
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                $arrayid = explode(',', $member[0]['allotted_member_id']);
                $index = array_search($regnumber, $arrayid, true);

                // get next record

                $currentid = $index;
                $nextid = $currentid + 1;
                if (array_key_exists($nextid, $arrayid))
                {
                  $next_id = $arrayid[$nextid];
                }
                else
                {
                  $next_id = $arrayid[0];
                }

                // end of next record
                // unset the  current id index

                unset($arrayid[$index]);
                if (count($arrayid) > 0)
                {
                  foreach ($arrayid as $row)
                  {
                    $new_arrayid[] = $row;
                  }
                }

                if (count($new_arrayid) > 0)
                {
                  $regstr = implode(',', $new_arrayid);
                }
                else
                {
                  $regstr = '';
                  $next_id = '';
                }

                $update_data = array(
                  'allotted_member_id' => $regstr
                );
                $this->db->where('DATE(date)', date('Y-m-d'));
                $this->db->where('list_type', 'New');
                $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                /* Start Code To Showing Count On Member List*/
                if ($next_id == '')
                {
                  $next_id = 0;
                }

                // $totalRecCount=$this->get_App_allocation_type_cnt();

                if ($srno > $totalRecCount)
                {

                  // $srno=$totalRecCount;

                  $srno = 1;
                }
                else
                {
                  $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                  $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                  $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                  $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                  $memberNo = $next_id;
                  $updated_list_index = array_search($memberNo, $reversedArr_list);
                  $srno = $updated_list_index;
                }

                redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
                /* Close Code To Showing Count On Member List*/
              }
            }
          }
          else
          {
            if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1')
            {
              // $error='Please  unchecked atleast one checkbox!!';
              $this->session->set_flashdata('error', 'Please uncheck atleast one checkbox!! (except declaration - its optional for this user)');
            }
            else
            {
              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_user_data = $this->master_model->getRecords('member_registration', array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $insert_data = array(
                'regnumber' => $data[0]['regnumber'],
                'mem_type' => $data[0]['registrationtype'],
                'mem_name' => $name_checkbox,
                // 'email_address'			=> $data[0]['email'],
                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => $field_count,
                'old_data' => serialize($old_user_data),
                'kyc_status' => '0',
                'kyc_state' => '1',
                'recommended_by' => $this->session->userdata('kyc_id'),
                'user_type' => $this->session->userdata('role'),
                'recommended_date' => $today,
                'record_source' => 'New'
              );

              // insert the record and get latest  kyc_id
              $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );

              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

                // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
                // $success='KYC  recommend for the candidate & Email sent successfully !!';
                // log activity
                // get recommended fields data from member registration -

                $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
                $old_data = $this->master_model->getRecords("member_registration", array(
                  'regnumber' => $regnumber,
                  'isactive' => '1'
                ), $select);
                $log_desc['old_data'] = $old_data;
                $log_desc['inserted_data'] = $insert_data;
                $description = serialize($log_desc);
                $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

                // email log

                $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

                // make recommended fields empty  -

                if (in_array('Name', $update_data))
                {

                  $updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                // if (in_array('DOB', $update_data))
                // {
                //   $updatedata['dateofbirth'] = '0000-00-00';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }

                // if (in_array('Employer', $update_data))
                // {
                //   $updatedata['associatedinstitute'] = '';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }
                // -------check for  photo -----------#

                if (in_array('Photo', $update_data))
                {
                  $updatedata['scannedphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'p');
                  $noarray = explode('/p_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_p_' . $noarray[1];

                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                // -------end check for  photo -----------#

                // ------- check for  signature-----------#

                if (in_array('Sign', $update_data))
                {

                  $updatedata['scannedsignaturephoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 's');
                  $noarray = explode('/s_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_s_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                      //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }

                // -------End check for  photo -----------#


                // ------- check for  idproof-----------#

                if (in_array('Id-proof', $update_data))
                {

                  $updatedata['idproofphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'pr');
                  $noarray = explode('/pr_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_pr_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                  // -------End  check for id proof -----------#


                }


                if (!empty($updatedata))
                {
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                $member = $this->master_model->getRecords("admin_kyc_users", array(
                  'DATE(date)' => date('Y-m-d'),
                  'list_type' => 'New',
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                $arrayid = explode(',', $member[0]['allotted_member_id']);
                $index = array_search($regnumber, $arrayid, true);

                // get next record

                $currentid = $index;
                $nextid = $currentid + 1;
                if (array_key_exists($nextid, $arrayid))
                {
                  $next_id = $arrayid[$nextid];
                }
                else
                {
                  $next_id = $arrayid[0];
                }

                // end of next record
                // unset the  current id index

                unset($arrayid[$index]);
                if (count($arrayid) > 0)
                {
                  foreach ($arrayid as $row)
                  {
                    $new_arrayid[] = $row;
                  }
                }

                if (count($new_arrayid) > 0)
                {
                  $regstr = implode(',', $new_arrayid);
                }
                else
                {
                  $regstr = '';
                  $next_id = '';
                }

                $update_data = array(
                  'allotted_member_id' => $regstr
                );
                $this->db->where('DATE(date)', date('Y-m-d'));
                $this->db->where('list_type', 'New');
                $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                /* Start Code To Showing Count On Member List*/
                if ($next_id == '')
                {
                  $next_id = 0;
                }

                // $totalRecCount=$this->get_App_allocation_type_cnt();

                if ($srno > $totalRecCount)
                {

                  // $srno=$totalRecCount;

                  $srno = 1;
                }
                else
                {
                  $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                  $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                  $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                  $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                  $memberNo = $next_id;
                  $updated_list_index = array_search($memberNo, $reversedArr_list);
                  $srno = $updated_list_index;
                }

                redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
                /* Close Code To Showing Count On Member List*/
              }
            }
          } // ($data[0]['createdon'] >= '2022-04-01') (else closing)
        }
        else
        { 
          if($data[0]['registrationtype'] == 'NM' && in_array($data[0]['excode'], $fedai_array) && $name_checkbox == '1' && $dob_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1' && $empidprf_checkbox == '1' && $declaration_checkbox == '1')
          { //echo 'Please  uncheck atleast one checkbox';die;
            $this->session->set_flashdata('error', 'Please  uncheck atleast one checkbox!!');
          }
          elseif ((!in_array($data[0]['excode'], $fedai_array)) && $name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1')
          {
              // $error='Please  unchecked atleast one checkbox!!';
              $this->session->set_flashdata('error', 'Please  uncheck atleast one checkbox!!');
          }
          else
          {
            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
            $old_user_data = $this->master_model->getRecords('member_registration', array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));
            $insert_data = array(
              'regnumber' => $data[0]['regnumber'],
              'mem_type' => $data[0]['registrationtype'],
              'mem_name' => $name_checkbox,

              // 'email_address'			=> $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => $emp_checkbox,
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'field_count' => $field_count,
              'old_data' => serialize($old_user_data),
              'kyc_status' => '0',
              'kyc_state' => '1',
              'recommended_by' => $this->session->userdata('kyc_id'),
              'user_type' => $this->session->userdata('role'),
              'recommended_date' => $today,
              'record_source' => 'New'
            );

            // insert the record and get latest  kyc_id

            $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);
            if ($data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A')
            {
              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );
            }
            elseif ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM')
            {
              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_NM'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //'to'=> "kyciibf@gmail.com",

                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );
            }

            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

              // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
              // $success='KYC  recommend for the candidate & Email sent successfully !!';
              // log activity
              // get recommended fields data from member registration -

              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_data = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $log_desc['old_data'] = $old_data;
              $log_desc['inserted_data'] = $insert_data;
              $description = serialize($log_desc);
              $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

              // email log

              $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

              // make recommended fields empty  -

              if (in_array('Name', $update_data))
              {

                //$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                $this->db->where('isactive', '1');
                $this->master_model->updateRecord('member_registration', $updatedata, array(
                  'regnumber' => $regnumber
                ));
              }

              // if (in_array('DOB', $update_data))
              // {
              //   $updatedata['dateofbirth'] = '0000-00-00';

              //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

              // }

              // if (in_array('Employer', $update_data))
              // {
              //   $updatedata['associatedinstitute'] = '';

              //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

              // }
              // -------check for  photo -----------#

              if (in_array('Photo', $update_data))
              {
                $updatedata['scannedphoto'] = '';
                $oldfilepath = get_img_name($regnumber, 'p');
                $noarray = explode('/p_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_p_' . $noarray[1];

                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                }
              }
              // -------end check for  photo -----------#

              // ------- check for  signature-----------#

              if (in_array('Sign', $update_data))
              {

                $updatedata['scannedsignaturephoto'] = '';
                $oldfilepath = get_img_name($regnumber, 's');
                $noarray = explode('/s_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_s_' . $noarray[1];
                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                    //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                }
              }

              // -------End check for  photo -----------#


              // ------- check for  idproof-----------#

              if (in_array('Id-proof', $update_data))
              {

                $updatedata['idproofphoto'] = '';
                $oldfilepath = get_img_name($regnumber, 'pr');
                $noarray = explode('/pr_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_pr_' . $noarray[1];
                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                }
              }
              // -------End  check for id proof -----------#

              // --------check for employment proof added by pooja mane 2024-08-13---------#
              if (in_array('Employment-proof', $update_data))
              {
                $updatedata['empidproofphoto'] = '';
                $oldfilepath = get_img_name($regnumber, 'empr');
                $noarray = explode('/empr_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_empr_' . $noarray[1];
                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  { echo ' proof rename';
                    $this->KYC_Log_model->create_log('Recommended Employment proof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete Employment proof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member Employment proof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                }
              }
              // --------check for employment proof end added by pooja mane 2024-08-13-----#
              
              // --------check for declaration form added by pooja mane 2024-08-13---------#
              if (in_array('Declaration', $update_data))
              {
                $updatedata['declaration'] = '';
                $oldfilepath = get_img_name($regnumber, 'declaration');

                $noarray = explode('/declaration_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_declaration_' . $noarray[1];

                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  { echo 'Declaration rename';
                    $this->KYC_Log_model->create_log('Recommended Declaration rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete Declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member Declaration  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                }
              }
              // print_r($update_data);
              // echo '...'.$file_path;die;
              // --------check for declaration form ends added by pooja mane 2024-08-13----#


              if (!empty($updatedata))
              {
                $this->db->where('isactive', '1');
                $this->master_model->updateRecord('member_registration', $updatedata, array(
                  'regnumber' => $regnumber
                ));
              }

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'New',
                'user_id' => $this->session->userdata('kyc_id')
              ));
              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);

              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }

              // end of next record
              // unset the  current id index

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );
              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'New');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));
              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }


              // $totalRecCount=$this->get_App_allocation_type_cnt();

              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }

              redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              /* Close Code To Showing Count On Member List*/
            }
          }
        } // ($data[0]['registrationtype'] == 'O') (else closing)
      }
      //echo 'above kyc submit';die;
      // kyc submit

      if (isset($_POST['btnSubmitkyc']))
      {
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }

        // $regnumber=$data[0]['regnumber'];
        // optional
        // echo "You chose the following color(s): <br />";

        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {

            // echo $cbox."<br />";

            $check_arr[] = $cbox;
          }
        }
        // print_r($check_arr);die;

        $regnumber = $this->input->post('regnumber');
        $this->db->where('regnumber', $regnumber);
        $this->db->where('isactive', '1');
        $member_regtype = $this->master_model->getRecords('member_registration', '', 'registrationtype,createdon,excode');
        // echo '********';die;
        // echo $member_regtype[0]['excode'];die;
        // Kyc complet for NM fedai member 7 fileds are consider by pooja mane 2024-08-13
        if ($member_regtype[0]['registrationtype'] == 'NM' && in_array($member_regtype[0]['excode'],$fedai_array))
        {
          if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr) && in_array('empidprf_checkbox', $check_arr)&& in_array('declaration_checkbox', $check_arr))
          {
            // echo '********';die;
            $new_arrayid = $members = $old_user_data = array();
            $status = '0';
            $state = '1';
            $date = date('Y-m-d H:i:s');
            $declaration_checkbox = '0';

            // $this->db->where('recommended_date',$date);

            $this->db->where('regnumber', $regnumber);
            $member_kyc_details = $this->master_model->getRecords('member_kyc');
            if (isset($_POST['cbox']))
            {
              $name = $this->input->post('cbox');
            }

            // $regnumber=$data[0]['regnumber'];
            // optional
            // echo "You chose the following color(s): <br />";

            $check_arr = array();
            if (count($name) > 0)
            {
              foreach ($name as $cbox)
              {

                // echo $cbox."<br />";

                $check_arr[] = $cbox;
              }
            }

            $msg = 'Edit your profile as :-';
            if (in_array('name_checkbox', $check_arr))
            {
              $name_checkbox = '1';
            }
            else
            {
              $name_checkbox = '0';
              $msg .= 'Name,';
            }

            if (in_array('dob_checkbox', $check_arr))
            {
              $dob_checkbox = '1';
            }
            else
            {
              $dob_checkbox = '0';
              $msg .= 'Date of Birth ,';
            }

            if (in_array('photo_checkbox', $check_arr))
            {
              $photo_checkbox = '1';
            }
            else
            {
              $photo_checkbox = '0';
              $msg .= 'Photo,';
            }

            if (in_array('sign_checkbox', $check_arr))
            {
              $sign_checkbox = '1';
            }
            else
            {
              $sign_checkbox = '0';
              $msg .= 'Sign,';
            }

            if (in_array('idprf_checkbox', $check_arr))
            {
              $idprf_checkbox = '1';
            }
            else
            {
              $idprf_checkbox = '0';
              $msg .= 'Employment-proof';
            }

            if (in_array('empidprf_checkbox', $check_arr))
            {
              $empidprf_checkbox = '1';
            }
            else
            {
              $empidprf_checkbox = '0';
              $msg .= 'Employment-proof';
            }

            if (in_array('declaration_checkbox', $check_arr))
            {
              $declaration_checkbox = '1';
            }
            else
            {
              $declaration_checkbox = '0';
              $msg .= 'Declaration';
            }

            // get the old_data

            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
            $old_user_data = $this->master_model->getRecords('member_registration', array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));


            // $email=$data[0]['email'];

            $update_data = array(
              'mem_name' => $name_checkbox,

              // 'email_address'      => $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => '0',
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'old_data' => serialize($old_user_data),
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id'),
              'approved_date' => $today,

              // 'record_source'      =>'Edit'

            );
            $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
            $this->db->where($sql);
            $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '0'
            ), 'regnumber,kyc_state,kyc_id', array(
              'kyc_id' => 'DESC'
            ));


            $this->db->where('isactive', '1');
            $this->master_model->updateRecord('member_registration', array(
              'kyc_status' => '1'
            ), array(
              'regnumber' => $regnumber
            ));

            // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

            $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
            $this->master_model->updateRecord('member_kyc', $update_data, array(
              'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            ));
            /*reset the dowanload count*/
            $where1 = array(
              'member_number' => $regnumber
            );
            $this->master_model->updateRecord('member_idcard_cnt', array(
              'card_cnt' => '0'
            ), $where1);

            // email send on KYC complete  for DB & NM

            $last_insterid = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id')
            ), 'kyc_id', array(
              'kyc_id' => 'DESC'
            ), '0', '1');


            $nomsg = '';

            $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $msg = implode(',', $update_data);
            $emailerstr = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'KYC_completion_email_to_NM'
            ));

            // echo $emailerstr[0]['emailer_text'];exit;

            $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr = array(

              // 'to'=> "kyciibf@gmail.com",

              'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str
            );
            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)   & Email sent successfully !!');

              // $success='KYC Completed for the candidate & Email sent successfully !!';
              // log activity

              $regnumber = $regnumber;
              $user_id = $this->session->userdata('kyc_id');
              $tilte = 'Member KYC completed';
              $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
              $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid['kyc_id'], $regnumber, $description);

              // $this->session->set_flashdata('success','kyc completed Successfully  !!');
              // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
              // email log

              $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            }

            // rebulide the array

            $member = $this->master_model->getRecords("admin_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'list_type' => 'New',
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);

            // get next record

            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }

            // end of next record

            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }

            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }

            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'New');
            $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }

            // $totalRecCount=$this->get_App_allocation_type_cnt();

            if ($srno > $totalRecCount)
            {

              // $srno=$totalRecCount;

              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
          else
          {

            // ECHO  $error='Select all check box to complete the Kyc !!';DIE;

            $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

            // $error='Select all check box to complete the Kyc !!';
            // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

          }
        }
        // code ends Kyc complet for NM fedai member 7 fileds are consider by pooja mane 2024-08-13
        // Kyc complet for DB and NM  member only 5 fileds are consider
        elseif ($member_regtype[0]['registrationtype'] == 'NM' || $member_regtype[0]['registrationtype'] == 'DB')
        {
          if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
          {

            $new_arrayid = $members = $old_user_data = array();
            $status = '0';
            $state = '1';
            $date = date('Y-m-d H:i:s');
            $declaration_checkbox = '0';

            // $this->db->where('recommended_date',$date);

            $this->db->where('regnumber', $regnumber);
            $member_kyc_details = $this->master_model->getRecords('member_kyc');
            if (isset($_POST['cbox']))
            {
              $name = $this->input->post('cbox');
            }

            // $regnumber=$data[0]['regnumber'];
            // optional
            // echo "You chose the following color(s): <br />";

            $check_arr = array();
            if (count($name) > 0)
            {
              foreach ($name as $cbox)
              {

                // echo $cbox."<br />";

                $check_arr[] = $cbox;
              }
            }

            $msg = 'Edit your profile as :-';
            if (in_array('name_checkbox', $check_arr))
            {
              $name_checkbox = '1';
            }
            else
            {
              $name_checkbox = '0';
              $msg .= 'Name,';
            }

            if (in_array('dob_checkbox', $check_arr))
            {
              $dob_checkbox = '1';
            }
            else
            {
              $dob_checkbox = '0';
              $msg .= 'Date of Birth ,';
            }

            if (in_array('photo_checkbox', $check_arr))
            {
              $photo_checkbox = '1';
            }
            else
            {
              $photo_checkbox = '0';
              $msg .= 'Photo,';
            }

            if (in_array('sign_checkbox', $check_arr))
            {
              $sign_checkbox = '1';
            }
            else
            {
              $sign_checkbox = '0';
              $msg .= 'Sign,';
            }

            if (in_array('idprf_checkbox', $check_arr))
            {
              $idprf_checkbox = '1';
            }
            else
            {
              $idprf_checkbox = '0';
              $msg .= 'Id-proof';
            }

            // get the old_data

            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
            $old_user_data = $this->master_model->getRecords('member_registration', array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));


            // $email=$data[0]['email'];

            $update_data = array(
              'mem_name' => $name_checkbox,

              // 'email_address'			=> $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => '0',
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'old_data' => serialize($old_user_data),
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id'),
              'approved_date' => $today,

              // 'record_source'			=>'Edit'

            );

            $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
            $this->db->where($sql);
            $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '0'
            ), 'regnumber,kyc_state,kyc_id', array(
              'kyc_id' => 'DESC'
            ));


            $this->db->where('isactive', '1');
            $this->master_model->updateRecord('member_registration', array(
              'kyc_status' => '1'
            ), array(
              'regnumber' => $regnumber
            ));

            // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

            $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
            $this->master_model->updateRecord('member_kyc', $update_data, array(
              'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            ));
            /*reset the dowanload count*/
            $where1 = array(
              'member_number' => $regnumber
            );
            $this->master_model->updateRecord('member_idcard_cnt', array(
              'card_cnt' => '0'
            ), $where1);

            // email send on KYC complete  for DB & NM

            $last_insterid = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id')
            ), 'kyc_id', array(
              'kyc_id' => 'DESC'
            ), '0', '1');


            $nomsg = '';

            $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $msg = implode(',', $update_data);
            $emailerstr = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'KYC_completion_email_to_NM'
            ));

            // echo $emailerstr[0]['emailer_text'];exit;

            $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr = array(

              // 'to'=> "kyciibf@gmail.com",

              'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str
            );
            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)   & Email sent successfully !!');

              // $success='KYC Completed for the candidate & Email sent successfully !!';
              // log activity

              $regnumber = $regnumber;
              $user_id = $this->session->userdata('kyc_id');
              $tilte = 'Member KYC completed';
              $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
              $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid['kyc_id'], $regnumber, $description);

              // $this->session->set_flashdata('success','kyc completed Successfully  !!');
              // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
              // email log

              $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            }

            // rebulide the array

            $member = $this->master_model->getRecords("admin_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'list_type' => 'New',
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);

            // get next record

            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }

            // end of next record

            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }

            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }

            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'New');
            $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }

            // $totalRecCount=$this->get_App_allocation_type_cnt();

            if ($srno > $totalRecCount)
            {

              // $srno=$totalRecCount;

              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
          else
          {

            // $error='Select all check box to complete the Kyc !!';

            $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

            // $error='Select all check box to complete the Kyc !!';
            // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

          }
        } //Kyc complet forO,A,F  member only 5 fileds are consider
        elseif ($member_regtype[0]['registrationtype'] == 'A' || $member_regtype[0]['registrationtype'] == 'F')
        {
          if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
          {
            $new_arrayid = $members = array();
            $status = '0';
            $state = '1';
            $date = date("Y-m-d H:i:s");
            $declaration_checkbox = '0';

            // $this->db->where('recommended_date',$date);

            $this->db->where('regnumber', $regnumber);
            $member_kyc_details = $this->master_model->getRecords('member_kyc');
            if (isset($_POST['cbox']))
            {
              $name = $this->input->post('cbox');
            }

            //					$regnumber=$data[0]['regnumber'];
            // optional
            // echo "You chose the following color(s): <br />";

            $check_arr = array();
            if (count($name) > 0)
            {
              foreach ($name as $cbox)
              {

                // echo $cbox."<br />";

                $check_arr[] = $cbox;
              }
            }

            $msg = 'Edit your profile as :-';
            if (in_array('name_checkbox', $check_arr))
            {
              $name_checkbox = '1';
            }
            else
            {
              $name_checkbox = '0';
              $msg .= 'Name,';
            }

            if (in_array('dob_checkbox', $check_arr))
            {
              $dob_checkbox = '1';
            }
            else
            {
              $dob_checkbox = '0';
              $msg .= 'Date of Birth ,';
            }

            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
              // $msg .= 'Associate institude ,';
            }

            if (in_array('photo_checkbox', $check_arr))
            {
              $photo_checkbox = '1';
            }
            else
            {
              $photo_checkbox = '0';
              $msg .= 'Photo,';
            }

            if (in_array('sign_checkbox', $check_arr))
            {
              $sign_checkbox = '1';
            }
            else
            {
              $sign_checkbox = '0';
              $msg .= 'Sign,';
            }

            if (in_array('idprf_checkbox', $check_arr))
            {
              $idprf_checkbox = '1';
            }
            else
            {
              $idprf_checkbox = '0';
              $msg .= 'Id-proof';
            }

            // $email=$data[0]['email'];

            $update_data = array(
              'mem_name' => $name_checkbox,

              // 'email_address'			=> $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => $emp_checkbox,
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id'),
              'approved_date' => $today,

              // 'record_source'			=>'Edit'

            );

            // query to update the latest record of the regnumber

            $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
            $this->db->where($sql);
            $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber
            ), 'regnumber,kyc_state,kyc_id', array(
              'kyc_id' => 'DESC'
            ));

            // print_r($member_kyc_lastest_record );exit;

            $this->db->where('isactive', '1');
            $this->master_model->updateRecord('member_registration', array(
              'kyc_status' => '1'
            ), array(
              'regnumber' => $regnumber
            ));

            // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

            $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
            $this->master_model->updateRecord('member_kyc', $update_data, array(
              'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            ));
            /*reset the dowanload count*/
            $where1 = array(
              'member_number' => $regnumber
            );
            $this->master_model->updateRecord('member_idcard_cnt', array(
              'card_cnt' => '0'
            ), $where1);
            $last_insterid = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id')
            ), 'kyc_id', array(
              'kyc_id' => 'DESC'
            ), '0', '1');

            // print_r($last_insterid[0]['kyc_id']);exit;

            $nomsg = '';
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));
            $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $msg = implode(',', $update_data);
            $emailerstr = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'KYC_completion_email_to_O'
            ));

            // echo $emailerstr[0]['emailer_text'];exit;

            $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr = array(

              //	'to'=> "kyciibf@gmail.com",
              'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str
            );

            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

              // $success='KYC Completed for the candidate & Email sent successfully !!';
              // log activity

              $regnumber = $regnumber;
              $user_id = $this->session->userdata('kyc_id');
              $tilte = 'Member KYC completed';
              $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
              $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

              // $this->session->set_flashdata('success','kyc completed Successfully  !!');
              // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
              // email log

              $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            }

            // rebulide the array

            $member = $this->master_model->getRecords("admin_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'list_type' => 'New',
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);

            // get next record

            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }

            // end of next record

            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }

            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }

            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'New');
            $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }

            // $totalRecCount=$this->get_App_allocation_type_cnt();

            if ($srno > $totalRecCount)
            {

              // $srno=$totalRecCount;

              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
          else
          {
            $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

            // $error='Select all check-box to complete the Kyc !!';
            // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

          }
        }
        elseif ($member_regtype[0]['registrationtype'] == 'O')
        {
          // Declaration mandatory for those users who are registered from 1 april 2022 
          // (its a date of declaration feature upload date on live)
          // echo $data[0]['createdon'];die;
          if ($member_regtype[0]['createdon'] >= '2022-04-01')
          {
            // print_r($check_arr);die;
            if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr) && in_array('declaration_checkbox', $check_arr))
            { 
              // print_r($check_arr);die;
              // echo '***********';die;
              $new_arrayid = $members = array();
              $status = '0';
              $state = '1';
              $date = date("Y-m-d H:i:s");

              // $this->db->where('recommended_date',$date);

              $this->db->where('regnumber', $regnumber);
              $member_kyc_details = $this->master_model->getRecords('member_kyc');
              if (isset($_POST['cbox']))
              {
                $name = $this->input->post('cbox');
              }

              //					$regnumber=$data[0]['regnumber'];
              // optional
              // echo "You chose the following color(s): <br />";

              $check_arr = array();
              if (count($name) > 0)
              {
                foreach ($name as $cbox)
                {

                  // echo $cbox."<br />";

                  $check_arr[] = $cbox;
                }
              }

              $msg = 'Edit your profile as :-';
              if (in_array('name_checkbox', $check_arr))
              {
                $name_checkbox = '1';
              }
              else
              {
                $name_checkbox = '0';
                $msg .= 'Name,';
              }

              if (in_array('dob_checkbox', $check_arr))
              {
                $dob_checkbox = '1';
              }
              else
              {
                $dob_checkbox = '0';
                $msg .= 'Date of Birth ,';
              }

              if (in_array('emp_checkbox', $check_arr))
              {
                $emp_checkbox = '1';
              }
              else
              {
                $emp_checkbox = '1';
                // $msg .= 'Associate institude ,';
              }

              if (in_array('photo_checkbox', $check_arr))
              {
                $photo_checkbox = '1';
              }
              else
              {
                $photo_checkbox = '0';
                $msg .= 'Photo,';
              }

              if (in_array('sign_checkbox', $check_arr))
              {
                $sign_checkbox = '1';
              }
              else
              {
                $sign_checkbox = '0';
                $msg .= 'Sign,';
              }

              if (in_array('idprf_checkbox', $check_arr))
              {
                $idprf_checkbox = '1';
              }
              else
              {
                $idprf_checkbox = '0';
                $msg .= 'Id-proof';
              }

              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                $msg .= 'Declaration';
              }

              // $email=$data[0]['email'];

              $update_data = array(
                'mem_name' => $name_checkbox,

                // 'email_address'			=> $data[0]['email'],

                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id'),
                'approved_date' => $today,

                // 'record_source'			=>'Edit'

              );

              // query to update the latest record of the regnumber

              $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
              $this->db->where($sql);
              $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber
              ), 'regnumber,kyc_state,kyc_id', array(
                'kyc_id' => 'DESC'
              ));

              // print_r($member_kyc_lastest_record );exit;

              $this->db->where('isactive', '1');
              $this->master_model->updateRecord('member_registration', array(
                'kyc_status' => '1'
              ), array(
                'regnumber' => $regnumber
              ));

              // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

              $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
              $this->master_model->updateRecord('member_kyc', $update_data, array(
                'regnumber' => $member_kyc_lastest_record[0]['regnumber']
              ));
              /*reset the dowanload count*/
              $where1 = array(
                'member_number' => $regnumber
              );
              $this->master_model->updateRecord('member_idcard_cnt', array(
                'card_cnt' => '0'
              ), $where1);
              $last_insterid = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id')
              ), 'kyc_id', array(
                'kyc_id' => 'DESC'
              ), '0', '1');

              // print_r($last_insterid[0]['kyc_id']);exit;

              $nomsg = '';
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'KYC_completion_email_to_O'
              ));

              // echo $emailerstr[0]['emailer_text'];exit;

              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //	'to'=> "kyciibf@gmail.com",
                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );

              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

                // $success='KYC Completed for the candidate & Email sent successfully !!';
                // log activity

                $regnumber = $regnumber;
                $user_id = $this->session->userdata('kyc_id');
                $tilte = 'Member KYC completed';
                $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
                $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

                // $this->session->set_flashdata('success','kyc completed Successfully  !!');
                // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
                // email log

                $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
              }

              // rebulide the array

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'New',
                'user_id' => $this->session->userdata('kyc_id')
              ));
              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);

              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }

              // end of next record

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );
              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'New');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));
               echo '<br>6139'.$this->db->last_query();//die;
              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }

              // $totalRecCount=$this->get_App_allocation_type_cnt();

              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }

              redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              /* Close Code To Showing Count On Member List*/
            }
            else
            {
              $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

              // $error='Select all check-box to complete the Kyc !!';
              // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

            }
          }
          else
          {
            if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
            {
              $new_arrayid = $members = array();
              $status = '0';
              $state = '1';
              $date = date("Y-m-d H:i:s");

              // $this->db->where('recommended_date',$date);

              $this->db->where('regnumber', $regnumber);
              $member_kyc_details = $this->master_model->getRecords('member_kyc');
              if (isset($_POST['cbox']))
              {
                $name = $this->input->post('cbox');
              }

              //					$regnumber=$data[0]['regnumber'];
              // optional
              // echo "You chose the following color(s): <br />";

              $check_arr = array();
              if (count($name) > 0)
              {
                foreach ($name as $cbox)
                {
                  // echo $cbox."<br />";
                  $check_arr[] = $cbox;
                }
              }

              $msg = 'Edit your profile as :-';
              if (in_array('name_checkbox', $check_arr))
              {
                $name_checkbox = '1';
              }
              else
              {
                $name_checkbox = '0';
                $msg .= 'Name,';
              }

              if (in_array('dob_checkbox', $check_arr))
              {
                $dob_checkbox = '1';
              }
              else
              {
                $dob_checkbox = '0';
                $msg .= 'Date of Birth ,';
              }

              if (in_array('emp_checkbox', $check_arr))
              {
                $emp_checkbox = '1';
              }
              else
              {
                $emp_checkbox = '1';
                // $msg .= 'Associate institude ,';
              }

              if (in_array('photo_checkbox', $check_arr))
              {
                $photo_checkbox = '1';
              }
              else
              {
                $photo_checkbox = '0';
                $msg .= 'Photo,';
              }

              if (in_array('sign_checkbox', $check_arr))
              {
                $sign_checkbox = '1';
              }
              else
              {
                $sign_checkbox = '0';
                $msg .= 'Sign,';
              }

              if (in_array('idprf_checkbox', $check_arr))
              {
                $idprf_checkbox = '1';
              }
              else
              {
                $idprf_checkbox = '0';
                $msg .= 'Id-proof';
              }

              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
              }

              // $email=$data[0]['email'];

              $update_data = array(
                'mem_name' => $name_checkbox,

                // 'email_address'			=> $data[0]['email'],

                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => 0,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id'),
                'approved_date' => $today,

                // 'record_source'			=>'Edit'

              );

              // query to update the latest record of the regnumber

              $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
              $this->db->where($sql);
              $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber
              ), 'regnumber,kyc_state,kyc_id', array(
                'kyc_id' => 'DESC'
              ));

              // print_r($member_kyc_lastest_record );exit;

              $this->db->where('isactive', '1');
              $this->master_model->updateRecord('member_registration', array(
                'kyc_status' => '1'
              ), array(
                'regnumber' => $regnumber
              ));

              // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

              $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
              $this->master_model->updateRecord('member_kyc', $update_data, array(
                'regnumber' => $member_kyc_lastest_record[0]['regnumber']
              ));
              /*reset the dowanload count*/
              $where1 = array(
                'member_number' => $regnumber
              );
              $this->master_model->updateRecord('member_idcard_cnt', array(
                'card_cnt' => '0'
              ), $where1);
              $last_insterid = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id')
              ), 'kyc_id', array(
                'kyc_id' => 'DESC'
              ), '0', '1');

              // print_r($last_insterid[0]['kyc_id']);exit;

              $nomsg = '';
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'KYC_completion_email_to_O'
              ));

              // echo $emailerstr[0]['emailer_text'];exit;

              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //	'to'=> "kyciibf@gmail.com",
                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );

              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

                // $success='KYC Completed for the candidate & Email sent successfully !!';
                // log activity

                $regnumber = $regnumber;
                $user_id = $this->session->userdata('kyc_id');
                $tilte = 'Member KYC completed';
                $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
                $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

                // $this->session->set_flashdata('success','kyc completed Successfully  !!');
                // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
                // email log

                $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
              }

              // rebulide the array

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'New',
                'user_id' => $this->session->userdata('kyc_id')
              ));
              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);

              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }

              // end of next record

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );
              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'New');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));
              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }

              // $totalRecCount=$this->get_App_allocation_type_cnt();

              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }

              redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              /* Close Code To Showing Count On Member List*/
            }
            else
            {
              $this->session->set_flashdata('error', 'Select all check box to complete the Kyc (Declaration is optional for this user) !!');

              // $error='Select all check-box to complete the Kyc !!';
              // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

            }
          }
        }
      }

      if ($regnumber)
      {
        $select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode,registrationtype,createdon';
        $members = $this->master_model->getRecords("member_registration a", array(
          'regnumber' => $regnumber,
          'isactive' => '1'
        ), $select, "", '0', '1');
      }

      // $this->db->where('field_count','0');

      $recommnended_members_data = $this->master_model->getRecords("member_kyc", array(
        'regnumber' => $regnumber
      ), '', array(
        'kyc_id' => 'DESC'
      ), '0', '1');

      // echo $this->db->last_query();exit;
      // $data['recomended_mem_data']=$recommnended_members_data;

      $data = array(
        'result' => $members,
        'next_id' => $next_id,
        'recomended_mem_data' => $recommnended_members_data,
        'error' => $error,
        'success' => $success
      );
      $data['srno'] = $srno;
      $data['totalRecCount'] = $totalRecCount;
      $this->load->view('admin/kyc/Approver/approver_allocated_screen', $data);
    }
    else
    {
      $this->session->set_flashdata('success', $this->session->flashdata('success'));

      // $this->session->set_flashdata('error','Invalid record!!');

      redirect(base_url() . 'admin/kyc/Approver/approver_allocated_list');
    }
  }
  // Function added end for new member KYC list by Pooja mane : 2023-10-31

  public function allocation_type_nosearch()
  {

    // check allocation type
    $new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));

    if (count($new_allocated_member_list) > 0)
    {
      if ($new_allocated_member_list[0]['allotted_member_id'] == '')
      {
        redirect(base_url() . 'admin/kyc/Approver/next_allocation_type');
      }
    }

    $kyc_start_date = $this->config->item('kyc_start_date');
    $allocated_member_list = $members = array();
    $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));

    //allocated_count
    if (count($allocated_member_list))
    {
      if (count($allocated_member_list) > 0)
      {
        $data['count'] = $allocated_member_list[0]['allocated_count'];
        $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      }
      foreach ($arraid as $row)
      {
        $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
        $this->db->join('admin_kyc_users', 'FIND_IN_SET(member_kyc.regnumber, admin_kyc_users.allotted_member_id)', 'LEFT', FALSE);
        $this->db->where('member_registration.isactive', '1');
        $this->db->where('member_registration.isdeleted', '0');
        $this->db->where('member_registration.kyc_status', '0');
        $this->db->where('admin_kyc_users.list_type', 'NEW');
        $members = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'member_kyc.field_count' => '0'), 'member_kyc.*,member_registration.*,admin_kyc_users.list_type', array('kyc_id' => 'DESC'), '0', '1');

        $members_arr[]  = $members;
      }

      $emptylistmsg = ' ';
      $data['emptylistmsg']  = $emptylistmsg;
      $data['result'] = call_user_func_array('array_merge', $members_arr);
      // print_r($result);
      /* Start Code To Get Recent Allotted Member Total Count */
      $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
      if (!empty($pagination_total_count))
      {
        foreach ($pagination_total_count[0] as $k => $value)
        {
          if ($k == "pagination_total_count")
          {
            $data['totalRecCount'] = $value;
          }
          if ($k == "original_allotted_member_id")
          {
            $data['original_allotted_member_id'] = $value;
          }
        }
      }
      /* Close Code To Get Recent Allotted Member Total Count */

      $this->load->view('admin/kyc/Approver/approver_edited_list', $data);
    }
    else
    {
      $this->load->view('admin/kyc/Approver/allocation_type');
    }
  }

  public function edited_allocation_type()
  {

    // check allocation type
    $new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id' => ''));

    if (count($new_allocated_member_list) > 0)
    {
      if ($new_allocated_member_list[0]['allotted_member_id'] == '')
      {
        redirect(base_url() . 'admin/kyc/Approver/next_edited_allocation_type');
      }
    }

    $kyc_start_date = $this->config->item('kyc_start_date');
    $allocated_member_list = $members = array();
    $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''));

    //allocated_count
    if (count($allocated_member_list))
    {

      if (count($allocated_member_list) > 0)
      {
        $data['count'] = $allocated_member_list[0]['allocated_count'];
        $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      }
      //print_r($arraid);DIE;
      foreach ($arraid as $row)
      {
        $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
        $this->db->join('admin_kyc_users', 'FIND_IN_SET(member_kyc.regnumber, admin_kyc_users.allotted_member_id)', 'LEFT', FALSE);
        $this->db->where('member_registration.isactive', '1');
        $this->db->where('member_registration.isdeleted', '0');
        $this->db->where('member_registration.kyc_status', '0');
        $this->db->where('admin_kyc_users.list_type', 'Edit');
        $members = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'member_kyc.field_count' => '0'), 'member_kyc.*,member_registration.*,admin_kyc_users.list_type', array('kyc_id' => 'ASC'), '0', '1');
        $members_arr[]  = $members;
      }

      if (count($members) == 0)
      {
        if ($members[0]['allotted_member_id'] == '')
        {
          redirect(base_url() . 'admin/kyc/Approver/next_edited_allocation_type');
        }
      }

      $emptylistmsg = ' ';
      $data['emptylistmsg']  = $emptylistmsg;
      $data['result'] = call_user_func_array('array_merge', $members_arr);

      /* Start Code To Get Recent Allotted Member Total Count */
      $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
      $total_rec_count = 0;
      if (!empty($pagination_total_count))
      {
        foreach ($pagination_total_count[0] as $k => $value)
        {
          if ($k == "pagination_total_count")
          {

            $data['totalRecCount'] = $value;
            $total_rec_count++;
          }
          if ($k == "original_allotted_member_id")
          {
            $data['original_allotted_member_id'] = $value;
          }
        }
      }
      /* Close Code To Get Recent Allotted Member Total Count */

      $this->load->view('admin/kyc/Approver/approver_edited_list', $data);
    }
    else
    {

      $member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and (kyc_state = 2 OR   kyc_state = 1 )AND field_count > 0");
      $recommendedmemberarr = array();
      //echo $this->db->last_query();die;

      if ($member_kyc->num_rows() > 0)
      {
        foreach ($member_kyc->result_array()  as $row)
        {
          $recommendedmemberarr[] = $row['regnumber'];
        }
      }

      if (count($recommendedmemberarr) > 0)
      {
        $this->db->where_not_in('mk.regnumber', array_map('stripslashes', $recommendedmemberarr));
      }

      //$array_var = array_map('stripslashes', $recommendedmemberarr);
      //
      $this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
      $this->db->join('member_registration mr', 'mr.regnumber=mk.regnumber', 'LEFT');
      $this->db->where('mr.registrationtype', 'NM');
      $this->db->where('mr.kyc_edit', '1');
      $this->db->where('mr.isactive', '1');
      $this->db->where('mr.isdeleted', '0');
      $this->db->where('mr.kyc_status', '0');
      $this->db->where('mk.kyc_status', '0');
      $this->db->where_in('mk.kyc_state', '1'); //array(1,2)
      $this->db->join('member_exam me', 'me.regnumber = mr.regnumber');
      $this->db->join('exam_master e', 'e.exam_code = me.exam_code');
      $this->db->group_by('me.exam_code');

      $mem_list = $this->master_model->getRecords("member_kyc mk", array('mk.field_count' => '0'), '', array('mk.kyc_id' => 'ASC'), '0');
      $edit_mem_exm_arr[] = $mem_list;
      $data['edit_mem_exm_arr'] = $edit_mem_exm_arr;


      $this->load->view('admin/kyc/Approver/edited_allocation_type', $data);
    }
  }

  public function  next_allocation_type()
  {

    $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New', 'user_type' => 'approver'), 'original_allotted_member_id');


    $allocatedmemberarr = array();
    if (count($kyc_data) > 0)
    {
      foreach ($kyc_data as $row)
      {
        $allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
      }
    }

    $member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and (kyc_state = 2 OR   kyc_state = 1 )AND field_count > 0");


    $recommendedmemberarr = array();
    if ($member_kyc->num_rows() > 0)
    {
      foreach ($member_kyc->result_array()  as $row)
      {
        $recommendedmemberarr[] = $row['regnumber'];
      }
    }

    $data_array = array();
    if (count($allocatedmemberarr) > 0)
    {
      // get the column data in a single array
      $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
    }

    // merge allocated member array with recommended members array
    $data_array = array_merge($data_array, $recommendedmemberarr);
    //print_r($data_array);die;
    if (count($data_array) > 0)
    {
      $this->db->where_not_in('mk.regnumber', array_map('stripslashes', $data_array));
    }

    //$array_var = array_map('stripslashes', $recommendedmemberarr);
    //
    $this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
    $this->db->join('member_registration mr', 'mr.regnumber=mk.regnumber', 'LEFT');
    $this->db->where('mr.registrationtype', 'NM');
    $this->db->where('mr.kyc_edit', '0');
    $this->db->where('mr.isactive', '1');
    $this->db->where('mr.isdeleted', '0');
    $this->db->where('mr.kyc_status', '0');
    $this->db->where('mk.kyc_status', '0');
    $this->db->where_in('mk.kyc_state', '1'); //CHECK FOR STATE 2
    $this->db->where('me.pay_status', '1');
    $this->db->join('member_exam me', 'me.regnumber = mr.regnumber');
    $this->db->join('exam_master e', 'e.exam_code = me.exam_code');
    $this->db->group_by('me.exam_code');

    $mem_list = $this->master_model->getRecords("member_kyc mk", array('mk.field_count' => '0'), '', array('mk.kyc_id' => 'ASC'), '0');

    $next_mem_exm_arr[] = $mem_list;
    $data['next_mem_exm_arr'] = $next_mem_exm_arr;

    $this->load->view('admin/kyc/Approver/next_allocation_type', $data);
  } 

  //to get next 100 allocation ...for  same day
  public function approver_next_allocated_list()
  {
    if (isset($_POST['selectby']))
    {
      $allocatedmemberarr = $data_array = $recommendedmemberarr = $member_kyc_lastest_record = $edit_recommended_list = array();
      $type = $_POST['selectby'];
      $data['count'] = 0;
      $tilte = $allocated_count = $emptylistmsg = '';
      $description = $allotted_member_id = '';
      $allocates_arr = $members_arr = $result = $array = $allocated_member_list = array();
      $data['result'] = array();
      $regstr = $searchText = $searchBy = '';
      $searchBy_regtype = '';
      $today = date('Y-m-d H:i:s');
      $per_page = 100;
      // $per_page = 10;
      $last = 99;
      // $last = 9;
      $start = 0;
      $list_type = 'New';
      $page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
      $check = $kyc_data = array();
      $date = date('Y-m-d H:i:s');
      $exam_code = $_POST['select_exm_cd'];
      $fedai_array = array(1009);//added by pooja mane for fedai


      $check = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));
      // echo $this->db->last_query();die;
      if (count($check) > 0)
      {
        if ($check[0]['allotted_member_id'] == '')
        {
          $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New', 'user_type' => 'approver'), 'allotted_member_id');
          // echo $this->db->last_query();die;
          if (count($kyc_data) > 0)
          {
            foreach ($kyc_data  as $row)
            {
              if ($row['allotted_member_id'] != '')
              {
                $allocatedmemberarr[] = explode(',', $row['allotted_member_id']);
              }
            }
          }


          $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
          //$this->db->group_by('member_kyc.regnumber');
          $this->db->where($sql);
          $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array('kyc_status' => '0'), 'regnumber,field_count,kyc_state,kyc_id', array('kyc_id' => 'DESC'));
          // echo $this->db->last_query();die;
          if (count($member_kyc_lastest_record) > 0)
          {
            foreach ($member_kyc_lastest_record  as $row)
            {
              if ($row['field_count'] == 0)
              {
                $edit_recommended_list[] = $row['regnumber'];
              }
            }
          }
          //$sqltoday=date('Y-m-d');
          if (count($edit_recommended_list) > 0)
          {
            $this->db->where_not_in('regnumber', $edit_recommended_list);
          }
          //print_r($edit_recommended_list);//DIE;
          $this->db->group_by('member_kyc.regnumber');
          $member_kyc = $this->master_model->getRecords("member_kyc", array('kyc_state' => 1), 'MAX(kyc_id),regnumber');
        // echo $this->db->last_query();die;
          if (!empty($member_kyc))
          {
            foreach ($member_kyc  as $row)
            {
              $recommendedmemberarr[] = $row['regnumber'];
            }
          }
          

          if (count($allocatedmemberarr) > 0)
          {  // get the column data in a single array
            $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
            /*if(count($data_array) > 0)
						{
							$this->db->where_not_in('member_registration.regnumber',array_map('stripslashes', $data_array));
						}*/
          }

          // merge allocated member array with recommended members array
          $data_array = array_merge($data_array, $recommendedmemberarr);

          //print_r($recommendedmemberarr);//DIE;
          if (count($data_array) > 0)
          {
            $this->db->where_not_in('member_kyc.regnumber', array_map('stripslashes', $data_array));
          }

          // Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-31
          if ($type == 'NM')
          {
            $this->db->select('exam_code');
            $this->db->join('member_exam', 'member_exam.regnumber = member_kyc.regnumber', 'INNER');
            $this->db->where('member_exam.exam_code', $exam_code);
            $this->db->where('member_exam.pay_status', '1');
          }
          // Added exam code condition End Pooja Mane 2023-10-31

          $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
          $this->db->where('member_registration.kyc_status', '0');
          $this->db->where('member_registration.kyc_edit', 0);
          $this->db->where('member_registration.isactive', '1');
          $this->db->where('member_registration.isdeleted', '0');
          $this->db->where('member_registration.registrationtype', $type);
          $this->db->group_by('member_kyc.regnumber');
          $this->db->where_in('kyc_state', '1'); //array(1,2)

          $recommender = 'recommender';
          $this->db->where("user_type LIKE '%$recommender%'");
          $members = $this->master_model->getRecords("member_kyc", array('field_count' => 0, 'member_kyc.kyc_status' => '0',  'approved_by' => 0), 'member_kyc.regnumber,kyc_id,kyc_state,member_registration.dateofbirth,member_registration.associatedinstitute,namesub,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,mem_associate_inst', array('kyc_id' => 'DESC'), '', $per_page);//,employee_proof,mem_declaration added by pooja mane 2024-08-13

          
          // print_r($members);//die;
          // echo count($members).'<br>'.$this->db->last_query();die;

          $array_string1 = $check[0]['original_allotted_member_id'];
          $allocates_arr1 = explode(',', $array_string1);
          foreach ($members as $row)
          {
            $allocates_arr[] .= $row['regnumber'];
            //$reg[] = $row['regnumber'];
            //$regstr .= $row['regnumber'].',';
          }
          $count = count($allocates_arr);
          $allocated_count = $count + $check[0]['allocated_count'];
          if (count($allocates_arr) > 0)
          {
            $allotted_member_id = implode(',', $allocates_arr);
          }
          $new_array = array_merge($allocates_arr1, $allocates_arr);
          $original_allotted_member_id = implode(',', $new_array);
          //get the  allocated list count
          if ($allotted_member_id == '')
          {
            $list_count = $check[0]['allocated_list_count'];
          }
          else
          {
            $list_count = $check[0]['allocated_list_count'] + 1;
          }
          $update_data = array(
            'user_type'            => $this->session->userdata('role'),
            'user_id'              => $this->session->userdata('kyc_id'),
            'allotted_member_id'    => $allotted_member_id,
            'original_allotted_member_id' => $original_allotted_member_id,
            'allocated_count'          => $allocated_count,
            'allocated_list_count'     => $list_count,
            'date'                         => $today,
            'list_type'                  => $list_type,
            'pagination_total_count ' => $count
          );
          $this->db->where('list_type', 'New');
          $this->db->where('user_id', $this->session->userdata('kyc_id'));
          $this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d')));
          //log activity 
          $tilte = 'Approver got next  New member list allocation ';
          $user_id = $this->session->userdata('kyc_id');
          $this->KYC_Log_model->create_log($tilte, $user_id, '', serialize($update_data));
        }
        $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));

        //allocated_count
        if (count($allocated_member_list) > 0)
        {
          $data['count'] = $allocated_member_list[0]['allocated_count'];
          $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

          //$data['result'] = $members;
          //$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
          if (count($arraid) > 0)
          {
            if ($searchBy != '' || $searchBy_regtype != '')
            {
              if ($searchBy != '' && $searchBy_regtype != '')
              {
                $this->db->where('regnumber', $searchBy);
                $this->db->where('registrationtype', $searchBy_regtype);
                $members = $this->master_model->getRecords("member_registration");
              }
              ///search by registration number
              else if ($searchBy != '')
              {
                $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
                $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
                $this->db->where('regnumber', $searchBy);
                $members = $this->master_model->getRecords("member_registration");
                //$row=$searchBy;
              }
              ///search by registration type
              else if ($searchBy_regtype != '')
              {
                $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
                $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
                $this->db->where('registrationtype', $searchBy_regtype);
                $members = $this->master_model->getRecords("member_registration");
              }
              if (count($members) > 0)
              {
                foreach ($members as $row)
                {
                  $members_arr[][] = $row;
                }
              }
            }
            else
            {
              $this->db->where('member_registration.registrationtype', $type);
              //default allocation list for 100 member
              foreach ($arraid as $row)
              {

                $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
                $this->db->where('member_registration.isactive', '1');
                $members   = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'field_count' => 0, 'kyc_state' => 1), 'member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,mem_associate_inst', array('kyc_id' => 'DESC'), '', 1);//,employee_proof,mem_declaration added by pooja mane 2024-08-13

                $members_arr[] = $members;
              }
            }
          }

          $data['result'] = call_user_func_array('array_merge', $members_arr);
          //$data['result']=$result;

          //$data['reg_no'] = $members[0]['regnumber'];
          //$id=$data['reg_no'];
        }
        $total_row = 100;
        $url = base_url() . "admin/kyc/Approver/approver_allocated_list/";
        $config = pagination_init($url, $total_row, $per_page, 2);
        $this->pagination->initialize($config);
        $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
        $str_links = $this->pagination->create_links();
        //var_dump($str_links);
        $data["links"] = $str_links;

        if (($start + $per_page) > $total_row)
          $end_of_total = $total_row;
        else
          $end_of_total = $start + $per_page;

        if ($total_row)
          $data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
        else
          $data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

        $data['index'] = $start + 1;

        $emptylistmsg = ' No records available...!!<br /><a href=' . base_url() . 'admin/kyc/Approver/next_allocation_type/>Back</a>';

        /* Start Code To Get Recent Allotted Member Total Count */
        $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
        if (!empty($pagination_total_count))
        {
          foreach ($pagination_total_count[0] as $k => $value)
          {
            if ($k == "pagination_total_count")
            {
              $data['totalRecCount'] = $value;
            }
            if ($k == "original_allotted_member_id")
            {
              $data['original_allotted_member_id'] = $value;
            }
          }
        }
        /* Close Code To Get Recent Allotted Member Total Count */

        $data['emptylistmsg']  = $emptylistmsg;

        $this->db->distinct('registrationtype');
        $data['mem_type'] = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('registrationtype' => 'ASC'));
        //print_r($data['result']);die;
        $this->load->view('admin/kyc/Approver/approver_allocated_list', $data);
      }
      else
      {
        redirect(base_url() . 'admin/kyc/Approver/approver_allocated_list');
      }
    }
  }

  public function  next_edited_allocation_type()
  {

    $new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''));

    $member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and (kyc_state = 2 OR   kyc_state = 1 )AND field_count > 0");
    $recommendedmemberarr = array();
    //echo $this->db->last_query();die;

    if ($member_kyc->num_rows() > 0)
    {
      foreach ($member_kyc->result_array()  as $row)
      {
        $recommendedmemberarr[] = $row['regnumber'];
      }
    }

    if (count($recommendedmemberarr) > 0)
    {
      $this->db->where_not_in('mk.regnumber', array_map('stripslashes', $recommendedmemberarr));
    }

    //$array_var = array_map('stripslashes', $recommendedmemberarr);
    //
    $this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
    $this->db->join('member_registration mr', 'mr.regnumber=mk.regnumber', 'LEFT');
    $this->db->where('mr.registrationtype', 'NM');
    $this->db->where('mr.kyc_edit', '1');
    $this->db->where('mr.isactive', '1');
    $this->db->where('mr.isdeleted', '0');
    $this->db->where('mr.kyc_status', '0');
    $this->db->where('mk.kyc_status', '0');
    $this->db->where_in('mk.kyc_state', '1'); //array(1,2)
    $this->db->where('me.pay_status', '1');
    $this->db->join('member_exam me', 'me.regnumber = mr.regnumber');
    $this->db->join('exam_master e', 'e.exam_code = me.exam_code');
    $this->db->group_by('me.exam_code');

    $mem_list = $this->master_model->getRecords("member_kyc mk", array('mk.field_count' => '0'), '', array('mk.kyc_id' => 'ASC'), '0');
    $next_mem_exm_arr[] = $mem_list;
    $data['next_mem_exm_arr'] = $next_mem_exm_arr;

    $this->load->view('admin/kyc/Approver/next_edited_allocation_type', $data);
  }

  //to show the recommended member list 
  public function recommended_list()
  {
    $kycstatus = array();
    $data['result'] = array();
    $date = date('Y-m-d H:i:s');
    $regstr = $searchText = $searchBy = '';
    $searchBy_regtype = '';
    $searchBy = $this->input->post('regnumber');
    $searchBy_regtype = $this->input->post('registrationtype');
    if ($searchBy != '')
    {
      $this->db->where('member_kyc.regnumber', $searchBy);
    }
    elseif ($searchBy_regtype != '')
    {
      $this->db->where('member_kyc.mem_type', $searchBy_regtype);
    }

    $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
    $this->db->where('member_registration.isactive', '1');
    /*
		- by SAGAR WALZADE : 'mem_declaration' column added in below select query
		*/
    $r_list = $this->master_model->getRecords("member_kyc", array('recommended_by' => $this->session->userdata('kyc_id')), 'member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
		mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,mem_declaration,mem_associate_inst,member_registration.dateofbirth,member_registration.associatedinstitute', array('kyc_id' => 'DESC'));//,employee_proof,mem_declaration added by pooja mane 2024-08-13
    //$r_list = $this->master_model->getRecords("member_kyc",array('regnumber'=>$searchBy));


    if (count($r_list))
    {

      $data['result'] =  $r_list;
      $data['status'] =  $kycstatus;
    }

    $this->load->view('admin/kyc/Approver/recommended_list', $data);
  }

  public function approver_next_edited_list()
  {
    //print_r($_POST);die;
    if (isset($_POST['selectby']))
    {
      $allocatedmemberarr = $data_array = $recommendedmemberarr = $member_kyc_lastest_record = $edit_recommended_list = array();
      $type = $_POST['selectby'];
      $data['count'] = 0;
      $tilte = $allocated_count = $emptylistmsg = '';
      $description = $allotted_member_id = '';
      $allocates_arr = $members_arr = $result = $array = $allocated_member_list = array();
      $data['result'] = array();
      $regstr = $searchText = $searchBy = '';
      $searchBy_regtype = '';
      $today = date('Y-m-d H:i:s');
      $per_page = 100;
      // $per_page = 10;
      $last = 99;
      // $last = 9;
      $start = 0;
      $list_type = 'Edit';
      $page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
      $check = $kyc_data = array();
      $date = date('Y-m-d H:i:s');
      $exam_code = $_POST['select_exm_cd'];


      $check = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id' => ''));

      if (count($check) > 0)
      {
        if ($check[0]['allotted_member_id'] == '')
        {
          $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'Edit', 'user_type' => 'approver'), 'allotted_member_id');

          if (count($kyc_data) > 0)
          {
            foreach ($kyc_data  as $row)
            {
              if ($row['allotted_member_id'] != '')
              {
                $allocatedmemberarr[] = explode(',', $row['allotted_member_id']);
              }
            }
          }


          $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
          //$this->db->group_by('member_kyc.regnumber');
          $this->db->where($sql);
          $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array('kyc_status' => '0'), 'regnumber,field_count,kyc_state,kyc_id', array('kyc_id' => 'DESC'));

          if (count($member_kyc_lastest_record) > 0)
          {
            foreach ($member_kyc_lastest_record  as $row)
            {
              if ($row['field_count'] == 0)
              {
                $edit_recommended_list[] = $row['regnumber'];
              }
            }
          }
          //$sqltoday=date('Y-m-d');
          if (count($edit_recommended_list) > 0)
          {
            $this->db->where_not_in('regnumber', $edit_recommended_list);
          }
          $this->db->group_by('member_kyc.regnumber');
          $member_kyc = $this->master_model->getRecords("member_kyc", array('kyc_state' => 1), 'MAX(kyc_id),regnumber');

          if (!empty($member_kyc))
          {
            foreach ($member_kyc  as $row)
            {
              $recommendedmemberarr[] = $row['regnumber'];
            }
          }
          $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber');
          $this->db->where('member_registration.kyc_status', '0');
          $this->db->where('member_kyc.kyc_status', '0');
          $this->db->where('member_registration.kyc_edit', '1');
          $this->db->where('member_registration.isactive', '1');
          $this->db->where('member_registration.isdeleted', '0');
          $this->db->where('member_registration.registrationtype', $type);
          $this->db->group_by('member_kyc.regnumber');

          if (count($allocatedmemberarr) > 0)
          {  // get the column data in a single array
            $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
            /*if(count($data_array) > 0)
						{
							$this->db->where_not_in('member_registration.regnumber',array_map('stripslashes', $data_array));
						}*/
          }
          // merge allocated member array with recommended members array
          $data_array = array_merge($data_array, $recommendedmemberarr);
          if (count($data_array) > 0)
          {
            $this->db->where_not_in('member_registration.regnumber', array_map('stripslashes', $data_array));
          }

          // Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-31
          if ($type == 'NM')
          {
            $this->db->select('exam_code');
            $this->db->join('member_exam', 'member_exam.regnumber = member_kyc.regnumber', 'INNER');
            $this->db->where('member_exam.exam_code', $exam_code);
            $this->db->where('member_exam.pay_status', '1');
          }
          // Added exam code condition End Pooja Mane 2023-10-31
          $this->db->where_in('kyc_state', array('1'));//, 2
          $members   = $this->master_model->getRecords("member_kyc", array('field_count' => '0'), 'kyc_id,member_kyc.regnumber,kyc_id,kyc_state,member_registration.dateofbirth,member_registration.associatedinstitute,namesub,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,mem_associate_inst', array('kyc_id' => 'ASC'), '', $per_page);//,employee_proof,mem_declaration added by pooja mane 2024-08-13

          // echo count($members).$this->db->last_query();die;

          $array_string1 = $check[0]['original_allotted_member_id'];
          $allocates_arr1 = explode(',', $array_string1);
          foreach ($members as $row)
          {
            $allocates_arr[] .= $row['regnumber'];
            //$reg[] = $row['regnumber'];
            //$regstr .= $row['regnumber'].',';
          }
          $count = count($allocates_arr);
          $allocated_count = $count + $check[0]['allocated_count'];
          if (count($allocates_arr) > 0)
          {
            $allotted_member_id = implode(',', $allocates_arr);
          }
          $new_array = array_merge($allocates_arr1, $allocates_arr);
          $original_allotted_member_id = implode(',', $new_array);
          //get the  allocated list count
          if ($allotted_member_id == '')
          {
            $list_count = $check[0]['allocated_list_count'];
          }
          else
          {
            $list_count = $check[0]['allocated_list_count'] + 1;
          }
          $update_data = array(
            'user_type'            => $this->session->userdata('role'),
            'user_id'              => $this->session->userdata('kyc_id'),
            'allotted_member_id'    => $allotted_member_id,
            'original_allotted_member_id' => $original_allotted_member_id,
            'allocated_count'          => $allocated_count,
            'allocated_list_count'     => $list_count,
            'date'                         => $today,
            'list_type'                  => $list_type,
            'pagination_total_count ' => $count
          );
          $this->db->where('list_type', 'Edit');
          $this->db->where('user_id', $this->session->userdata('kyc_id'));
          $this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d')));
          //log activity 
          $tilte = 'Approver got next  New member list allocation ';
          $user_id = $this->session->userdata('kyc_id');
          $this->KYC_Log_model->create_log($tilte, $user_id, '', serialize($update_data));
        }
        $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''));

        //allocated_count
        if (count($allocated_member_list) > 0)
        {
          $data['count'] = $allocated_member_list[0]['allocated_count'];
          $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

          //$data['result'] = $members;
          //$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
          if (count($arraid) > 0)
          {
            if ($searchBy != '' || $searchBy_regtype != '')
            {
              if ($searchBy != '' && $searchBy_regtype != '')
              {
                $this->db->where('regnumber', $searchBy);
                $this->db->where('registrationtype', $searchBy_regtype);
                $members = $this->master_model->getRecords("member_registration");
              }
              ///search by registration number
              else if ($searchBy != '')
              {
                $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
                $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
                $this->db->where('regnumber', $searchBy);
                $members = $this->master_model->getRecords("member_registration");
                //$row=$searchBy;
              }
              ///search by registration type
              else if ($searchBy_regtype != '')
              {
                $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
                $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
                $this->db->where('registrationtype', $searchBy_regtype);
                $members = $this->master_model->getRecords("member_registration");
              }
              if (count($members) > 0)
              {
                foreach ($members as $row)
                {
                  $members_arr[][] = $row;
                }
              }
            }
            else
            {
              $this->db->where('member_registration.registrationtype', $type);
              //default allocation list for 100 member
              foreach ($arraid as $row)
              {

                $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
                $this->db->where('member_registration.isactive', '1');
                $members   = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'field_count' => 0, 'kyc_state' => 1), 'member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,mem_associate_inst', array('kyc_id' => 'ASC'), '', 1);//,employee_proof,mem_declaration added by pooja mane 2024-08-13

                $members_arr[] = $members;
              }
            }
          }

          $data['result'] = call_user_func_array('array_merge', $members_arr);
          //$data['result']=$result;

          //$data['reg_no'] = $members[0]['regnumber'];
          //$id=$data['reg_no'];
        }
        $total_row = 100;
        $url = base_url() . "admin/kyc/Approver/approver_edited_list/";
        $config = pagination_init($url, $total_row, $per_page, 2);
        $this->pagination->initialize($config);
        $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
        $str_links = $this->pagination->create_links();
        //var_dump($str_links);
        $data["links"] = $str_links;

        if (($start + $per_page) > $total_row)
          $end_of_total = $total_row;
        else
          $end_of_total = $start + $per_page;

        if ($total_row)
          $data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
        else
          $data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

        $data['index'] = $start + 1;

        $emptylistmsg = ' No records available...!!<br /><a href=' . base_url() . 'admin/kyc/Approver/next_edited_allocation_type/>Back</a>';

        /* Start Code To Get Recent Allotted Member Total Count */
        $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
        if (!empty($pagination_total_count))
        {
          foreach ($pagination_total_count[0] as $k => $value)
          {
            if ($k == "pagination_total_count")
            {
              $data['totalRecCount'] = $value;
            }
            if ($k == "original_allotted_member_id")
            {
              $data['original_allotted_member_id'] = $value;
            }
          }
        }
        /* Close Code To Get Recent Allotted Member Total Count */

        $data['emptylistmsg']  = $emptylistmsg;

        $this->db->distinct('registrationtype');
        $data['mem_type'] = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('registrationtype' => 'ASC'));
        //print_r($data['result']);die;
        $this->load->view('admin/kyc/Approver/approver_edited_list', $data);
      }
      else
      {

        redirect(base_url() . 'admin/kyc/Approver/approver_edited_list');
        //redirect(base_url() . 'admin/kyc/Approver/approver_edited_list/'.$type.'/'.$exam_code);
      }
    }
  }

  public function approver_edited_list()
  {

    $tilte = $type = '';
    $description = $emptylistmsg = '';
    $allocates_arr = $members_arr = $result = $array = array();
    $data['result'] = array();
    $regstr = $searchText = $searchBy = '';
    $searchBy_regtype = '';
    $today = date('Y-m-d H:i:s');
    $per_page = 100;
    $last = 99;
    $start = 0;
    $list_type = 'Edit';
    $exam_code = $_POST['select_exm_cd'];
    //print_r($_POST);die;

    $page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
    if ($this->input->post('regnumber') != '')
    {
      $searchBy = $this->input->post('regnumber');
    }
    if ($this->input->post('registrationtype') != '')
    {
      $searchBy_regtype = $this->input->post('registrationtype');
    }

    $registrationtype = '';
    $data['reg_no'] = ' ';

    if ($page != 0)
    {
      $start = $page - 1;
    }
    $allocates = array();
    //get  all  user loging today 
    if (isset($_POST['selectby']))
    {

      $type = $_POST['selectby'];
      $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_type' => 'approver', 'list_type' => 'Edit'), 'original_allotted_member_id');

      $allocatedmemberarr = array();
      if (count($kyc_data) > 0)
      {
        foreach ($kyc_data as $row)
        {
          $allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
        }
      }

      $member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and (kyc_state = 2 OR   kyc_state = 1 )AND field_count > 0");

      $recommendedmemberarr = array();
      if ($member_kyc->num_rows() > 0)
      {
        foreach ($member_kyc->result_array()  as $row)
        {
          $recommendedmemberarr[] = $row['regnumber'];
        }
      }

      $data_array = array();
      if (count($allocatedmemberarr) > 0)
      {
        // get the column data in a single array
        $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
      }

      // merge allocated member array with recommended members array
      $data_array = array_merge($data_array, $recommendedmemberarr);

      if (count($data_array) > 0)
      {
        $this->db->where_not_in('member_kyc.regnumber', array_map('stripslashes', $data_array));
      }

      // Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
      if ($type == 'NM')
      {
        $this->db->select('exam_code');
        $this->db->join('member_exam', 'member_exam.regnumber = member_kyc.regnumber');
        $this->db->where('member_exam.exam_code', $exam_code);
        $this->db->where('member_exam.pay_status', '1');
      }
      // Added exam code condition End Pooja Mane 2023-10-13

      $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
      $this->db->where('member_registration.kyc_status', '0');
      $this->db->where('member_registration.kyc_edit', 1);
      $this->db->where('member_kyc.kyc_status', '0');
      $this->db->where('member_registration.isactive', '1');
      $this->db->where('member_registration.isdeleted', '0');
      $this->db->where('member_registration.registrationtype', $type);
      $this->db->where_in('kyc_state', array(1));//2
      $this->db->group_by('member_kyc.regnumber');

      $r_list = $this->master_model->getRecords("member_kyc", array('field_count' => 0), 'member_kyc.regnumber,kyc_id,namesub,dateofbirth,associatedinstitute,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
				mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,isactive,mem_associate_inst,field_count', array('kyc_id' => 'ASC'), $start, $per_page);//,employee_proof,mem_declaration added by pooja mane 2024-08-13

      // echo count($r_list).$this->db->last_query();die;

      $today = date("Y-m-d H:i:s");
      $row_count = $this->master_model->getRecordCount("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit'));

      //echo $this->db->last_query();exit;
      if ($row_count == 0)
      {
        $regstr = '';
        foreach ($r_list  as $row)
        {
          $allocates_arr[] = $row['regnumber'];
        }
        if (count($allocates_arr) > 0)
        {
          $regstr = implode(',', $allocates_arr);
        }
        //print_r($regstr);exit;
        if ($regstr != '')
        {

          $insert_data = array(
            'user_type'      => $this->session->userdata('role'),
            'user_id'        => $this->session->userdata('kyc_id'),
            'allotted_member_id'  => $regstr,
            'original_allotted_member_id'  => $regstr,
            'allocated_count'     => count($allocates_arr),
            'allocated_list_count'     => '1',
            'date'                  => $today,
            'list_type'             => 'Edit',
            'pagination_total_count ' => count($allocates_arr)
          );
          $this->master_model->insertRecord('admin_kyc_users', $insert_data);
          //log activity 
          $tilte = 'Approver  KYC  member list allocation';
          $description = 'Approver has allocated ' . count($allocates_arr) . ' member';
          $user_id = $this->session->userdata('kyc_id');
          $this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);
        }
      }
    }
    $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''));
    //echo $this->db->last_query();exit;
    if (count($allocated_member_list) > 0)
    {
      $data['count'] = $allocated_member_list[0]['allocated_count'];
    }
    else
    {
      $data['count'] = 0;
    }

    if (count($allocated_member_list) > 0)
    {
      $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      //print_r($arraid);die;
      //$data['result'] = $members;
      //$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
      if (count($arraid) > 0)
      {
        if ($searchBy != '' || $searchBy_regtype != '')
        {
          if ($searchBy != '' && $searchBy_regtype != '')
          {
            $this->db->where('regnumber', $searchBy);
            $this->db->where('registrationtype', $searchBy_regtype);
            $members = $this->master_model->getRecords("member_registration");
          }
          ///search by registration number
          else if ($searchBy != '')
          {
            $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
            $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
            $this->db->where('regnumber', $searchBy);
            $members = $this->master_model->getRecords("member_registration");
            //$row=$searchBy;
          }
          ///search by registration type
          else if ($searchBy_regtype != '')
          {
            $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
            $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
            $this->db->where('registrationtype', $searchBy_regtype);
            $members = $this->master_model->getRecords("member_registration");
          }
          if (count($members) > 0)
          {
            foreach ($members as $row)
            {
              $members_arr[][] = $row;
            }
          }
        }
        else
        {

          //default allocation list for 100 member
          foreach ($arraid as $row)
          {

            $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
            $this->db->where('member_registration.isactive', '1');
            $this->db->where('member_registration.isdeleted', '0');
            $this->db->where('member_kyc.kyc_status', '0');
            $this->db->where('member_registration.kyc_status', '0');
            $this->db->where('member_registration.kyc_edit', '1');
            $this->db->where('member_registration.registrationtype', $type);
            $members = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'member_kyc.field_count' => '0', 'kyc_state' => 1), '', array('kyc_id' => 'ASC'), '0', '1');
            $members_arr[]  = $members;
          }
        }
        //Member KYC Filter


      }

      $data['result'] = call_user_func_array('array_merge', $members_arr);
      //print_r($members_arr);die;
      //exit;	


    }
    $total_row = 100;
    $url = base_url() . "admin/kyc/Approver/approver_edited_list/";
    $config = pagination_init($url, $total_row, $per_page, 2);
    $this->pagination->initialize($config);
    $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
    $str_links = $this->pagination->create_links();
    //var_dump($str_links);
    $data["links"] = $str_links;

    if (($start + $per_page) > $total_row)
      $end_of_total = $total_row;
    else
      $end_of_total = $start + $per_page;

    if ($total_row)
      $data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
    else
      $data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

    $data['index'] = $start + 1;

    $emptylistmsg = ' No records available...!!<br />
		<a href=' . base_url() . 'admin/kyc/Approver/edited_allocation_type/>Back</a>';

    /* Start Code To Get Recent Allotted Member Total Count */
    $pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
    if (!empty($pagination_total_count))
    {
      foreach ($pagination_total_count[0] as $k => $value)
      {
        if ($k == "pagination_total_count")
        {
          $data['totalRecCount'] = $value;
        }
        if ($k == "original_allotted_member_id")
        {
          $data['original_allotted_member_id'] = $value;
        }
      }
    }
    /* Close Code To Get Recent Allotted Member Total Count */


    $data['emptylistmsg']  = $emptylistmsg;
    redirect(base_url() . 'admin/kyc/Approver/edited_allocation_type');
    $this->db->distinct('registrationtype');
    $data['mem_type'] = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('registrationtype' => 'ASC'));
    //print_r($data);die;
    $this->load->view('admin/kyc/Approver/approver_edited_list', $data);
  }

  /*
	- SAGAR WALZADE : Code start here
	- function use : Function to approve member kyc or recomend him back to recommender
	- Changes : declaration field added, previous function renamed into "approver_edited_member_old"
	*/
  public function approver_edited_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
  {

    if ($regnumber)
    {
      $oldfilepath = $file_path = $photo_file = '';
      $state = $next_id = $success = $error = $description = '';
      $data['result'] = $name = $update_data = $old_user_data = $member_kyc_lastest_record = $sql = array();
      $new_arrayid = $noarray = array();
      $today = $date = date('Y-m-d H:i:s');
      $registrationtype = '';
      $data['reg_no'] = ' ';
      $field_count = 0;
      $fedai_array = array(1009);//Added by pooja mane 2024-08-14

      // recommendation submit

      if (isset($_POST['btnSubmitRecmd']))
      {
        $select = 'regnumber,registrationtype,email,createdon,excode';
        $data = $this->master_model->getRecords('member_registration', array(
          'regnumber' => $regnumber,
          'isactive' => '1',
          'kyc_status' => '0'
        ), $select);
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }

        $regnumber = $data[0]['regnumber'];

        // optional
        // echo "You chose the following color(s): <br />";

        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {

            // echo $cbox."<br />";

            $check_arr[] = $cbox;
          }
        }
        //print_r($check_arr);die;
        $msg = 'Edit your profile as :-';
        if (count($check_arr) > 0)
        {
          if (in_array('name_checkbox', $check_arr))
          {
            $name_checkbox = '1';
          }
          else
          {
            $name_checkbox = '0';
            $field_count++;
            $update_data[] = 'Name';
            $msg .= 'Name,';
          }

          if (in_array('dob_checkbox', $check_arr))
          {
            $dob_checkbox = '1';
          }
          else
          {
            $dob_checkbox = '0';
            $field_count++;
            $update_data[] .= 'DOB';
            $msg .= 'Date of Birth ,';
          }

          if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM')
          {
            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
            }
          }
          elseif ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A')
          {
            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
              $field_count++;
              $update_data[] .= 'Employer';
              $msg .= 'Employer,';
            }
          }

          if (in_array('photo_checkbox', $check_arr))
          {
            $photo_checkbox = '1';
          }
          else
          {
            $photo_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Photo';
            $msg .= 'Photo,';
          }

          if (in_array('sign_checkbox', $check_arr))
          {
            $sign_checkbox = '1';
          }
          else
          {
            $sign_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Sign';
            $msg .= 'Sign,';
          }

          if (in_array('idprf_checkbox', $check_arr))
          {
            $idprf_checkbox = '1';
          }
          else
          {
            $idprf_checkbox = '0';
            $field_count++;
            $update_data[] .= 'Id-proof';
            $msg .= 'Id-proof';
          }

          if ($data[0]['registrationtype'] == 'O')
          {
            if ($data[0]['createdon'] >= '2022-04-01')
            {
              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                $field_count++;
                $update_data[] .= 'Declaration';
                $msg .= 'Declaration';
              }
            }
            else
            {
              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                // no field_count is required here (declaration optional for old members)
              }
            }
          }
          else
          {
            $declaration_checkbox = '0';
          }

          // EMP ID/DECLARATION CHECK FOR NM FEDAI CODE ADDED BY POOJA MANE 2024-08-14
          if ($data[0]['registrationtype'] == 'NM' && in_array($data[0]['excode'], $fedai_array)) {
            if (in_array('empidprf_checkbox', $check_arr)) {
              $empidprf_checkbox = '1';
            } else {
              $empidprf_checkbox = '0';
              $field_count++;
              $update_data[] = 'Employment-proof';
              $msg .= 'Employment-proof';
            }
          }
          
          if ($data[0]['registrationtype'] == 'NM' && in_array($data[0]['excode'], $fedai_array)) {
            if (in_array('declaration_checkbox', $check_arr)) {
              $declaration_checkbox = '1';
            } else {
              $declaration_checkbox = '0';
              $field_count++;
              $update_data[] = 'Declaration';
              $msg .= 'Declaration';
            }
          }
          // EMP ID/DECLARATION CHECK FOR NM FEDAI CODE ADDED BY POOJA MANE 2024-08-14

          
        }
        else
        {
          $name_checkbox = '0';
          $msg .= 'Name,';
          $field_count++;
          $update_data[] .= 'Name';
          $dob_checkbox = '0';
          $msg .= 'Date of Birth ,';
          $field_count++;
          $update_data[] .= 'DOB';
          $emp_checkbox = '1';
          $msg .= 'Employer,';
          $field_count++;
          $update_data[] .= 'Employer';
          $photo_checkbox = '0';
          $msg .= 'Photo,';
          $field_count++;
          $update_data[] .= 'Photo';
          $sign_checkbox = '0';
          $msg .= 'Sign,';
          $field_count++;
          $update_data[] .= 'Sign';
          $idprf_checkbox = '0';
          $msg .= 'Id-proof,';
          $field_count++;
          $update_data[] .= 'Id-proof';

          if ($data[0]['registrationtype'] == 'O')
          {
            if ($data[0]['createdon'] >= '2022-04-01')
            {
              $declaration_checkbox = '0';
              $msg .= 'Declaration';
              $field_count++;
              $update_data[] .= 'Declaration';
            }
            else
            {
              $declaration_checkbox = '0';
              // no field_count is required here (declaration optional for old members)
            }
          }
          else
          {
            $declaration_checkbox = '0';
          }

          // EMP ID/DECLARATION CHECK FOR NM FEDAI CODE ADDED BY POOJA MANE 2024-08-14
          if ($data[0]['registrationtype'] == 'NM') 
          {
            if (in_array($data[0]['excode'],$fedai_array))
            {
              $declaration_checkbox = '0';
              $msg .= 'Declaration';
              $field_count++;
              $update_data[] = 'Declaration';

              $empidprf_checkbox = '0';
              $msg .= 'Employment-proof,';
              $field_count++;
              $update_data[] = 'Employment-proof';

            } else 
            {
              $declaration_checkbox = '0';
              $empidprf_checkbox = '0';
            }
          }
          // EMP ID/DECLARATION CHECK FOR NM FEDAI CODE ADDED BY POOJA MANE 2024-08-14
        }
        // print_r($check_arr);die;
        $email = $data[0]['email'];
        if ($data[0]['registrationtype'] == 'O')
        {
          // Declaration mandatory for those users who are registered from 1 april 2022 
          // (its a date of declaration feature upload date on live)
          if ($data[0]['createdon'] >= '2022-04-01')
          {
            if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1' && $declaration_checkbox == '1')
            {
              // $error='Please  unchecked atleast one checkbox!!';
              $this->session->set_flashdata('error', 'Please uncheck atleast one checkbox!!');
            }
            else
            {
              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_user_data = $this->master_model->getRecords('member_registration', array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $insert_data = array(
                'regnumber' => $data[0]['regnumber'],
                'mem_type' => $data[0]['registrationtype'],
                'mem_name' => $name_checkbox,
                // 'email_address'      => $data[0]['email'],
                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => $field_count,
                'old_data' => serialize($old_user_data),
                'kyc_status' => '0',
                'kyc_state' => '1',
                'recommended_by' => $this->session->userdata('kyc_id'),
                'user_type' => $this->session->userdata('role'),
                'recommended_date' => $today,
                'record_source' => 'Edit'
              );

              // insert the record and get latest  kyc_id
              $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );


              // email send on recommend...
              // email to user

              /*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'approver_recommend_email'));
              $final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);
    
              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);
    
              $userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber),'reg_no');
              $info_arr=array(
              'to'=> "kyciibf@gmail.com",
              'from'=> $emailerstr[0]['from'],
              'subject'=>$emailerstr[0]['subject'],
              'message'=>$final_str
              );*/
              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

                // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
                // $success='KYC  recommend for the candidate & Email sent successfully !!';
                // log activity
                // get recommended fields data from member registration -

                $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
                $old_data = $this->master_model->getRecords("member_registration", array(
                  'regnumber' => $regnumber,
                  'isactive' => '1'
                ), $select);
                $log_desc['old_data'] = $old_data;
                $log_desc['inserted_data'] = $insert_data;
                $description = serialize($log_desc);
                $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

                // email log

                $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

                // make recommended fields empty  -

                if (in_array('Name', $update_data))
                {
                  /*$updatedata=array(
                  'namesub'=>'',
                  'firstname'=>'',
                  'middlename'=>'',
                  'lastname'=>''
                  );*/
                  $updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                // if (in_array('DOB', $update_data))
                // {
                //   $updatedata['dateofbirth'] = '0000-00-00';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }

                // if (in_array('Employer', $update_data))
                // {
                //   $updatedata['associatedinstitute'] = '';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }
                // -------check for  photo -----------#

                if (in_array('Photo', $update_data))
                {
                  $updatedata['scannedphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'p');
                  $noarray = explode('/p_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_p_' . $noarray[1];

                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                // -------end check for  photo -----------#

                // ------- check for  signature-----------#

                if (in_array('Sign', $update_data))
                {

                  $updatedata['scannedsignaturephoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 's');
                  $noarray = explode('/s_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_s_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                      //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }

                // -------End check for  photo -----------#


                // ------- check for  idproof-----------#

                if (in_array('Id-proof', $update_data))
                {

                  $updatedata['idproofphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'pr');
                  $noarray = explode('/pr_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_pr_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                  // -------End  check for id proof -----------#


                }

                // ------- check for declaration -----------#
                if (in_array('Declaration', $update_data))
                {
                  $updatedata['declaration'] = '';
                  $oldfilepath = get_img_name($regnumber, 'declaration');
                  $noarray = explode('/declaration_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath;
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_declaration_' . $noarray[1];
                    $description .= ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended declaration rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member declaration  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  }
                }
                // -------End  check for declaration -----------#

                if (!empty($updatedata))
                {
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                $member = $this->master_model->getRecords("admin_kyc_users", array(
                  'DATE(date)' => date('Y-m-d'),
                  'list_type' => 'Edit',
                  'user_id' => $this->session->userdata('kyc_id')
                ));

                $arrayid = explode(',', $member[0]['allotted_member_id']);
                $index = array_search($regnumber, $arrayid, true);

                // get next record

                $currentid = $index;
                $nextid = $currentid + 1;
                if (array_key_exists($nextid, $arrayid))
                {
                  $next_id = $arrayid[$nextid];
                }
                else
                {
                  $next_id = $arrayid[0];
                }

                // end of next record
                // unset the  current id index

                unset($arrayid[$index]);
                if (count($arrayid) > 0)
                {
                  foreach ($arrayid as $row)
                  {
                    $new_arrayid[] = $row;
                  }
                }

                if (count($new_arrayid) > 0)
                {
                  $regstr = implode(',', $new_arrayid);
                }
                else
                {
                  $regstr = '';
                  $next_id = '';
                }

                $update_data = array(
                  'allotted_member_id' => $regstr
                );
                $this->db->where('DATE(date)', date('Y-m-d'));
                $this->db->where('list_type', 'Edit');
                $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                /* Start Code To Showing Count On Member List*/
                if ($next_id == '')
                {
                  $next_id = 0;
                }

                // $totalRecCount=$this->get_App_allocation_type_cnt();

                if ($srno > $totalRecCount)
                {

                  // $srno=$totalRecCount;

                  $srno = 1;
                }
                else
                {
                  $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                  $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                  $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                  $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                  $memberNo = $next_id;
                  $updated_list_index = array_search($memberNo, $reversedArr_list);
                  $srno = $updated_list_index;
                }

                redirect(base_url() . 'admin/kyc/Approver/approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
                /* Close Code To Showing Count On Member List*/
              }
            }
          }
          else
          {
            if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1')
            {
              // $error='Please  unchecked atleast one checkbox!!';
              $this->session->set_flashdata('error', 'Please uncheck atleast one checkbox!! (except declaration - its optional for this user)');
            }
            else
            {
              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_user_data = $this->master_model->getRecords('member_registration', array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $insert_data = array(
                'regnumber' => $data[0]['regnumber'],
                'mem_type' => $data[0]['registrationtype'],
                'mem_name' => $name_checkbox,
                // 'email_address'      => $data[0]['email'],
                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => $field_count,
                'old_data' => serialize($old_user_data),
                'kyc_status' => '0',
                'kyc_state' => '1',
                'recommended_by' => $this->session->userdata('kyc_id'),
                'user_type' => $this->session->userdata('role'),
                'recommended_date' => $today,
                'record_source' => 'Edit'
              );

              // insert the record and get latest  kyc_id
              $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );


              // email send on recommend...
              // email to user

              /*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'approver_recommend_email'));
              $final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);
    
              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);
    
              $userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber),'reg_no');
              $info_arr=array(
              'to'=> "kyciibf@gmail.com",
              'from'=> $emailerstr[0]['from'],
              'subject'=>$emailerstr[0]['subject'],
              'message'=>$final_str
              );*/
              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

                // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
                // $success='KYC  recommend for the candidate & Email sent successfully !!';
                // log activity
                // get recommended fields data from member registration -

                $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
                $old_data = $this->master_model->getRecords("member_registration", array(
                  'regnumber' => $regnumber,
                  'isactive' => '1'
                ), $select);
                $log_desc['old_data'] = $old_data;
                $log_desc['inserted_data'] = $insert_data;
                $description = serialize($log_desc);
                $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

                // email log

                $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

                // make recommended fields empty  -

                if (in_array('Name', $update_data))
                {
                  /*$updatedata=array(
                  'namesub'=>'',
                  'firstname'=>'',
                  'middlename'=>'',
                  'lastname'=>''
                  );*/
                  $updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                // if (in_array('DOB', $update_data))
                // {
                //   $updatedata['dateofbirth'] = '0000-00-00';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }

                // if (in_array('Employer', $update_data))
                // {
                //   $updatedata['associatedinstitute'] = '';

                //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

                // }
                // -------check for  photo -----------#

                if (in_array('Photo', $update_data))
                {
                  $updatedata['scannedphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'p');
                  $noarray = explode('/p_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_p_' . $noarray[1];

                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                // -------end check for  photo -----------#

                // ------- check for  signature-----------#

                if (in_array('Sign', $update_data))
                {

                  $updatedata['scannedsignaturephoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 's');
                  $noarray = explode('/s_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_s_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                      //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }

                // -------End check for  photo -----------#


                // ------- check for  idproof-----------#

                if (in_array('Id-proof', $update_data))
                {

                  $updatedata['idproofphoto'] = '';
                  $oldfilepath = get_img_name($regnumber, 'pr');
                  $noarray = explode('/pr_', $oldfilepath);
                  $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                  if (isset($noarray[1]))
                  {
                    $file_path = implode('/', explode('/', $oldfilepath, -1));
                    $photo_file = 'k_pr_' . $noarray[1];
                    if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                    {
                      $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                    }
                    else
                    {
                      $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                      //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                    }
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                  // -------End  check for id proof -----------#


                }

                // ------- check for declaration (not required for registered user before 1-4-2022)-----------#
                // if (in_array('Declaration', $update_data)) {
                //  $updatedata['declaration'] = '';
                //  $oldfilepath = get_img_name($regnumber, 'declaration');
                //  $noarray = explode('/declaration_', $oldfilepath);
                //  $description = 'oldpath:' . $oldfilepath;
                //  if (isset($noarray[1])) {
                //    $file_path = implode('/', explode('/', $oldfilepath, -1));
                //    $photo_file = 'k_declaration_' . $noarray[1];
                //    $description .= ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                //    if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
                //      $this->KYC_Log_model->create_log('Recommended declaration rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                //    } else {
                //      $this->KYC_Log_model->create_log('fail to delete declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                //    }
                //  } else {
                //    $this->KYC_Log_model->create_log('member declaration  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                //  }
                // }
                // -------End  check for declaration -----------#

                if (!empty($updatedata))
                {
                  $this->db->where('isactive', '1');
                  $this->master_model->updateRecord('member_registration', $updatedata, array(
                    'regnumber' => $regnumber
                  ));
                }

                $member = $this->master_model->getRecords("admin_kyc_users", array(
                  'DATE(date)' => date('Y-m-d'),
                  'list_type' => 'Edit',
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                $arrayid = explode(',', $member[0]['allotted_member_id']);
                $index = array_search($regnumber, $arrayid, true);

                // get next record

                $currentid = $index;
                $nextid = $currentid + 1;
                if (array_key_exists($nextid, $arrayid))
                {
                  $next_id = $arrayid[$nextid];
                }
                else
                {
                  $next_id = $arrayid[0];
                }

                // end of next record
                // unset the  current id index

                unset($arrayid[$index]);
                if (count($arrayid) > 0)
                {
                  foreach ($arrayid as $row)
                  {
                    $new_arrayid[] = $row;
                  }
                }

                if (count($new_arrayid) > 0)
                {
                  $regstr = implode(',', $new_arrayid);
                }
                else
                {
                  $regstr = '';
                  $next_id = '';
                }

                $update_data = array(
                  'allotted_member_id' => $regstr
                );
                $this->db->where('DATE(date)', date('Y-m-d'));
                $this->db->where('list_type', 'Edit');
                $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                  'user_id' => $this->session->userdata('kyc_id')
                ));
                /* Start Code To Showing Count On Member List*/
                if ($next_id == '')
                {
                  $next_id = 0;
                }

                // $totalRecCount=$this->get_App_allocation_type_cnt();

                if ($srno > $totalRecCount)
                {

                  // $srno=$totalRecCount;

                  $srno = 1;
                }
                else
                {
                  $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                  $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                  $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                  $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                  $memberNo = $next_id;
                  $updated_list_index = array_search($memberNo, $reversedArr_list);
                  $srno = $updated_list_index;
                }

                redirect(base_url() . 'admin/kyc/Approver/approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
                /* Close Code To Showing Count On Member List*/
              }
            }
          } // ($data[0]['createdon'] >= '2022-04-01') (else closing)
        }
        else
        {
          if($data[0]['registrationtype'] == 'NM' && in_array($data[0]['excode'], $fedai_array) && $name_checkbox == '1' && $dob_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1' && $empidprf_checkbox == '1' && $declaration_checkbox == '1')
          { //echo 'Please  uncheck atleast one checkbox';die;
            $this->session->set_flashdata('error', 'Please  uncheck atleast one checkbox!!');
          }
          elseif ((!in_array($data[0]['excode'], $fedai_array)) && $name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1')
          {
              // $error='Please  unchecked atleast one checkbox!!';
              $this->session->set_flashdata('error', 'Please  uncheck atleast one checkbox!!');
          }
          else
          {
            
            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
            $old_user_data = $this->master_model->getRecords('member_registration', array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));
            $insert_data = array(
              'regnumber' => $data[0]['regnumber'],
              'mem_type' => $data[0]['registrationtype'],
              'mem_name' => $name_checkbox,

              // 'email_address'      => $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => $emp_checkbox,
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'field_count' => $field_count,
              'old_data' => serialize($old_user_data),
              'kyc_status' => '0',
              'kyc_state' => '1',
              'recommended_by' => $this->session->userdata('kyc_id'),
              'user_type' => $this->session->userdata('role'),
              'recommended_date' => $today,
              'record_source' => 'Edit'
            );

            // insert the record and get latest  kyc_id

            $last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);
            if ($data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A')
            {
              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_O'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                // 'to'=> "kyciibf@gmail.com",

                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );
            }
            elseif ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM')
            {
              include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

              $key = $this->config->item('pass_key');
              $aes = new CryptAES();
              $aes->set_key(base64_decode($key));
              $aes->require_pkcs5();
              $userpass = $aes->decrypt($userdata[0]['usrpassword']);
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'recommendation_email_NM'
              ));
              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
              $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
              $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
              $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //'to'=> "kyciibf@gmail.com",

                'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );
            }


            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

              // $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
              // $success='KYC  recommend for the candidate & Email sent successfully !!';
              // log activity
              // get recommended fields data from member registration -

              $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
              $old_data = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ), $select);
              $log_desc['old_data'] = $old_data;
              $log_desc['inserted_data'] = $insert_data;
              $description = serialize($log_desc);
              $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

              // email log

              $this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

              // make recommended fields empty  -

              if (in_array('Name', $update_data))
              {
                /*$updatedata=array(
                'namesub'=>'',
                'firstname'=>'',
                'middlename'=>'',
                'lastname'=>''
                );*/
                $updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
                $this->db->where('isactive', '1');
                $this->master_model->updateRecord('member_registration', $updatedata, array(
                  'regnumber' => $regnumber
                ));
              }

              // if (in_array('DOB', $update_data))
              // {
              //   $updatedata['dateofbirth'] = '0000-00-00';

              //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

              // }

              // if (in_array('Employer', $update_data))
              // {
              //   $updatedata['associatedinstitute'] = '';

              //   // $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

              // }
              // -------check for  photo -----------#

              if (in_array('Photo', $update_data))
              {
                $updatedata['scannedphoto'] = '';
                $oldfilepath = get_img_name($regnumber, 'p');
                $noarray = explode('/p_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_p_' . $noarray[1];

                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
                }
              }
              // -------end check for  photo -----------#

              // ------- check for  signature-----------#

              if (in_array('Sign', $update_data))
              {

                $updatedata['scannedsignaturephoto'] = '';
                $oldfilepath = get_img_name($regnumber, 's');
                $noarray = explode('/s_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_s_' . $noarray[1];
                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

                    //$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
                }
              }

              // -------End check for  photo -----------#


              // ------- check for  idproof-----------#

              if (in_array('Id-proof', $update_data))
              {

                $updatedata['idproofphoto'] = '';
                $oldfilepath = get_img_name($regnumber, 'pr');
                $noarray = explode('/pr_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_pr_' . $noarray[1];
                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  {
                    $this->KYC_Log_model->create_log('Recommended idproof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                    //$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  //  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
                }
              }// -------End  check for id proof -----------#

              // print_r($update_data);die;
              // --------check for employment proof added by pooja mane 2024-08-14---------#
              if (in_array('Employment-proof', $update_data))
              {
                $updatedata['empidproofphoto'] = '';
                $oldfilepath = get_img_name($regnumber, 'empr');
                $noarray = explode('/empr_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_empr_' . $noarray[1];
                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  { echo ' proof rename';
                    $this->KYC_Log_model->create_log('Recommended Employment proof rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete Employment proof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member Employment proof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                }
              }
              // --------check for employment proof end added by pooja mane 2024-08-14-----#
              
              // --------check for declaration form added by pooja mane 2024-08-14---------#
              if (in_array('Declaration', $update_data))
              {
                $updatedata['declaration'] = '';
                $oldfilepath = get_img_name($regnumber, 'declaration');

                $noarray = explode('/declaration_', $oldfilepath);
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (isset($noarray[1]))
                {
                  $file_path = implode('/', explode('/', $oldfilepath, -1));
                  $photo_file = 'k_declaration_' . $noarray[1];

                  if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                  { echo 'Declaration rename';
                    $this->KYC_Log_model->create_log('Recommended Declaration rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                  }
                  else
                  {
                    $this->KYC_Log_model->create_log('fail to delete Declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                  }
                }
                else
                {
                  $this->KYC_Log_model->create_log('member Declaration  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                }
              }
              // --------check for declaration form ends added by pooja mane 2024-08-14----#


              if (!empty($updatedata))
              {
                $this->db->where('isactive', '1');
                $this->master_model->updateRecord('member_registration', $updatedata, array(
                  'regnumber' => $regnumber
                ));
              }

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'Edit',
                'user_id' => $this->session->userdata('kyc_id')
              ));
              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);

              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }
              // echo'<pre>';echo $this->db->last_query();//die;
              // print_r($arrayid);
              // echo $next_id;die;
              // end of next record
              // unset the  current id index

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              // echo'<pre>';echo $this->db->last_query();//die;
              // print_r($new_arrayid);die;
              //echo $next_id;die;

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );
              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'Edit');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));

              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }

              // $totalRecCount=$this->get_App_allocation_type_cnt();

              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }
              // echo 'srno'.$srno;
              // echo '<br>totala'.$totalRecCount;
              // die;
              if ($srno)
              {
                redirect(base_url() . 'admin/kyc/Approver/approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              }
              else
              {
                // $emptylistmsg = ' No records available...!!<br /><a href=' . base_url() . 'admin/kyc/Approver/edited_allocation_type/>Back</a>';
                // redirect(base_url() . 'admin/kyc/Approver/approver_edited_list');
                $totalRecCount = 0;
                redirect(base_url() . 'admin/kyc/Approver/approver_edited_member/' . $regnumber . "/" . $srno . "/" . $totalRecCount);
              }
              /* Close Code To Showing Count On Member List*/
            }
          }
        } // ($data[0]['registrationtype'] == 'O') (else closing)
      }

      // kyc submit

      if (isset($_POST['btnSubmitkyc']))
      {
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }

        // $regnumber=$data[0]['regnumber'];
        // optional
        // echo "You chose the following color(s): <br />";

        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {

            // echo $cbox."<br />";

            $check_arr[] = $cbox;
          }
        }
        //print_r($check_arr);die;

        $regnumber = $this->input->post('regnumber');
        $this->db->where('regnumber', $regnumber);
        $this->db->where('isactive', '1');
        $member_regtype = $this->master_model->getRecords('member_registration', '', 'registrationtype,createdon,excode');

        // Kyc complet for DB and NM  member only 5 fileds are consider
        // Kyc complet for NM fedai member 7 fileds are consider
        if ($member_regtype[0]['registrationtype'] == 'NM' && in_array($member_regtype[0]['excode'],$fedai_array))
        {
          if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr) && in_array('empidprf_checkbox', $check_arr)&& in_array('declaration_checkbox', $check_arr))
          {
            // echo '********';die;
            $new_arrayid = $members = $old_user_data = array();
            $status = '0';
            $state = '1';
            $date = date('Y-m-d H:i:s');
            $declaration_checkbox = '0';

            // $this->db->where('recommended_date',$date);

            $this->db->where('regnumber', $regnumber);
            $member_kyc_details = $this->master_model->getRecords('member_kyc');
            if (isset($_POST['cbox']))
            {
              $name = $this->input->post('cbox');
            }

            // $regnumber=$data[0]['regnumber'];
            // optional
            // echo "You chose the following color(s): <br />";

            $check_arr = array();
            if (count($name) > 0)
            {
              foreach ($name as $cbox)
              {

                // echo $cbox."<br />";

                $check_arr[] = $cbox;
              }
            }

            $msg = 'Edit your profile as :-';
            if (in_array('name_checkbox', $check_arr))
            {
              $name_checkbox = '1';
            }
            else
            {
              $name_checkbox = '0';
              $msg .= 'Name,';
            }

            if (in_array('dob_checkbox', $check_arr))
            {
              $dob_checkbox = '1';
            }
            else
            {
              $dob_checkbox = '0';
              $msg .= 'Date of Birth ,';
            }

            if (in_array('photo_checkbox', $check_arr))
            {
              $photo_checkbox = '1';
            }
            else
            {
              $photo_checkbox = '0';
              $msg .= 'Photo,';
            }

            if (in_array('sign_checkbox', $check_arr))
            {
              $sign_checkbox = '1';
            }
            else
            {
              $sign_checkbox = '0';
              $msg .= 'Sign,';
            }

            if (in_array('idprf_checkbox', $check_arr))
            {
              $idprf_checkbox = '1';
            }
            else
            {
              $idprf_checkbox = '0';
              $msg .= 'Employment-proof';
            }

            if (in_array('empidprf_checkbox', $check_arr))
            {
              $empidprf_checkbox = '1';
            }
            else
            {
              $empidprf_checkbox = '0';
              $msg .= 'Employment-proof';
            }

            if (in_array('declaration_checkbox', $check_arr))
            {
              $declaration_checkbox = '1';
            }
            else
            {
              $declaration_checkbox = '0';
              $msg .= 'Declaration';
            }

            // get the old_data

            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
            $old_user_data = $this->master_model->getRecords('member_registration', array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));


            // $email=$data[0]['email'];

            $update_data = array(
              'mem_name' => $name_checkbox,

              // 'email_address'      => $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => '0',
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'old_data' => serialize($old_user_data),
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id'),
              'approved_date' => $today,

              // 'record_source'      =>'Edit'

            );

            $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
            $this->db->where($sql);
            $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '0'
            ), 'regnumber,kyc_state,kyc_id', array(
              'kyc_id' => 'DESC'
            ));


            $this->db->where('isactive', '1');
            $this->master_model->updateRecord('member_registration', array(
              'kyc_status' => '1'
            ), array(
              'regnumber' => $regnumber
            ));

            // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

            $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
            $this->master_model->updateRecord('member_kyc', $update_data, array(
              'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            ));
            /*reset the dowanload count*/
            $where1 = array(
              'member_number' => $regnumber
            );
            $this->master_model->updateRecord('member_idcard_cnt', array(
              'card_cnt' => '0'
            ), $where1);

            // email send on KYC complete  for DB & NM

            $last_insterid = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id')
            ), 'kyc_id', array(
              'kyc_id' => 'DESC'
            ), '0', '1');


            $nomsg = '';

            $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $msg = implode(',', $update_data);
            $emailerstr = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'KYC_completion_email_to_NM'
            ));

            // echo $emailerstr[0]['emailer_text'];exit;

            $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr = array(

              // 'to'=> "kyciibf@gmail.com",

              'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str
            );
            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)   & Email sent successfully !!');

              // $success='KYC Completed for the candidate & Email sent successfully !!';
              // log activity

              $regnumber = $regnumber;
              $user_id = $this->session->userdata('kyc_id');
              $tilte = 'Member KYC completed';
              $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
              $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid['kyc_id'], $regnumber, $description);

              // $this->session->set_flashdata('success','kyc completed Successfully  !!');
              // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
              // email log

              $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            }

            // rebulide the array

            $member = $this->master_model->getRecords("admin_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'list_type' => 'New',
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);

            // get next record

            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }

            // end of next record

            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }

            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }

            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'New');
            $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }

            // $totalRecCount=$this->get_App_allocation_type_cnt();

            if ($srno > $totalRecCount)
            {

              // $srno=$totalRecCount;

              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/approver_allocated_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
          else
          {

            ECHO  $error='Select all check box to complete the Kyc !!';DIE;

            $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

            // $error='Select all check box to complete the Kyc !!';
            // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

          }
        }
        //code added by pooja mane for fedai non members ends here 2024-08-14
        elseif ($member_regtype[0]['registrationtype'] == 'NM' || $member_regtype[0]['registrationtype'] == 'DB')
        {
          if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
          {

            $new_arrayid = $members = $old_user_data = array();
            $status = '0';
            $state = '1';
            $date = date('Y-m-d H:i:s');
            $declaration_checkbox = '0';

            // $this->db->where('recommended_date',$date);

            $this->db->where('regnumber', $regnumber);
            $member_kyc_details = $this->master_model->getRecords('member_kyc');
            if (isset($_POST['cbox']))
            {
              $name = $this->input->post('cbox');
            }

            // $regnumber=$data[0]['regnumber'];
            // optional
            // echo "You chose the following color(s): <br />";

            $check_arr = array();
            if (count($name) > 0)
            {
              foreach ($name as $cbox)
              {

                // echo $cbox."<br />";

                $check_arr[] = $cbox;
              }
            }

            $msg = 'Edit your profile as :-';
            if (in_array('name_checkbox', $check_arr))
            {
              $name_checkbox = '1';
            }
            else
            {
              $name_checkbox = '0';
              $msg .= 'Name,';
            }

            if (in_array('dob_checkbox', $check_arr))
            {
              $dob_checkbox = '1';
            }
            else
            {
              $dob_checkbox = '0';
              $msg .= 'Date of Birth ,';
            }

            if (in_array('photo_checkbox', $check_arr))
            {
              $photo_checkbox = '1';
            }
            else
            {
              $photo_checkbox = '0';
              $msg .= 'Photo,';
            }

            if (in_array('sign_checkbox', $check_arr))
            {
              $sign_checkbox = '1';
            }
            else
            {
              $sign_checkbox = '0';
              $msg .= 'Sign,';
            }

            if (in_array('idprf_checkbox', $check_arr))
            {
              $idprf_checkbox = '1';
            }
            else
            {
              $idprf_checkbox = '0';
              $msg .= 'Id-proof';
            }

            // get the old_data

            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode';
            $old_user_data = $this->master_model->getRecords('member_registration', array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));
            //echo $this->db->last_query();die;

            // $email=$data[0]['email'];

            $update_data = array(
              'mem_name' => $name_checkbox,

              // 'email_address'      => $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => '0',
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'old_data' => serialize($old_user_data),
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id'),
              'approved_date' => $today,

              // 'record_source'      =>'Edit'

            );
            //echo'<pre>';print_r($update_data);die;

            $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
            $this->db->where($sql);
            $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '0'
            ), 'regnumber,kyc_state,kyc_id', array(
              'kyc_id' => 'DESC'
            ));

            // print_r($member_kyc_lastest_record );exit;

            $this->db->where('isactive', '1');
            $this->master_model->updateRecord('member_registration', array(
              'kyc_status' => '1'
            ), array(
              'regnumber' => $regnumber
            ));

            // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

            $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
            $this->master_model->updateRecord('member_kyc', $update_data, array(
              'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            ));
            /*reset the dowanload count*/
            $where1 = array(
              'member_number' => $regnumber
            );
            $this->master_model->updateRecord('member_idcard_cnt', array(
              'card_cnt' => '0'
            ), $where1);

            // email send on KYC complete  for DB & NM

            $last_insterid = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id')
            ), 'kyc_id', array(
              'kyc_id' => 'DESC'
            ), '0', '1');

            // echo $this->db->last->query();exit;
            // print_r($last_insterid);exit;

            $nomsg = '';

            $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $msg = implode(',', $update_data);
            $emailerstr = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'KYC_completion_email_to_NM'
            ));

            // echo $emailerstr[0]['emailer_text'];exit;

            $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr = array(

              // 'to'=> "kyciibf@gmail.com",

              'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str
            );
            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)   & Email sent successfully !!');

              // $success='KYC Completed for the candidate & Email sent successfully !!';
              // log activity

              $regnumber = $regnumber;
              $user_id = $this->session->userdata('kyc_id');
              $tilte = 'Member KYC completed';
              $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
              $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid['kyc_id'], $regnumber, $description);

              // $this->session->set_flashdata('success','kyc completed Successfully  !!');
              // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
              // email log

              $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            }

            // rebulide the array

            $member = $this->master_model->getRecords("admin_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'list_type' => 'Edit',
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);

            // get next record

            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }

            // end of next record

            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }

            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }

            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'Edit');
            $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }

            // $totalRecCount=$this->get_App_allocation_type_cnt();

            if ($srno > $totalRecCount)
            {

              // $srno=$totalRecCount;

              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
          else
          {

            // $error='Select all check box to complete the Kyc !!';

            $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

            // $error='Select all check box to complete the Kyc !!';
            // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

          }
        } //Kyc complet forO,A,F  member only 5 fileds are consider
        elseif ($member_regtype[0]['registrationtype'] == 'A' || $member_regtype[0]['registrationtype'] == 'F')
        {
          if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
          {
            $new_arrayid = $members = array();
            $status = '0';
            $state = '1';
            $date = date("Y-m-d H:i:s");
            $declaration_checkbox = '0';

            // $this->db->where('recommended_date',$date);

            $this->db->where('regnumber', $regnumber);
            $member_kyc_details = $this->master_model->getRecords('member_kyc');
            if (isset($_POST['cbox']))
            {
              $name = $this->input->post('cbox');
            }

            //          $regnumber=$data[0]['regnumber'];
            // optional
            // echo "You chose the following color(s): <br />";

            $check_arr = array();
            if (count($name) > 0)
            {
              foreach ($name as $cbox)
              {

                // echo $cbox."<br />";

                $check_arr[] = $cbox;
              }
            }

            $msg = 'Edit your profile as :-';
            if (in_array('name_checkbox', $check_arr))
            {
              $name_checkbox = '1';
            }
            else
            {
              $name_checkbox = '0';
              $msg .= 'Name,';
            }

            if (in_array('dob_checkbox', $check_arr))
            {
              $dob_checkbox = '1';
            }
            else
            {
              $dob_checkbox = '0';
              $msg .= 'Date of Birth ,';
            }

            if (in_array('emp_checkbox', $check_arr))
            {
              $emp_checkbox = '1';
            }
            else
            {
              $emp_checkbox = '1';
              // $msg .= 'Associate institude ,';
            }

            if (in_array('photo_checkbox', $check_arr))
            {
              $photo_checkbox = '1';
            }
            else
            {
              $photo_checkbox = '0';
              $msg .= 'Photo,';
            }

            if (in_array('sign_checkbox', $check_arr))
            {
              $sign_checkbox = '1';
            }
            else
            {
              $sign_checkbox = '0';
              $msg .= 'Sign,';
            }

            if (in_array('idprf_checkbox', $check_arr))
            {
              $idprf_checkbox = '1';
            }
            else
            {
              $idprf_checkbox = '0';
              $msg .= 'Id-proof';
            }

            // $email=$data[0]['email'];

            $update_data = array(
              'mem_name' => $name_checkbox,

              // 'email_address'      => $data[0]['email'],

              'mem_dob' => $dob_checkbox,
              'mem_associate_inst' => $emp_checkbox,
              'mem_photo' => $photo_checkbox,
              'mem_sign' => $sign_checkbox,
              'mem_proof' => $idprf_checkbox,
              'employee_proof' => $empidprf_checkbox,
              'mem_declaration' => $declaration_checkbox,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id'),
              'approved_date' => $today,

              // 'record_source'      =>'Edit'

            );

            // query to update the latest record of the regnumber

            $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
            $this->db->where($sql);
            $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber
            ), 'regnumber,kyc_state,kyc_id', array(
              'kyc_id' => 'DESC'
            ));

            // print_r($member_kyc_lastest_record );exit;

            $this->db->where('isactive', '1');
            $this->master_model->updateRecord('member_registration', array(
              'kyc_status' => '1'
            ), array(
              'regnumber' => $regnumber
            ));

            // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

            $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
            $this->master_model->updateRecord('member_kyc', $update_data, array(
              'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            ));
            /*reset the dowanload count*/
            $where1 = array(
              'member_number' => $regnumber
            );
            $this->master_model->updateRecord('member_idcard_cnt', array(
              'card_cnt' => '0'
            ), $where1);
            $last_insterid = $this->master_model->getRecords("member_kyc", array(
              'regnumber' => $regnumber,
              'kyc_status' => '1',
              'kyc_state' => 3,
              'approved_by' => $this->session->userdata('kyc_id')
            ), 'kyc_id', array(
              'kyc_id' => 'DESC'
            ), '0', '1');

            // print_r($last_insterid[0]['kyc_id']);exit;

            $nomsg = '';
            $userdata = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ));
            $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $msg = implode(',', $update_data);
            $emailerstr = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'KYC_completion_email_to_O'
            ));

            // echo $emailerstr[0]['emailer_text'];exit;

            $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr = array(

              //  'to'=> "kyciibf@gmail.com",
              'to' => 'iibfdevp@esds.co.in,Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str
            );
            /*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'approver_KYC_complete_email'));
            $final_str = str_replace("#MSG#", "".$nomsg."",  $emailerstr[0]['emailer_text']);

            // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

            $info_arr=array(
            'to'=> "kyciibf@gmail.com",
            'from'=> $emailerstr[0]['from'],
            'subject'=>$emailerstr[0]['subject'],
            'message'=>$final_str
            );*/
            /*echo '<pre>';
            print_r($info_arr);
            exit;*/
            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

              // $success='KYC Completed for the candidate & Email sent successfully !!';
              // log activity

              $regnumber = $regnumber;
              $user_id = $this->session->userdata('kyc_id');
              $tilte = 'Member KYC completed';
              $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
              $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

              // $this->session->set_flashdata('success','kyc completed Successfully  !!');
              // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
              // email log

              $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            }

            // rebulide the array

            $member = $this->master_model->getRecords("admin_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'list_type' => 'Edit',
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);

            // get next record

            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }

            // end of next record

            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }

            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }

            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'Edit');
            $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }

            // $totalRecCount=$this->get_App_allocation_type_cnt();

            if ($srno > $totalRecCount)
            {

              // $srno=$totalRecCount;

              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
          else
          {
            $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

            // $error='Select all check-box to complete the Kyc !!';
            // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

          }
        }
        elseif ($member_regtype[0]['registrationtype'] == 'O')
        {
          // Declaration mandatory for those users who are registered from 1 april 2022 
          // (its a date of declaration feature upload date on live)
          if ($data[0]['createdon'] >= '2022-04-01')
          {
            if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr) && in_array('declaration_checkbox', $check_arr))
            {
              $new_arrayid = $members = array();
              $status = '0';
              $state = '1';
              $date = date("Y-m-d H:i:s");

              // $this->db->where('recommended_date',$date);

              $this->db->where('regnumber', $regnumber);
              $member_kyc_details = $this->master_model->getRecords('member_kyc');
              if (isset($_POST['cbox']))
              {
                $name = $this->input->post('cbox');
              }

              //          $regnumber=$data[0]['regnumber'];
              // optional
              // echo "You chose the following color(s): <br />";

              $check_arr = array();
              if (count($name) > 0)
              {
                foreach ($name as $cbox)
                {

                  // echo $cbox."<br />";

                  $check_arr[] = $cbox;
                }
              }

              $msg = 'Edit your profile as :-';
              if (in_array('name_checkbox', $check_arr))
              {
                $name_checkbox = '1';
              }
              else
              {
                $name_checkbox = '0';
                $msg .= 'Name,';
              }

              if (in_array('dob_checkbox', $check_arr))
              {
                $dob_checkbox = '1';
              }
              else
              {
                $dob_checkbox = '0';
                $msg .= 'Date of Birth ,';
              }

              if (in_array('emp_checkbox', $check_arr))
              {
                $emp_checkbox = '1';
              }
              else
              {
                $emp_checkbox = '1';
                // $msg .= 'Associate institude ,';
              }

              if (in_array('photo_checkbox', $check_arr))
              {
                $photo_checkbox = '1';
              }
              else
              {
                $photo_checkbox = '0';
                $msg .= 'Photo,';
              }

              if (in_array('sign_checkbox', $check_arr))
              {
                $sign_checkbox = '1';
              }
              else
              {
                $sign_checkbox = '0';
                $msg .= 'Sign,';
              }

              if (in_array('idprf_checkbox', $check_arr))
              {
                $idprf_checkbox = '1';
              }
              else
              {
                $idprf_checkbox = '0';
                $msg .= 'Id-proof';
              }

              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
                $msg .= 'Declaration';
              }

              // $email=$data[0]['email'];

              $update_data = array(
                'mem_name' => $name_checkbox,

                // 'email_address'      => $data[0]['email'],

                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id'),
                'approved_date' => $today,

                // 'record_source'      =>'Edit'

              );

              // query to update the latest record of the regnumber

              $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
              $this->db->where($sql);
              $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber
              ), 'regnumber,kyc_state,kyc_id', array(
                'kyc_id' => 'DESC'
              ));

              // print_r($member_kyc_lastest_record );exit;

              $this->db->where('isactive', '1');
              $this->master_model->updateRecord('member_registration', array(
                'kyc_status' => '1'
              ), array(
                'regnumber' => $regnumber
              ));

              // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

              $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
              $this->master_model->updateRecord('member_kyc', $update_data, array(
                'regnumber' => $member_kyc_lastest_record[0]['regnumber']
              ));
              /*reset the dowanload count*/
              $where1 = array(
                'member_number' => $regnumber
              );
              $this->master_model->updateRecord('member_idcard_cnt', array(
                'card_cnt' => '0'
              ), $where1);
              $last_insterid = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id')
              ), 'kyc_id', array(
                'kyc_id' => 'DESC'
              ), '0', '1');

              // print_r($last_insterid[0]['kyc_id']);exit;

              $nomsg = '';
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'KYC_completion_email_to_O'
              ));

              // echo $emailerstr[0]['emailer_text'];exit;

              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //  'to'=> "kyciibf@gmail.com",
                'to' => $userdata[0]['email'],
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );
              /*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'approver_KYC_complete_email'));
              $final_str = str_replace("#MSG#", "".$nomsg."",  $emailerstr[0]['emailer_text']);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr=array(
              'to'=> "kyciibf@gmail.com",
              'from'=> $emailerstr[0]['from'],
              'subject'=>$emailerstr[0]['subject'],
              'message'=>$final_str
              );*/
              /*echo '<pre>';
              print_r($info_arr);
              exit;*/
              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

                // $success='KYC Completed for the candidate & Email sent successfully !!';
                // log activity

                $regnumber = $regnumber;
                $user_id = $this->session->userdata('kyc_id');
                $tilte = 'Member KYC completed';
                $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
                $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

                // $this->session->set_flashdata('success','kyc completed Successfully  !!');
                // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
                // email log

                $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
              }

              // rebulide the array

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'Edit',
                'user_id' => $this->session->userdata('kyc_id')
              ));
              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);

              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }

              // end of next record

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );
              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'Edit');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));
              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }

              // $totalRecCount=$this->get_App_allocation_type_cnt();

              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }

              redirect(base_url() . 'admin/kyc/Approver/approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              /* Close Code To Showing Count On Member List*/
            }
            else
            {
              $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');

              // $error='Select all check-box to complete the Kyc !!';
              // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

            }
          }
          else
          {
            if (in_array('name_checkbox', $check_arr) && in_array('dob_checkbox', $check_arr) && in_array('photo_checkbox', $check_arr) && in_array('sign_checkbox', $check_arr) && in_array('idprf_checkbox', $check_arr))
            {
              // print_r($check_arr);DIE;
              $new_arrayid = $members = array();
              $status = '0';
              $state = '1';
              $date = date("Y-m-d H:i:s");

              // $this->db->where('recommended_date',$date);

              $this->db->where('regnumber', $regnumber);
              $member_kyc_details = $this->master_model->getRecords('member_kyc');
              if (isset($_POST['cbox']))
              {
                $name = $this->input->post('cbox');
              }

              //          $regnumber=$data[0]['regnumber'];
              // optional
              // echo "You chose the following color(s): <br />";

              $check_arr = array();
              if (count($name) > 0)
              {
                foreach ($name as $cbox)
                {
                  // echo $cbox."<br />";
                  $check_arr[] = $cbox;
                }
              }

              $msg = 'Edit your profile as :-';
              if (in_array('name_checkbox', $check_arr))
              {
                $name_checkbox = '1';
              }
              else
              {
                $name_checkbox = '0';
                $msg .= 'Name,';
              }

              if (in_array('dob_checkbox', $check_arr))
              {
                $dob_checkbox = '1';
              }
              else
              {
                $dob_checkbox = '0';
                $msg .= 'Date of Birth ,';
              }

              if (in_array('emp_checkbox', $check_arr))
              {
                $emp_checkbox = '1';
              }
              else
              {
                $emp_checkbox = '1';
                // $msg .= 'Associate institude ,';
              }

              if (in_array('photo_checkbox', $check_arr))
              {
                $photo_checkbox = '1';
              }
              else
              {
                $photo_checkbox = '0';
                $msg .= 'Photo,';
              }

              if (in_array('sign_checkbox', $check_arr))
              {
                $sign_checkbox = '1';
              }
              else
              {
                $sign_checkbox = '0';
                $msg .= 'Sign,';
              }

              if (in_array('idprf_checkbox', $check_arr))
              {
                $idprf_checkbox = '1';
              }
              else
              {
                $idprf_checkbox = '0';
                $msg .= 'Id-proof';
              }

              if (in_array('declaration_checkbox', $check_arr))
              {
                $declaration_checkbox = '1';
              }
              else
              {
                $declaration_checkbox = '0';
              }

              // $email=$data[0]['email'];

              $update_data = array(
                'mem_name' => $name_checkbox,

                // 'email_address'      => $data[0]['email'],

                'mem_dob' => $dob_checkbox,
                'mem_associate_inst' => $emp_checkbox,
                'mem_photo' => $photo_checkbox,
                'mem_sign' => $sign_checkbox,
                'mem_proof' => $idprf_checkbox,
                'employee_proof' => $empidprf_checkbox,
                'mem_declaration' => $declaration_checkbox,
                'field_count' => 0,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id'),
                'approved_date' => $today,

                // 'record_source'      =>'Edit'

              );

              // query to update the latest record of the regnumber

              $sql = 'kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
              $this->db->where($sql);
              $member_kyc_lastest_record = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber
              ), 'regnumber,kyc_state,kyc_id', array(
                'kyc_id' => 'DESC'
              ));

              // print_r($member_kyc_lastest_record );exit;

              $this->db->where('isactive', '1');
              $this->master_model->updateRecord('member_registration', array(
                'kyc_status' => '1'
              ), array(
                'regnumber' => $regnumber
              ));

              // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));

              $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
              $this->master_model->updateRecord('member_kyc', $update_data, array(
                'regnumber' => $member_kyc_lastest_record[0]['regnumber']
              ));
              /*reset the dowanload count*/
              $where1 = array(
                'member_number' => $regnumber
              );
              $this->master_model->updateRecord('member_idcard_cnt', array(
                'card_cnt' => '0'
              ), $where1);
              $last_insterid = $this->master_model->getRecords("member_kyc", array(
                'regnumber' => $regnumber,
                'kyc_status' => '1',
                'kyc_state' => 3,
                'approved_by' => $this->session->userdata('kyc_id')
              ), 'kyc_id', array(
                'kyc_id' => 'DESC'
              ), '0', '1');

              // print_r($last_insterid[0]['kyc_id']);exit;

              $nomsg = '';
              $userdata = $this->master_model->getRecords("member_registration", array(
                'regnumber' => $regnumber,
                'isactive' => '1'
              ));
              $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
              $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
              $msg = implode(',', $update_data);
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'KYC_completion_email_to_O'
              ));

              // echo $emailerstr[0]['emailer_text'];exit;

              $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
              $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr = array(

                //  'to'=> "kyciibf@gmail.com",
                'to' => $userdata[0]['email'],
                'from' => $emailerstr[0]['from'],
                'subject' => $emailerstr[0]['subject'],
                'message' => $final_str
              );
              /*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'approver_KYC_complete_email'));
              $final_str = str_replace("#MSG#", "".$nomsg."",  $emailerstr[0]['emailer_text']);

              // $final_str= str_replace("#password#", "".$decpass."",  $newstring);

              $info_arr=array(
              'to'=> "kyciibf@gmail.com",
              'from'=> $emailerstr[0]['from'],
              'subject'=>$emailerstr[0]['subject'],
              'message'=>$final_str
              );*/
              /*echo '<pre>';
              print_r($info_arr);
              exit;*/
              if ($this->Emailsending->mailsend($info_arr))
              {
                $this->session->set_flashdata('success', 'KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');

                // $success='KYC Completed for the candidate & Email sent successfully !!';
                // log activity

                $regnumber = $regnumber;
                $user_id = $this->session->userdata('kyc_id');
                $tilte = 'Member KYC completed';
                $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
                $this->KYC_Log_model->create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);

                // $this->session->set_flashdata('success','kyc completed Successfully  !!');
                // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
                // email log

                $this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
              }

              // rebulide the array

              $member = $this->master_model->getRecords("admin_kyc_users", array(
                'DATE(date)' => date('Y-m-d'),
                'list_type' => 'Edit',
                'user_id' => $this->session->userdata('kyc_id')
              ));
              $arrayid = explode(',', $member[0]['allotted_member_id']);
              $index = array_search($regnumber, $arrayid, true);

              // get next record

              $currentid = $index;
              $nextid = $currentid + 1;
              if (array_key_exists($nextid, $arrayid))
              {
                $next_id = $arrayid[$nextid];
              }
              else
              {
                $next_id = $arrayid[0];
              }

              // end of next record

              unset($arrayid[$index]);
              if (count($arrayid) > 0)
              {
                foreach ($arrayid as $row)
                {
                  $new_arrayid[] = $row;
                }
              }

              if (count($new_arrayid) > 0)
              {
                $regstr = implode(',', $new_arrayid);
              }
              else
              {
                $regstr = '';
                $next_id = '';
              }

              $update_data = array(
                'allotted_member_id' => $regstr
              );
              $this->db->where('DATE(date)', date('Y-m-d'));
              $this->db->where('list_type', 'Edit');
              $this->master_model->updateRecord('admin_kyc_users', $update_data, array(
                'user_id' => $this->session->userdata('kyc_id')
              ));
              /* Start Code To Showing Count On Member List*/
              if ($next_id == '')
              {
                $next_id = 0;
              }

              // $totalRecCount=$this->get_App_allocation_type_cnt();

              if ($srno > $totalRecCount)
              {

                // $srno=$totalRecCount;

                $srno = 1;
              }
              else
              {
                $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
                $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                $memberNo = $next_id;
                $updated_list_index = array_search($memberNo, $reversedArr_list);
                $srno = $updated_list_index;
              }

              redirect(base_url() . 'admin/kyc/Approver/approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
              /* Close Code To Showing Count On Member List*/
            }
            else
            {
              $this->session->set_flashdata('error', 'Select all check box to complete the Kyc (Declaration is optional for this user) !!');

              // $error='Select all check-box to complete the Kyc !!';
              // redirect(base_url().'admin/kyc/Approver/approver_allocated_member/'.$regnumber);

            }
          }
        }
      }

      if ($regnumber)
      {
        $select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode,registrationtype,createdon';
        $members = $this->master_model->getRecords("member_registration a", array(
          'regnumber' => $regnumber,
          'isactive' => '1'
        ), $select, "", '0', '1');

        // echo $this->db->last_query();exit;

        /*if(count($members))
        {
        $data['result'] = $members;
        $data['reg_no'] = $members[0]['regnumber'];
        $id=$data['reg_no'];
        }*/
      }

      // $this->db->where('field_count','0');

      $recommnended_members_data = $this->master_model->getRecords("member_kyc", array(
        'regnumber' => $regnumber
      ), '', array(
        'kyc_id' => 'DESC'
      ), '0', '1');

      // echo $this->db->last_query();exit;
      // $data['recomended_mem_data']=$recommnended_members_data;

      $data = array(
        'result' => $members,
        'next_id' => $next_id,
        'recomended_mem_data' => $recommnended_members_data,
        'error' => $error,
        'success' => $success
      );
      $data['srno'] = $srno;
      $data['totalRecCount'] = $totalRecCount;
      $this->load->view('admin/kyc/Approver/approver_edited_screen', $data);
    }
    else
    {
      $this->session->set_flashdata('success', $this->session->flashdata('success'));

      // $this->session->set_flashdata('error','Invalid record!!');

      redirect(base_url() . 'admin/kyc/Approver/approver_edited_list');
    }
  }
  /* SAGAR WALZADE : Code end here */
  
  //to get next recode on click of next button
  public function next_recode($regnumber)
  {
    if ($regnumber)
    {
      $ky_id = $this->session->userdata('kyc_id');
      $arrayid = array();
      $date = date("Y-m-d");
      $select = '*';
      $this->db->where('date', $date);
      $this->db->where('user_type', $this->session->userdata('role'));
      $this->db->where('user_id', $this->session->userdata('kyc_id'));
      $member = $this->master_model->getRecords("admin_kyc_users", "", $select);
      $arrayid = explode(',', $member[0]['allotted_member_id']);
      $currentid  =   array_search($regnumber, $arrayid, true);
      $nextid = $currentid + 1;
      if (array_key_exists($nextid, $arrayid))
      {
        $next_id = $arrayid[$nextid];
      }
      else
      {
        $next_id = $arrayid[0];
      }

      redirect(base_url() . 'admin/kyc/Approver/member/' . $next_id);
    }
    else
    {
      redirect(base_url() . 'admin/kyc/Approver/kyc_complete');
    }
  }

  //Recommended list to approver - by prafull
  public function recommneder_list()
  {

    $kycstatus = array();
    $data['result'] = array();
    $regstr = $searchText = $searchBy = '';
    $searchBy_regtype = '';
    $searchBy = $this->input->post('regnumber');
    $searchBy_regtype = $this->input->post('registrationtype');
    if ($searchBy != '')
    {
      $this->db->where('member_kyc.regnumber', $searchBy);
    }
    elseif ($searchBy_regtype != '')
    {
      $this->db->where('member_kyc.mem_type', $searchBy_regtype);
    }

    $this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
    $this->db->where('member_registration.isactive', '1');
    $r_list = $this->master_model->getRecords("member_kyc", array('recommended_by' => $this->session->userdata('kyc_id'), 'recommended_date' => $date), 'member_kyc.regnumber,kyc_id,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
		mem_dob,mem_sign,mem_proof,employee_proof,mem_declaration,mem_photo,mem_associate_inst,member_kyc.old_data,member_registration.dateofbirth,member_registration.associatedinstitute', array('kyc_id' => 'DESC'));//,employee_proof,mem_declaration added by pooja mane 2024-08-13
    //$r_list = $this->master_model->getRecords("member_kyc",array('regnumber'=>$searchBy));



    if (count($r_list))
    {

      $data['result'] =  $r_list;
      $data['status'] =  $kycstatus;
    }

    $this->load->view('admin/kyc/Approver/recommended_list', $data);
    //redirect(base_url().'admin/kyc/Approver/member');
  }


  /*
	- by SAGAR WALZADE : kyccomplete list showed by ajax - code start
	- 17-5-2022
	*/
  public function kyccomplete_newlist()
  {
    $this->load->view('admin/kyc/Approver/kyccomplete_newlist');
  }

  public function get_kyc_complete_list()
  {
    $response = array();
    $postData = $this->input->post();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    ## Search 
    $searchQuery = "";
    // if ($searchValue != '') {
    // 	$searchQuery = " (emp_name like '%" . $searchValue . "%' or email like '%" . $searchValue . "%' or city like'%" . $searchValue . "%' ) ";
    // }

    ## Total number of records without filtering
    $records = $this->db->query("SELECT `kyc_id`, `member_kyc`.`regnumber`, `kyc_id`, `namesub`, `firstname`, `middlename`, `lastname`, `dateofbirth`, `registrationtype`, `email`, `recommended_by`, `recommended_date`, `approved_date` 
							FROM `member_kyc` 
							LEFT JOIN `member_registration` ON `member_registration`.`regnumber`= `member_kyc`.`regnumber` 
							WHERE `member_kyc`.`kyc_state` = 3 
							AND `member_registration`.`isactive` = '1' 
							AND `field_count` = '0' AND `approved_by` = '" . $this->session->userdata('kyc_id') . "'")->result();
    $totalRecords = count($records);



    ## Total number of record with filtering
    // $this->db->select('count(*) as allcount');
    // if($searchQuery != '')
    //    $this->db->where($searchQuery);
    // $records = $this->db->get('employees')->result();
    // $totalRecordwithFilter = $records[0]->allcount;

    ## Fetch records
    $records = $this->db->query("SELECT `kyc_id`, `member_kyc`.`regnumber`, `kyc_id`, `namesub`, `firstname`, `middlename`, `lastname`, `dateofbirth`, `registrationtype`, `email`, `recommended_by`, `recommended_date`, `approved_date` 
							FROM `member_kyc` 
							LEFT JOIN `member_registration` ON `member_registration`.`regnumber`= `member_kyc`.`regnumber` 
							WHERE `member_kyc`.`kyc_state` = 3 
							AND `member_registration`.`isactive` = '1' 
							AND `field_count` = '0' AND `approved_by` = '" . $this->session->userdata('kyc_id') . "'
		 					ORDER BY `kyc_id` DESC LIMIT " . $rowperpage . " OFFSET " . $start . " ")->result();

    $data = array();
    $row_count = ($start + 1);
    foreach ($records as $record)
    {
      $employer = $this->master_model->getRecords("administrators", array('id' => $record->recommended_by), 'name');
      $data[] = array(
        "no" => $row_count,
        "regnumber" => $record->regnumber,
        "name" => $record->namesub . " " . $record->firstname . " " . $record->middlename . " " . $record->lastname,
        "registration_type" => $record->registrationtype,
        "recommended_by" => $employer[0]['name'],
        "recommended_date" => date('d-m-Y ', strtotime($record->recommended_date)),
        "approved_date" => date('d-m-Y ', strtotime($record->approved_date)),
        "action" => '<a href="' . base_url() . 'admin/kyc/Approver/completed_details/' . $record->regnumber . '/' . $row_count . '">View Details</a>',
      );
      $row_count++;
    }

    ## Response
    $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecords,
      // "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
    );

    echo json_encode($response);
  }
  /*
	- by SAGAR WALZADE : kyccomplete list showed by ajax - code end
	*/

  //Details of Recommended member - by prafull
  public function details($regnumber = NULL)
  {
    $data['result'] = array();
    $registrationtype = '';
    $data['reg_no'] = ' ';
    if ($regnumber)
    {

      $members = $this->master_model->getRecords("member_registration", array('regnumber' => $regnumber, 'isactive' => '1'));
      //echo $this->db->last_query();exit;
      if (count($members))
      {
        $data['result'] = $members;
        $data['reg_no'] = $members[0]['regnumber'];
        $id = $data['reg_no'];
      }
    }
    $recommnended_members_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber), '', array('kyc_id' => 'DESC'));
    $data['recomended_mem_data'] = $recommnended_members_data;
    $this->load->view('admin/kyc/Approver/approver_view_recommended_details', $data);
  }

  //Details of KYC completed member - by pawan
  public function completed_details($regnumber = NULL)
  {
    $data['result'] = array();
    $registrationtype = '';
    $data['reg_no'] = ' ';
    if ($regnumber)
    {
      /*
			- by SAGAR WALZADE : 'declaration' column added in below select query
			*/
      $select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,empidproofphoto,excode,registrationtype';
      $members = $this->master_model->getRecords("member_registration a", array('regnumber' => $regnumber, 'isactive' => '1'), $select, "");
      //echo $this->db->last_query();exit;
      if (count($members))
      {
        $data['result'] = $members;
        $data['reg_no'] = $members[0]['regnumber'];
        $id = $data['reg_no'];
      }
    }
    $recommnended_members_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber), '', array('kyc_id' => 'DESC'));
    $data['recomended_mem_data'] = $recommnended_members_data;
    $this->load->view('admin/kyc/Approver/approver_view_kyccompleted_details', $data);
  }


  public function edited_member($regnumber)
  {
    //	echo $regnumber;exit;
    $data['result'] = array();

    $registrationtype = '';
    $data['reg_no'] = ' ';
    if ($regnumber)
    {
      $select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
      $members = $this->master_model->getRecords("member_registration a", array('regnumber' => $regnumber, 'isactive' => '1'), $select, "", '0', '1');
      //echo $this->db->last_query();exit;
      if (count($members))
      {
        $data['result'] = $members;

        $data['reg_no'] = $members[0]['regnumber'];
        $id = $data['reg_no'];
      }
    }
    $recommnended_members_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber), '', array('kyc_id' => 'DESC'));
    $data['recomended_mem_data'] = $recommnended_members_data;
    $this->load->view('admin/kyc/Approver/approver_edited_screen', $data);
  }


  // By VSU : Function to fetch list of members to initiate KYC
  public function member($regnumber)
  {
    //	echo $regnumber;exit;
    $data['result'] = array();

    $registrationtype = '';
    $data['reg_no'] = ' ';
    if ($regnumber)
    {
      $select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
      $members = $this->master_model->getRecords("member_registration a", array('regnumber' => $regnumber, 'isactive' => '1'), $select, "");
      //echo $this->db->last_query();exit;
      if (count($members))
      {
        $data['result'] = $members;

        $data['reg_no'] = $members[0]['regnumber'];
        $id = $data['reg_no'];
        $recommnended_members_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber), '', array('kyc_id' => 'DESC'));
        $data['recomended_mem_data'] = $recommnended_members_data;
      }
    }
    $this->load->view('admin/kyc/Approver/approver_edited_screen', $data);
  }


  public function get_App_allocation_type_cnt()
  {

    // check allocation type
    $new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));
    //echo $this->db->last_query();exit;
    if (count($new_allocated_member_list) > 0)
    {
      if ($new_allocated_member_list[0]['allotted_member_id'] == '')
      {
        redirect(base_url() . 'admin/kyc/Approver/next_allocation_type');
      }
    }

    $kyc_start_date = $this->config->item('kyc_start_date');
    $allocated_member_list = $members = array();
    $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
    //allocated_count
    if (count($allocated_member_list))
    {

      if (count($allocated_member_list) > 0)
      {
        $data['count'] = $allocated_member_list[0]['allocated_count'];
        $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      }
      foreach ($arraid as $row)
      {
        $this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber', 'LEFT');
        $this->db->where('member_registration.isactive', '1');
        $this->db->where('member_registration.isdeleted', '0');
        $this->db->where('member_registration.kyc_status', '0');
        $members = $this->master_model->getRecords("member_kyc", array('member_kyc.regnumber' => $row, 'member_kyc.field_count' => '0'), '', array('kyc_id' => 'DESC'), '0', '1');
        $members_arr[]  = $members;
      }
      $emptylistmsg = ' ';
      $data['emptylistmsg']  = $emptylistmsg;
      $data['result'] = call_user_func_array('array_merge', $members_arr);
      return count($data['result']);
      //$this->load->view('admin/kyc/Approver/approver_edited_list',$data);
    }
    else
    {
      return 0;
    }
  }
  /* Date Filters to get benchmark members from admin_benchmark_kyc_users */
  public function benchmark_allocation_type()
  {
    // check allocation type
    $new_allocated_member_list = $this->master_model->getRecords("admin_benchmark_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));
    //echo "<br><br>1=>".$this->db->last_query();
    if (count($new_allocated_member_list) > 0)
    {
      if ($new_allocated_member_list[0]['allotted_member_id'] == '')
      {
        //redirect(base_url().'admin/kyc/Approver/benchmark_allocation_type');
        $this->load->view('admin/kyc/Approver/benchmark_allocation_type');
      }
    }
    //$kyc_start_date=$this->config->item('kyc_start_date');
    $allocated_member_list = $members = array();
    $allocated_member_list = $this->master_model->getRecords("admin_benchmark_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));

    //echo "<br><br>2=>".$this->db->last_query();

    //allocated_count
    if (count($allocated_member_list))
    {
      if (count($allocated_member_list) > 0)
      {
        $data['count'] = $allocated_member_list[0]['allocated_count'];
        $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      }
      foreach ($arraid as $row)
      {
        $this->db->join('member_registration', 'member_registration.regnumber=benchmark_member_kyc.regnumber', 'LEFT');
        $this->db->where('member_registration.benchmark_kyc_edit', '0');
        $this->db->where('member_registration.isactive', '1');
        $this->db->where('member_registration.benchmark_kyc_status', '0');
        $this->db->where('member_registration.benchmark_disability', 'Y');
        $members = $this->master_model->getRecords("benchmark_member_kyc", array('benchmark_member_kyc.regnumber' => $row, 'benchmark_member_kyc.field_count' => '0'), '', array('kyc_id' => 'DESC'), '0', '1');

        //echo "<br><br>3=>".$this->db->last_query();

        $members_arr[]  = $members;
      }
      $emptylistmsg = ' ';
      $data['emptylistmsg']  = $emptylistmsg;
      $data['result'] = call_user_func_array('array_merge', $members_arr);
      /* Start Code To Get Recent Allotted Member Total Count */
      $pagination_total_count = $this->master_model->getRecords("admin_benchmark_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");

      //echo "<br><br>4=>".$this->db->last_query();
      //exit;
      if (!empty($pagination_total_count))
      {
        foreach ($pagination_total_count[0] as $k => $value)
        {
          if ($k == "pagination_total_count")
          {
            $data['totalRecCount'] = $value;
          }
          if ($k == "original_allotted_member_id")
          {
            $data['original_allotted_member_id'] = $value;
          }
        }
      }
      /* Close Code To Get Recent Allotted Member Total Count */
      $this->load->view('admin/kyc/Approver/benchmark_approver_edited_list', $data);
    }
    else
    {
      $this->load->view('admin/kyc/Approver/benchmark_allocation_type');
    }
  }
  /* Get Member from admin_benchmark_kyc_users */
  public function benchmark_approver_edited_list()
  {
    $tilte = $type = '';
    $description = $emptylistmsg = '';
    $allocates_arr = $members_arr = $result = $array = array();
    $data['result'] = array();
    $regstr = $searchText = $searchBy = '';
    $searchBy_regtype = '';
    $today = date('Y-m-d H:i:s');
    $per_page = 200;
    $last = 199;
    $start = 0;
    $list_type = 'New';
    $page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
    if ($this->input->post('regnumber') != '')
    {
      $searchBy = $this->input->post('regnumber');
    }
    $form_start_date = $form_end_date = '';
    if ($this->input->post('form_start_date') != '')
    {
      $form_start_date = $this->input->post('form_start_date');
    }
    if ($this->input->post('form_end_date') != '')
    {
      $form_end_date = $this->input->post('form_end_date');
    }
    $data['reg_no'] = ' ';
    if ($page != 0)
    {
      $start = $page - 1;
    }
    $allocates = array();
    //get  all  user loging today 
    if (isset($form_start_date) && isset($form_end_date))
    {
      //$type=$_POST['selectby'];
      $kyc_data = $this->master_model->getRecords("admin_benchmark_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_type' => 'approver'), 'original_allotted_member_id');
      //echo "<br>SQL 1 =>".$this->db->last_query(); //exit;;
      $allocatedmemberarr = array();
      if (count($kyc_data) > 0)
      {
        foreach ($kyc_data as $row)
        {
          $allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
        }
      }
      $member_kyc = $this->db->query("SELECT regnumber,kyc_id
				FROM benchmark_member_kyc
				WHERE kyc_id IN (
				SELECT MAX(kyc_id)
				FROM benchmark_member_kyc
				GROUP BY regnumber
				) and (kyc_state = 2 OR kyc_state = 1) AND field_count > 0");
      //echo "<br>SQL 2 =>".$this->db->last_query(); //exit;
      $recommendedmemberarr = array();
      if ($member_kyc->num_rows() > 0)
      {
        foreach ($member_kyc->result_array()  as $row)
        {
          $recommendedmemberarr[] = $row['regnumber'];
        }
      }
      $data_array = array();
      if (count($allocatedmemberarr) > 0)
      {
        // get the column data in a single array
        $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
        /*if(count($data_array) > 0)
						{
						$this->db->where_not_in('member_kyc.regnumber',array_map('stripslashes', $data_array));
					}*/
      }
      // merge allocated member array with recommended members array
      $data_array = array_merge($data_array, $recommendedmemberarr);
      if (count($data_array) > 0)
      {
        $this->db->where_not_in('benchmark_member_kyc.regnumber', array_map('stripslashes', $data_array));
      }
      $this->db->join('member_registration', 'member_registration.regnumber= benchmark_member_kyc.regnumber', 'LEFT');
      $this->db->where('member_registration.benchmark_kyc_status', '0');
      $this->db->where('benchmark_member_kyc.kyc_status', '0');
      $this->db->where('member_registration.isactive', '1');
      //$this->db->where('member_registration.registrationtype',$type);	
      $this->db->where('benchmark_kyc_edit', '0');
      $this->db->where('((benchmark_disability = "Y" AND DATE(createdon)>="' . $form_start_date . '" 
				AND DATE(createdon)<="' . $form_end_date . '") OR 
				(benchmark_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(benchmark_edit_date)>="' . $form_start_date . '" 
				AND DATE(benchmark_edit_date)<="' . $form_end_date . '"))');
      $this->db->group_by('benchmark_member_kyc.regnumber');
      $r_list = $this->master_model->getRecords("benchmark_member_kyc", array('field_count' => 0, 'kyc_state' => 1), 'MAX(kyc_id),benchmark_member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,benchmark_member_kyc.kyc_status, benchmark_member_kyc.mem_visually,benchmark_member_kyc.mem_orthopedically,benchmark_member_kyc.mem_cerebral 
				,isactive,field_count', array('kyc_id' => 'DESC'), $start, $per_page);
      /*echo "<br>SQL 3 =>".$this->db->last_query(); //exit;
					echo '<pre>';
				print_r($r_list);*/
      //exit;
      $today = date("Y-m-d H:i:s");
      $row_count = $this->master_model->getRecordCount("admin_benchmark_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New'));
      //echo "<br>SQL 4 =>".$this->db->last_query(); exit;
      if ($row_count == 0)
      {
        $regstr = '';
        foreach ($r_list  as $row)
        {
          $allocates_arr[] = $row['regnumber'];
        }
        if (count($allocates_arr) > 0)
        {
          $regstr = implode(',', $allocates_arr);
        }
        //print_r($regstr);
        //exit;
        if ($regstr != '')
        {
          $insert_data = array(
            'user_type'      => $this->session->userdata('role'),
            'user_id'        => $this->session->userdata('kyc_id'),
            'allotted_member_id'  => $regstr,
            'original_allotted_member_id'  => $regstr,
            'allocated_count'     => count($allocates_arr),
            'allocated_list_count'     => '1',
            'date'                  => $today,
            'list_type'             => 'New',
            'pagination_total_count ' => count($allocates_arr)
          );
          $this->master_model->insertRecord('admin_benchmark_kyc_users', $insert_data);
          //log activity 
          $tilte = 'Approver  KYC  member list allocation';
          $description = 'Approver has allocated ' . count($allocates_arr) . ' member';
          $user_id = $this->session->userdata('kyc_id');
          $this->KYC_Log_model->benchmark_create_log($tilte, $user_id, '', '', $description);
        }
      }
    }
    $allocated_member_list = $this->master_model->getRecords("admin_benchmark_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
    if (count($allocated_member_list) > 0)
    {
      $data['count'] = $allocated_member_list[0]['allocated_count'];
    }
    else
    {
      $data['count'] = 0;
    }
    if (count($allocated_member_list) > 0)
    {
      $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      //$data['result'] = $members;
      //$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
      if (count($arraid) > 0)
      {
        if ($form_start_date != '' && $form_end_date != '')
        {
          if ($searchBy != '' && $form_start_date != '' && $form_end_date != '')
          {   //echo "<br>IF 1";
            //$this->db->join('benchmark_member_kyc','benchmark_member_kyc.regnumber=benchmark_member_kyc.regnumber','LEFT');
            $this->db->where('regnumber', $searchBy);
            $this->db->where('((benchmark_disability = "Y" AND DATE(createdon)>="' . $form_start_date . '" 
							AND DATE(createdon)<="' . $form_end_date . '") OR 
							(benchmark_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(benchmark_edit_date)>="' . $form_start_date . '" 
							AND DATE(benchmark_edit_date)<="' . $form_end_date . '"))');
            $this->db->where('benchmark_kyc_status', '0');
            $members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
          }
          else if ($searchBy != '')
          {  //echo "<br>eles if 2";
            $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
            //$this->db->join('benchmark_member_kyc','benchmark_member_kyc.regnumber=benchmark_member_kyc.regnumber','LEFT');
            $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
            $this->db->where('regnumber', $searchBy);
            $this->db->where('benchmark_kyc_status', '0');
            $members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
          }
          else if ($form_start_date != '' && $form_end_date != '')
          { //echo "<br>eles if 3";
            //$this->db->join('benchmark_member_kyc','benchmark_member_kyc.regnumber=benchmark_member_kyc.regnumber','LEFT');
            $this->db->where('((benchmark_disability = "Y" AND DATE(createdon)>="' . $form_start_date . '" 
							AND DATE(createdon)<="' . $form_end_date . '") OR 
							(benchmark_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(benchmark_edit_date)>="' . $form_start_date . '" 
							AND DATE(benchmark_edit_date)<="' . $form_end_date . '"))');
            $this->db->where('benchmark_kyc_status', '0');
            $members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
          }
          if (count($members) > 0)
          {
            foreach ($members as $row)
            {
              $members_arr[][] = $row;
            }
          }
        }
        else
        {
          //default allocation list for 100 member
          foreach ($arraid as $row)
          {
            $this->db->join('member_registration', 'member_registration.regnumber=benchmark_member_kyc.regnumber', 'LEFT');
            $this->db->where('member_registration.isactive', '1');
            $this->db->where('member_registration.isdeleted', '0');
            $this->db->where('benchmark_member_kyc.kyc_status', '0');
            $this->db->where('member_registration.benchmark_kyc_status', '0');
            //$this->db->where('member_registration.registrationtype',$type);	
            $members = $this->master_model->getRecords("benchmark_member_kyc", array('benchmark_member_kyc.regnumber' => $row, 'benchmark_member_kyc.field_count' => '0', 'benchmark_member_kyc.kyc_state' => 1), '', array('kyc_id' => 'DESC'), '0', '1');
            $members_arr[]  = $members;
          }
        }
      }
      //echo "<br>".$this->db->last_query();
      $data['result'] = call_user_func_array('array_merge', $members_arr);
      //echo '<pre>';
      //print_r($data['result']);
      //exit;
    }
    $total_row = 200;
    $url = base_url() . "admin/kyc/Approver/benchmark_approver_edited_list/";
    $config = pagination_init($url, $total_row, $per_page, 2);
    $this->pagination->initialize($config);
    $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Search</li>
			</ol>';
    $str_links = $this->pagination->create_links();
    //var_dump($str_links);
    $data["links"] = $str_links;
    if (($start + $per_page) > $total_row)
      $end_of_total = $total_row;
    else
      $end_of_total = $start + $per_page;
    if ($total_row)
      $data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
    else
      $data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';
    $data['index'] = $start + 1;
    $emptylistmsg = ' No records available...!!<br />
			<a href=' . base_url() . 'admin/kyc/Approver/benchmark_allocation_type/>Back</a>';
    /* Start Code To Get Recent Allotted Member Total Count */
    $pagination_total_count = $this->master_model->getRecords("admin_benchmark_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
    if (!empty($pagination_total_count))
    {
      foreach ($pagination_total_count[0] as $k => $value)
      {
        if ($k == "pagination_total_count")
        {
          $data['totalRecCount'] = $value;
        }
        if ($k == "original_allotted_member_id")
        {
          $data['original_allotted_member_id'] = $value;
        }
      }
    }
    /* Close Code To Get Recent Allotted Member Total Count */
    $data['emptylistmsg']  = $emptylistmsg;
    //		redirect(base_url().'admin/kyc/Approver/approver_edited_list');	
    $this->db->distinct('registrationtype');
    $data['mem_type'] = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('registrationtype' => 'ASC'));
    $this->load->view('admin/kyc/Approver/benchmark_approver_edited_list', $data);
  }
  /* Bechmark KYC recommend and kyc complete start */
  function benchmark_approver_edited_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
  {
    if ($regnumber)
    {
      $oldfilepath = $file_path = $photo_file = '';
      $state = $next_id = $success = $error = $description = '';
      $data['result'] = $name = $update_data = $old_user_data = $member_kyc_lastest_record = $sql = array();
      $new_arrayid = $noarray = array();
      $today = $date = date('Y-m-d H:i:s');
      $registrationtype = '';
      $data['reg_no'] = ' ';
      $field_count = 0;
      // recommendation submit
      if (isset($_POST['btnSubmitRecmd']))
      {
        $select = 'regnumber,registrationtype,email,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img';
        $data = $this->master_model->getRecords('member_registration', array(
          'regnumber' => $regnumber,
          'isactive' => '1',
          'benchmark_kyc_status' => '0'
        ), $select);
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }
        $regnumber = $data[0]['regnumber'];
        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {
            $check_arr[] = $cbox;
          }
        }
        $msg = 'Benchmark Edit your profile as :-';
        if (count($check_arr) > 0)
        {
          $folder_name = date('d-m-Y');
          $new_img_name = date('H:i:s');
          if (in_array('visually_checkbox', $check_arr))
          {
            $visually_checkbox = '1';
          }
          else
          {
            if ($data[0]['vis_imp_cert_img'] != '')
            {
              $visually_checkbox = '0';
              $field_count++;
              $update_data[] = 'Visually';
              $msg .= 'Visually,';
              if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name))
              {
                mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name, 0777, true);
              }
              if (file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name))
              {
                if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability'))
                {
                  mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability', 0777, true);
                }
              }
              $original_file = base_url() . "uploads/disability/v_" . $regnumber . ".jpg";
              $newfile = getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability/v_' . $regnumber . '_' . $new_img_name . '.jpg';
              copy($original_file, $newfile);
            }
            else
            {
              $visually_checkbox = '3';
            }
          }
          if (in_array('orthopedically_checkbox', $check_arr))
          {
            $orthopedically_checkbox = '1';
          }
          else
          {
            if ($data[0]['orth_han_cert_img'] != '')
            {
              $orthopedically_checkbox = '0';
              $field_count++;
              $update_data[] = 'Orthopedically';
              $msg .= 'Orthopedically,';
              if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name))
              {
                mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name, 0777, true);
              }
              if (file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name))
              {
                if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability'))
                {
                  mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability', 0777, true);
                }
              }
              $original_file = base_url() . "uploads/disability/o_" . $regnumber . ".jpg";
              $newfile = getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability/o_' . $regnumber . '_' . $new_img_name . '.jpg';
              copy($original_file, $newfile);
            }
            else
            {
              $orthopedically_checkbox = '3';
            }
          }
          if (in_array('cerebral_checkbox', $check_arr))
          {
            $cerebral_checkbox = '1';
          }
          else
          {
            if ($data[0]['cer_palsy_cert_img'] != '')
            {
              $cerebral_checkbox = '0';
              $field_count++;
              $update_data[] = 'Cerebral';
              $msg .= 'Cerebral';
              if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name))
              {
                mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name, 0777, true);
              }
              if (file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name))
              {
                if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability'))
                {
                  mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability', 0777, true);
                }
              }
              $original_file = base_url() . "uploads/disability/c_" . $regnumber . ".jpg";
              $newfile = getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability/c_' . $regnumber . '_' . $new_img_name . '.jpg';
              copy($original_file, $newfile);
            }
            else
            {
              $cerebral_checkbox = '3';
            }
          }
        }
        else
        {
          if ($data[0]['vis_imp_cert_img'] != '')
          {
            $visually_checkbox = '0';
            $msg .= 'Visually';
            $field_count++;
            $update_data[] = 'Visually';
          }
          else
          {
            $visually_checkbox = '3';
          }

          if ($data[0]['orth_han_cert_img'] != '')
          {
            $orthopedically_checkbox = '0';
            $msg .= 'Orthopedically';
            $field_count++;
            $update_data[] = 'Orthopedically';
          }
          else
          {
            $orthopedically_checkbox = '3';
          }

          if ($data[0]['cer_palsy_cert_img'] != '')
          {
            $cerebral_checkbox = '0';
            $msg .= 'Cerebral';
            $field_count++;
            $update_data[] = 'Cerebral';
          }
          else
          {
            $cerebral_checkbox = '3';
          }
        }
        $email = $data[0]['email'];
        if ($visually_checkbox == '1' && $orthopedically_checkbox == '1' && $cerebral_checkbox == '1')
        {
          $this->session->set_flashdata('error', 'Please uncheck atleast one checkbox!!');
        }
        else
        {
          $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img';
          $old_user_data = $this->master_model->getRecords('member_registration', array(
            'regnumber' => $regnumber,
            'isactive' => '1'
          ), $select);
          $userdata = $this->master_model->getRecords("member_registration", array(
            'regnumber' => $regnumber,
            'isactive' => '1'
          ));
          $insert_data = array(
            'regnumber' => $data[0]['regnumber'],
            'mem_visually' => $visually_checkbox,
            'mem_orthopedically' => $orthopedically_checkbox,
            'mem_cerebral' => $cerebral_checkbox,
            'field_count' => $field_count,
            'old_data' => serialize($old_user_data),
            'kyc_status' => '0',
            'kyc_state' => '1',
            'recommended_by' => $this->session->userdata('kyc_id'),
            'user_type' => $this->session->userdata('role'),
            'recommended_date' => $today,
            'record_source' => 'New'
          );
          // insert the record and get latest  kyc_id
          $last_insterid = $this->master_model->insertRecord('benchmark_member_kyc', $insert_data, true);

          include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
          $key = $this->config->item('pass_key');
          $aes = new CryptAES();
          $aes->set_key(base64_decode($key));
          $aes->require_pkcs5();
          $userpass = $aes->decrypt($userdata[0]['usrpassword']);
          $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
          $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
          $msg = implode(',', $update_data);
          $emailerstr = $this->master_model->getRecords('emailer', array(
            'emailer_name' => 'benchmark_recommendation_email'
          ));
          $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
          $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
          $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
          $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
          if ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A')
          {
            $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);
          }
          else
          {
            $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);
          }
          $info_arr = array(
            //'to'=> "kyciibf@gmail.com",
            'to' => $userdata[0]['email'],
            'from' => $emailerstr[0]['from'],
            'subject' => $emailerstr[0]['subject'],
            'message' => $final_str
          );

          if ($this->Emailsending->mailsend($info_arr))
          {
            $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');
            $select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img';
            $old_data = $this->master_model->getRecords("member_registration", array(
              'regnumber' => $regnumber,
              'isactive' => '1'
            ), $select);
            $log_desc['old_data'] = $old_data;
            $log_desc['inserted_data'] = $insert_data;
            $description = serialize($log_desc);
            $this->KYC_Log_model->benchmark_create_log('Benchmark Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
            // email log
            $this->KYC_Log_model->benchmark_email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            // make recommended fields empty  -
            if (in_array('Visually', $update_data))
            {
              $updatedata['vis_imp_cert_img'] = '';
              $updatedata['visually_impaired'] = '';
              $oldfilepath = "uploads/disability/v_" . $regnumber . ".jpg";
              $noarray = explode('/v_', $oldfilepath);
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_v_' . $noarray[1];
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->benchmark_create_log('Recommended Benchmark Visually Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->benchmark_create_log('fail to delete Benchmark Visually', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->benchmark_create_log('member Benchmark Visually not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
              }
            }
            if (in_array('Orthopedically', $update_data))
            {
              $updatedata['orth_han_cert_img'] = '';
              $updatedata['orthopedically_handicapped'] = '';
              $oldfilepath = "uploads/disability/o_" . $regnumber . ".jpg";
              $noarray = explode('/o_', $oldfilepath);
              $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_o_' . $noarray[1];
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->benchmark_create_log('Recommended Benchmark Orthopedically rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->benchmark_create_log('fail to delete Benchmark Orthopedically', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->benchmark_create_log('member Benchmark Orthopedically not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
              }
            }
            if (in_array('Cerebral', $update_data))
            {
              $updatedata['cer_palsy_cert_img'] = '';
              $updatedata['cerebral_palsy'] = '';
              $oldfilepath = "uploads/disability/c_" . $regnumber . ".jpg";
              $noarray = explode('/c_', $oldfilepath);
              $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_c_' . $noarray[1];
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->benchmark_create_log('Recommended benchmark cerebral Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->benchmark_create_log('fail to delete benchmark cerebral ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->benchmark_create_log('member benchmark cerebral not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
              }
            }
            if (!empty($updatedata))
            {
              $this->db->where('isactive', '1');
              $this->master_model->updateRecord('member_registration', $updatedata, array(
                'regnumber' => $regnumber
              ));
            }
            $member = $this->master_model->getRecords("admin_benchmark_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'user_id' => $this->session->userdata('kyc_id')
            ));
            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);
            // get next record
            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }
            // end of next record
            // unset the  current id index
            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }
            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }
            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'New');
            $this->master_model->updateRecord('admin_benchmark_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));
            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }
            // $totalRecCount=$this->get_App_allocation_type_cnt();
            if ($srno > $totalRecCount)
            {
              // $srno=$totalRecCount;
              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }
            redirect(base_url() . 'admin/kyc/Approver/benchmark_approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
        }
      }
      // kyc submit
      if (isset($_POST['btnSubmitkyc']))
      {
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }
        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {
            $check_arr[] = $cbox;
          }
        }
        $regnumber = $this->input->post('regnumber');
        $this->db->where('regnumber', $regnumber);
        $this->db->where('isactive', '1');
        $member_regtype = $this->master_model->getRecords('member_registration', '', 'registrationtype,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img');

        /*echo "<pre>";
					
					echo "<br>".$member_regtype[0]['vis_imp_cert_img'];
					echo "<br>".$member_regtype[0]['orth_han_cert_img'];
					echo "<br>".$member_regtype[0]['cer_palsy_cert_img'];
					print_r($name);
					print_r($check_arr);
					exit;*/

        if (count($check_arr) == 3 && (in_array('visually_checkbox', $check_arr) || in_array('orthopedically_checkbox', $check_arr) || in_array('cerebral_checkbox', $check_arr)))
        //if($member_regtype[0]['vis_imp_cert_img'] != '' $member_regtype[0]['orth_han_cert_img'] )
        {

          $new_arrayid = $members = array();
          $status = '0';
          $state = '1';
          $date = date("Y-m-d H:i:s");
          // $this->db->where('recommended_date',$date);
          $this->db->where('regnumber', $regnumber);
          $member_kyc_details = $this->master_model->getRecords('benchmark_member_kyc');
          if (isset($_POST['cbox']))
          {
            $name = $this->input->post('cbox');
          }
          //$regnumber=$data[0]['regnumber'];

          $check_arr = array();
          if (count($name) > 0)
          {
            foreach ($name as $cbox)
            {
              $check_arr[] = $cbox;
            }
          }
          $msg = 'Benchmark Edit your profile as :-';

          if (in_array('visually_checkbox', $check_arr))
          {
            $visually_checkbox = '1';
          }
          else
          {
            if ($member_regtype[0]['vis_imp_cert_img'] != '')
            {
              $visually_checkbox = '0';
              $msg .= 'Visually,';
            }
            else
            {
              $visually_checkbox = '3';
            }
          }
          if (in_array('orthopedically_checkbox', $check_arr))
          {
            $orthopedically_checkbox = '1';
          }
          else
          {
            if ($member_regtype[0]['orth_han_cert_img'] != '')
            {
              $orthopedically_checkbox = '0';
              $msg .= 'Orthopedically';
            }
            else
            {
              $orthopedically_checkbox = '3';
            }
          }
          if (in_array('cerebral_checkbox', $check_arr))
          {
            $cerebral_checkbox = '1';
          }
          else
          {
            if ($member_regtype[0]['cer_palsy_cert_img'] != '')
            {
              $cerebral_checkbox = '0';
              $msg .= 'Cerebral_checkbox';
            }
            else
            {
              $cerebral_checkbox = '3';
            }
          }
          // $email=$data[0]['email'];
          $update_data = array(
            'mem_visually' => $visually_checkbox,
            'mem_orthopedically' => $orthopedically_checkbox,
            'mem_cerebral' => $cerebral_checkbox,
            'kyc_status' => '1',
            'kyc_state' => 3,
            'approved_by' => $this->session->userdata('kyc_id'),
            'approved_date' => $today,
            // 'record_source'			=>'Edit'
          );
          // query to update the latest record of the regnumber
          $sql = 'kyc_id in (SELECT max(kyc_id) FROM benchmark_member_kyc GROUP BY regnumber )';
          $this->db->where($sql);
          $member_kyc_lastest_record = $this->master_model->getRecords("benchmark_member_kyc", array(
            'regnumber' => $regnumber
          ), 'regnumber,kyc_state,kyc_id', array(
            'kyc_id' => 'DESC'
          ));
          // print_r($member_kyc_lastest_record );exit;
          $this->db->where('isactive', '1');
          $this->master_model->updateRecord('member_registration', array(
            'benchmark_kyc_status' => '1'
          ), array(
            'regnumber' => $regnumber
          ));

          /*echo "<pre>";
						print_r($update_data);
						exit;*/

          // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));
          $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
          $this->master_model->updateRecord('benchmark_member_kyc', $update_data, array(
            'regnumber' => $member_kyc_lastest_record[0]['regnumber']
          ));
          /*reset the dowanload count*/

          /*$where1 = array(
						'member_number' => $regnumber
						);
						$this->master_model->updateRecord('member_idcard_cnt', array(
						'card_cnt' => '0'
						) , $where1);*/

          $last_insterid = $this->master_model->getRecords("benchmark_member_kyc", array(
            'regnumber' => $regnumber,
            'kyc_status' => '1',
            'kyc_state' => 3,
            'approved_by' => $this->session->userdata('kyc_id')
          ), 'kyc_id', array(
            'kyc_id' => 'DESC'
          ), '0', '1');


          $nomsg = '';
          $userdata = $this->master_model->getRecords("member_registration", array(
            'regnumber' => $regnumber,
            'isactive' => '1'
          ));
          $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
          $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
          $msg = implode(',', $update_data);
          $emailerstr = $this->master_model->getRecords('emailer', array(
            'emailer_name' => 'benchmark_KYC_completion_email'
          ));

          $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
          $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

          $info_arr = array(
            //'to'=> "kyciibf@gmail.com",
            'to' => $userdata[0]['email'],
            'from' => $emailerstr[0]['from'],
            'subject' => $emailerstr[0]['subject'],
            'message' => $final_str
          );

          if ($this->Emailsending->mailsend($info_arr))
          {
            $this->session->set_flashdata('success', 'Benchmark KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');
            // $success='KYC Completed for the candidate & Email sent successfully !!';
            // log activity
            $regnumber = $regnumber;
            $user_id = $this->session->userdata('kyc_id');
            $tilte = 'Member Benchmark KYC completed';
            $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
            $this->KYC_Log_model->benchmark_create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);
            // $this->session->set_flashdata('success','kyc completed Successfully  !!');
            // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
            // email log
            $this->KYC_Log_model->benchmark_email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
          }
          // rebulide the array
          $member = $this->master_model->getRecords("admin_benchmark_kyc_users", array(
            'DATE(date)' => date('Y-m-d'),
            'user_id' => $this->session->userdata('kyc_id')
          ));
          $arrayid = explode(',', $member[0]['allotted_member_id']);
          $index = array_search($regnumber, $arrayid, true);
          // get next record
          $currentid = $index;
          $nextid = $currentid + 1;
          if (array_key_exists($nextid, $arrayid))
          {
            $next_id = $arrayid[$nextid];
          }
          else
          {
            $next_id = $arrayid[0];
          }
          // end of next record
          unset($arrayid[$index]);
          if (count($arrayid) > 0)
          {
            foreach ($arrayid as $row)
            {
              $new_arrayid[] = $row;
            }
          }
          if (count($new_arrayid) > 0)
          {
            $regstr = implode(',', $new_arrayid);
          }
          else
          {
            $regstr = '';
            $next_id = '';
          }
          $update_data = array(
            'allotted_member_id' => $regstr
          );
          $this->db->where('DATE(date)', date('Y-m-d'));
          $this->db->where('list_type', 'New');
          $this->master_model->updateRecord('admin_benchmark_kyc_users', $update_data, array(
            'user_id' => $this->session->userdata('kyc_id')
          ));
          /* Start Code To Showing Count On Member List*/
          if ($next_id == '')
          {
            $next_id = 0;
          }
          // $totalRecCount=$this->get_App_allocation_type_cnt();
          if ($srno > $totalRecCount)
          {
            // $srno=$totalRecCount;
            $srno = 1;
          }
          else
          {
            $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
            $arr = array_slice($original_allotted_Arr, -$totalRecCount);
            $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
            $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
            $memberNo = $next_id;
            $updated_list_index = array_search($memberNo, $reversedArr_list);
            $srno = $updated_list_index;
          }
          redirect(base_url() . 'admin/kyc/Approver/benchmark_approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
          /* Close Code To Showing Count On Member List*/
        }
        else
        {
          $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');
          // $error='Select all check-box to complete the Kyc !!';
          // redirect(base_url().'admin/kyc/Approver/approver_edited_member/'.$regnumber);
        }
      }
      if ($regnumber)
      {
        $select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,scannedsignaturephoto,idproofphoto,registrationtype';
        $members = $this->master_model->getRecords("member_registration a", array(
          'regnumber' => $regnumber,
          'isactive' => '1'
        ), $select, "", '0', '1');
      }
      $recommnended_members_data = $this->master_model->getRecords("benchmark_member_kyc", array(
        'regnumber' => $regnumber
      ), '', array(
        'kyc_id' => 'DESC'
      ), '0', '1');
      $data = array(
        'result' => $members,
        'next_id' => $next_id,
        'recomended_mem_data' => $recommnended_members_data,
        'error' => $error,
        'success' => $success
      );
      $data['srno'] = $srno;
      $data['totalRecCount'] = $totalRecCount;
      $this->load->view('admin/kyc/Approver/benchmark_approver_edited_screen', $data);
    }
    else
    {
      $this->session->set_flashdata('success', $this->session->flashdata('success'));
      redirect(base_url() . 'admin/kyc/Approver/benchmark_approver_edited_list');
    }
  }
  /* Bechmark KYC recommend and kyc complete Close */


  //PROFESSIONAL BANKER KYC : ADDED BY SAGAR ON 20-12-2021
  function professional_banker_kyc()
  {
    $this->load->view('admin/kyc/Approver/professional_banker_kyc');
  }

  public function get_professional_banker_data_ajax()
  {
    $table = 'professional_banker_registrations pbr';

    $column_order = array('pbr.pb_reg_id', 'pbr.regnumber', '(SELECT CONCAT(namesub, " ", firstname, " ", lastname) FROM member_registration WHERE regnumber = pbr.regnumber LIMIT 1) AS DispName', 'pbr.exam_code', 'em.description', 'pt.amount',  'pbr.exp_cert', 'IF(pbr.kyc_status=0, "Pending", IF(pbr.kyc_status=1,"Approved","Rejected")) AS KycStatus', 'pbr.remark', 'pbr.created_on', 'pbr.kyc_status'); //SET COLUMNS FOR SORT

    $column_search = array('pbr.regnumber', '(SELECT CONCAT(namesub, " ", firstname, " ", lastname) FROM member_registration WHERE regnumber = pbr.regnumber LIMIT 1)', 'pbr.exam_code', 'em.description', 'pt.amount', 'pbr.exp_cert', 'IF(pbr.kyc_status=0, "Pending", IF(pbr.kyc_status=1,"Approved","Rejected"))', 'pbr.remark', 'pbr.created_on'); //SET COLUMN FOR SEARCH
    $order = array('pbr.created_on' => 'DESC'); // DEFAULT ORDER

    $exam_code_str = '1021,1022,1023,1024,1025';
    $WhereForTotal = "WHERE pbr.exam_code IN (" . $exam_code_str . ") AND pbr.exam_period = '997' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    // $Where = "WHERE pbr.exam_code IN (" . $exam_code_str . ") AND pbr.exam_period = '997' ";

    // show only candidates who having payment status success [pt.status = 1 that means success]
    $Where = "WHERE pbr.exam_code IN (" . $exam_code_str . ") AND pbr.exam_period = '997' AND pt.status = 1";
    // echo '<pre>';print_R($_POST);echo '</pre>';die;
    if ($_POST['search']['value']) // DATATABLE SEARCH
    {
      if (in_array(strtolower($_POST['search']['value']), array('pending', 'approve', 'approved', 'reject', 'rejected')))
      {
        $Where .= " AND (";
        $Where .= "IF(pbr.kyc_status=0, 'Pending', IF(pbr.kyc_status=1,'Approved','Rejected')) LIKE '%" . ($this->custom_safe_string($_POST['search']['value'])) . "%' ESCAPE '!' ";
        $Where .= ')';
      }
      else
      {
        $Where .= " AND (";
        for ($i = 0; $i < count($column_search); $i++)
        {
          $Where .= $column_search[$i] . " LIKE '%" . ($this->custom_safe_string($_POST['search']['value'])) . "%' ESCAPE '!' OR ";
        }
        $Where = substr_replace($Where, "", -3);
        $Where .= ')';
      }
    }

    //CUSTOM SEARCH FOR NAME
    /* $s_name = trim($this->security->xss_clean($this->input->post('s_name')));
      if($s_name != "") { $Where .= " AND CONCAT(cm.fname, ' ' , cm.mname, IF(cm.mname != '', ' ', ''), cm.lname) LIKE '%".custom_safe_string($s_name)."%'"; } */

    $Order = ""; //DATATABLE SORT
    if (isset($_POST['order']))
    {
      $explode_arr = explode("AS", $column_order[$_POST['order']['0']['column']]);
      $Order = "ORDER BY " . $explode_arr[0] . " " . $_POST['order']['0']['dir'];
    }
    else if (isset($order))
    {
      $Order = "ORDER BY " . key($order) . " " . $order[key($order)];
    }

    $Limit = "";
    if ($_POST['length'] != '-1')
    {
      $Limit = "LIMIT " . intval($_POST['start']) . ", " . intval($_POST['length']);
    } // DATATABLE LIMIT	

    $join_qry = "INNER JOIN member_exam me ON me.id = pbr.mem_exam_id AND me.exam_code = pbr.exam_code AND me.exam_period = pbr.exam_period ";
    $join_qry .= "INNER JOIN payment_transaction pt ON pt.ref_id = me.id AND pt.exam_code = pbr.exam_code AND pt.status IN (1)";
    // $join_qry .= "INNER JOIN payment_transaction pt ON pt.ref_id = me.id AND pt.exam_code = pbr.exam_code AND pt.status IN (1,3)";
    $join_qry .= "LEFT JOIN exam_master em ON em.exam_code = pbr.exam_code";

    $print_query = "SELECT " . str_replace(" , ", " ", implode(", ", $column_order)) . " FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
    $Result = $this->db->query($print_query);
    $Rows = $Result->result_array();

    $TotalResult = count($this->getAllRec($column_order[0], $table . " " . $join_qry, $WhereForTotal));
    $FilteredResult = count($this->getAllRec($column_order[0], $table . " " . $join_qry, $Where));

    $data = array();
    $no = $_POST['start'];

    foreach ($Rows as $Res)
    {
      $no++;
      $row = array();

      $row[] = $no;
      $row[] = $Res['regnumber'];
      $row[] = $Res['DispName'];
      $row[] = $Res['exam_code'];
      $row[] = $Res['description'];
      $row[] = $Res['amount'];
      $row[] = '<a href="' . site_url('admin/kyc/Approver/download_exp_cert/' . base64_encode($Res['pb_reg_id'])) . '" class="btn btn-sm btn-primary" style="padding:1px 5px 2px">Download</a>';
      $row[] = $Res['KycStatus'];
      $row[] = $Res['remark'];
      $row[] = $Res['created_on'];

      if ($Res['kyc_status'] == 0)
      {
        $row[] = '<a href="' . site_url('admin/kyc/Approver/approver_edited_professional_banker_member/' . base64_encode($Res['pb_reg_id'])) . '" class="btn btn-sm btn-primary" style="padding:1px 5px 2px">Approve/Reject</a>';
      }
      else
      {
        $row[] = '--';
      }

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

  function custom_safe_string($str = "")
  {
    $str = str_replace('"', "&quot;", $str);
    $str = str_replace("'", "&apos;", $str);
    return $str;
  }

  public function approver_edited_professional_banker_member($enc_pb_reg_id = 0)
  {
    $pb_reg_id = $enc_pb_reg_id;
    if ($enc_pb_reg_id != '0')
    {
      $pb_reg_id = base64_decode($enc_pb_reg_id);
    }

    $exam_code_arr = array(1021, 1022, 1023, 1024, 1025);
    $this->db->where_in('pbr.exam_code', $exam_code_arr);
    $this->db->join('member_exam me', 'me.id = pbr.mem_exam_id AND me.exam_code = pbr.exam_code AND me.exam_period = pbr.exam_period', 'INNER');
    $this->db->join('payment_transaction pt', 'pt.ref_id = me.id AND pt.exam_code = pbr.exam_code AND pt.status IN (1)', 'INNER');
    $this->db->join('exam_master em', 'em.exam_code = pbr.exam_code', 'LEFT');
    $exp_cert_data = $this->master_model->getRecords('professional_banker_registrations pbr', array('pbr.pb_reg_id' => $pb_reg_id, 'pbr.exam_period' => '997', 'pbr.kyc_status' => '0'), 'pbr.pb_reg_id, pbr.regnumber, pbr.exam_code, (SELECT CONCAT(namesub, " ", firstname, " ", lastname) FROM member_registration WHERE regnumber = pbr.regnumber LIMIT 1) AS DispName, IF(pbr.kyc_status=0, "Pending", IF(pbr.kyc_status=1,"Approvd","Rejected")) AS KycStatus, pbr.remark, pbr.created_on, pbr.kyc_status, pbr.exp_cert, em.description, pt.amount');
    // echo $this->db->last_query(); exit;
    // echo '<pre>'; print_r($exp_cert_data); echo '</pre>';die;

    if (count($exp_cert_data) > 0)
    {
      if (count($_POST) && count($_POST) > 0)
      {
        $btnSubmitkyc = $this->input->post('btnSubmitkyc');
        if ($btnSubmitkyc == 'Approve')
        {
          $regnumber = $this->input->post('regnumber');
          $enc_pb_reg_id_posted = $this->input->post('pb_reg_id');
        }
        else
        {
          $regnumber = $this->input->post('regnumber_modal');
          $enc_pb_reg_id_posted = $this->input->post('pb_reg_id_modal');
        }

        if ($enc_pb_reg_id == $enc_pb_reg_id_posted)
        {
          $userfinalstrname = 'Member';
          $this->db->join('state_master', 'state_master.state_code=member_registration.state');
          $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
          $member_data = $this->master_model->getRecords('member_registration', array('regnumber' => $regnumber), 'firstname, middlename, lastname, address1, address2, address3, address4, district, city, email, mobile, office, pincode, state_master.state_name, institution_master.name');
          //echo '<pre>'; print_r($member_data); echo '</pre>'; //exit;

          if (count($member_data) > 0)
          {
            $username = $member_data[0]['firstname'] . ' ' . $member_data[0]['middlename'] . ' ' . $member_data[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

            $info_arr['to'] = $member_data[0]['email'];
          }

          if (base_url() == 'https://iibf.teamgrowth.net/' || base_url() == 'http://iibf.teamgrowth.net/')
          {
            $info_arr['to'] = 'sagar.matale@esds.co.in';
          }

          $info_arr['from'] = "logs@iibf.esdsconnect.com";
          //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
          if ($btnSubmitkyc == 'Approve')
          {
            $up_data['kyc_status'] = '1';
            $up_data['kyc_updated_date'] = date("Y-m-d H:i:s");
            $this->master_model->updateRecord('professional_banker_registrations', $up_data, array('pb_reg_id' => $pb_reg_id));

            //SEND MAIL TO MEMBER REGARDING KYC APPROVAl
            $info_arr['subject'] = "Professional Banker Application Approved - " . $regnumber;

            $final_str = '<div style="max-width:600px; width:100%; margin:20px auto;">
										<table style="width:100%; background:#FFFFCC;" cellspacing="5" cellpadding="5" border="1">
											<tbody>
												<tr><td colspan="2"><h2 style="margin: 10px 0; text-align: center; ">' . $exp_cert_data[0]['description'] . '</h2></td></tr>
												<tr>
													<td colspan="2">
														<p style="margin: 10px 0;text-align:justify;">Dear ' . $userfinalstrname . '<br><br>Your Registration has been successful and the Digital Certificate for the Professional Banker Qualification under the accomplished Track will be emailed to your registered email id shortly.<br><br>
														Regards,<br>IIBF Team</p>
													</td>
												</tr>												
											</tbody>
										</table>
									</div>';

            $info_arr['message'] = $final_str;
            //echo '<pre>'; print_r($info_arr); echo '</pre>'; exit;
            $this->Emailsending->mailsend($info_arr);

            $this->session->set_flashdata('success', 'You have successfully approved the KYC.');
            redirect(site_url('admin/kyc/Approver/professional_banker_kyc'));
          }
          else if ($btnSubmitkyc == 'Reject')
          {
            $up_data['kyc_status'] = '2';
            $up_data['remark'] = $remark = $this->input->post('remark');
            $up_data['kyc_updated_date'] = date("Y-m-d H:i:s");
            $this->master_model->updateRecord('professional_banker_registrations', $up_data, array('pb_reg_id' => $pb_reg_id));

            //insert rejection logs
            $add_log['pb_reg_id'] = $pb_reg_id;
            $add_log['exp_cert'] = $exp_cert_data[0]['exp_cert'];
            $add_log['action'] = 'KYC rejected by admin';
            $add_log['remark'] = $remark;
            $add_log['created_on'] = date('y-m-d H:i:s');
            $this->master_model->insertRecord('professional_banker_rejection_logs', $add_log, true);

            //SEND MAIL TO MEMBER REGARDING KYC REJECTION
            $info_arr['subject'] = "Professional Banker Application Rejected - " . $regnumber;

            $final_str = '<div style="max-width:600px; width:100%; margin:20px auto;">
										<table style="width:100%; background:#FFFFCC;" cellspacing="5" cellpadding="5" border="1">
											<tbody>
												<tr><td colspan="2"><h2 style="margin: 10px 0; text-align: center; ">' . $exp_cert_data[0]['description'] . '</h2></td></tr>
												<tr>
													<td colspan="2">
														<p style="margin: 10px 0;text-align:justify;">Dear ' . $userfinalstrname . '<br><br>"The Experience Certificate uploaded is currently under consideration at our end and you will be notified as soon as it is approved or rejected. We will like to reiterate that the uploaded document is to be preferably issued by the HR of your current/previous organisation with clear mention of time that you have spent there and at what capacity (i.e. Designation and Department). Any document other than this will be considered strictly on a case to case basis and the decision taken by IIBF will be final in this regard."<br><br>
														Regards,<br>IIBF Team</p>
													</td>
												</tr>												
											</tbody>
										</table>										
									</div>';

            $info_arr['message'] = $final_str;
            //echo '<pre>'; print_r($info_arr); echo '</pre>'; exit;
            $this->Emailsending->mailsend($info_arr);

            $this->session->set_flashdata('success', 'You have successfully rejected the KYC.');
            redirect(site_url('admin/kyc/Approver/professional_banker_kyc'));
          }
        }
        else
        {
          redirect(site_url('admin/kyc/Approver/professional_banker_kyc'));
        }
      }

      //echo '<pre>'; print_r($exp_cert_data); echo '</pre>'; exit;
      $data['exp_cert_data'] = $exp_cert_data;

      $rejection_logs = array();
      if (count($exp_cert_data) > 0)
      {
        $this->db->order_by('created_on', 'DESC');
        $rejection_logs = $this->master_model->getRecords('professional_banker_rejection_logs', array('pb_reg_id' => $exp_cert_data[0]['pb_reg_id']), 'log_id, exp_cert, action, remark, created_on');
      }
      $data['rejection_logs'] = $rejection_logs;
      //echo '<pre>'; print_r($rejection_logs); echo '</pre>'; exit;
      $this->load->view('admin/kyc/Approver/approver_professional_banker_edited_screen', $data);
    }
    else
    {
      redirect(site_url('admin/kyc/Approver/professional_banker_kyc'));
    }
  }

  function download_exp_cert($enc_pb_reg_id = 0)
  {
    $pb_reg_id = $enc_pb_reg_id;
    if ($enc_pb_reg_id != '0')
    {
      $pb_reg_id = base64_decode($enc_pb_reg_id);
    }

    $exam_code_arr = array(1021, 1022, 1023, 1024, 1025);
    $this->db->where_in('pbr.exam_code', $exam_code_arr);
    $exp_cert_data = $this->master_model->getRecords('professional_banker_registrations pbr', array('pbr.pb_reg_id' => $pb_reg_id, 'pbr.exam_period' => '997'), 'pbr.exp_cert');
    //echo $this->db->last_query();

    if (count($exp_cert_data) > 0)
    {
      $file_full_path = ('./uploads/professional_bankers/' . $exp_cert_data[0]['exp_cert']);
      $this->download_file($file_full_path, $exp_cert_data[0]['exp_cert']);
    }
    else
    {
      echo 'Invalid download request';
    }
  }

  function download_exp_cert_log($enc_log_id = 0)
  {
    $log_id = $enc_log_id;
    if ($enc_log_id != '0')
    {
      $log_id = base64_decode($enc_log_id);
    }

    $this->db->join('professional_banker_rejection_logs rl', 'rl.pb_reg_id = pbr.pb_reg_id', 'INNER');
    $exam_code_arr = array(1021, 1022, 1023, 1024, 1025);
    $this->db->where_in('pbr.exam_code', $exam_code_arr);
    $exp_cert_data = $this->master_model->getRecords('professional_banker_registrations pbr', array('pbr.exam_period' => '997', 'rl.log_id' => $log_id), 'rl.exp_cert');

    if (count($exp_cert_data) > 0)
    {
      $file_full_path = ('./uploads/professional_bankers/' . $exp_cert_data[0]['exp_cert']);
      $this->download_file($file_full_path, $exp_cert_data[0]['exp_cert']);
    }
    else
    {
      echo 'Invalid download request';
    }
  }

  function download_file($file_full_path = '', $file_name = '')
  {
    $this->load->helper('file');
    if ($file_full_path != '' && $file_name != '')
    {
      $mime = get_mime_by_extension($file_full_path);

      // Build the headers to push out the file properly.
      header('Pragma: public');     // required
      header('Expires: 0');         // no cache
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_full_path)) . ' GMT');
      header('Cache-Control: private', false);
      header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
      header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');  // Add the file name
      header('Content-Transfer-Encoding: binary');
      header('Content-Length: ' . filesize($file_full_path)); // provide file size
      header('Connection: close');
      readfile($file_full_path); // push it out
      exit();
    }
  }

  function getAllRec($select, $table, $where, $order_by = null) // GET ALL RECORDS WITH SELECT STRING
  {
    $q = "select $select from $table $where $order_by";
    $query = $this->db->query($q);
    return $query->result_array();
  }

  /*SCRIBE APPROVER FUNCTIONS : POOJA MANE 09-12-2022*/
  /* Date Filters to get scribe members from admin_benchmark_kyc_users */
  public function scribe_allocation_type()
  {

    //echo "scribe_allocation_type";die;
    // check allocation type
    $new_allocated_member_list = $this->master_model->getRecords("admin_scribe_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));
    //echo "<br><br>1=>".$this->db->last_query();
    if (count($new_allocated_member_list) > 0)
    {
      if ($new_allocated_member_list[0]['allotted_member_id'] == '')
      {
        //redirect(base_url().'admin/kyc/Approver/scribe_allocation_type');
        $this->load->view('admin/kyc/Approver/scribe_allocation_type');
      }
    }
    //$kyc_start_date=$this->config->item('kyc_start_date');
    $allocated_member_list = $members = array();
    $allocated_member_list = $this->master_model->getRecords("admin_scribe_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));

    //echo "<br><br>2=>".$this->db->last_query();die;

    //allocated_count
    if (count($allocated_member_list))
    {
      if (count($allocated_member_list) > 0)
      {
        $data['count'] = $allocated_member_list[0]['allocated_count'];
        $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      }
      //print_r($allocated_member_list);die;
      foreach ($arraid as $row)
      {
        $this->db->join('scribe_registration', 'scribe_registration.regnumber=scribe_member_kyc.regnumber', 'LEFT');
        $this->db->where('scribe_registration.scribe_kyc_edit', '0');
        $this->db->where('scribe_registration.remark', '1');
        $this->db->where('scribe_registration.scribe_kyc_status', '0');
        $this->db->where('scribe_registration.benchmark_disability', 'Y');
        $this->db->group_by('scribe_registration.regnumber');
        $members = $this->master_model->getRecords("scribe_member_kyc", array('scribe_member_kyc.regnumber' => $row, 'scribe_member_kyc.field_count' => '0'), '', array('kyc_id' => 'DESC'), '0', '1');

        //echo "<br><br>3=>".$this->db->last_query();

        $members_arr[]  = $members;
      }
      $emptylistmsg = ' ';
      $data['emptylistmsg']  = $emptylistmsg;
      $data['result'] = call_user_func_array('array_merge', $members_arr);
      /* Start Code To Get Recent Allotted Member Total Count */
      $pagination_total_count = $this->master_model->getRecords("admin_scribe_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");

      //echo "<br><br>4=>".$this->db->last_query();DIE;
      //exit;
      if (!empty($pagination_total_count))
      {
        foreach ($pagination_total_count[0] as $k => $value)
        {
          if ($k == "pagination_total_count")
          {
            $data['totalRecCount'] = $value;
          }
          if ($k == "original_allotted_member_id")
          {
            $data['original_allotted_member_id'] = $value;
          }
        }
      }
      /* Close Code To Get Recent Allotted Member Total Count */
      $this->load->view('admin/kyc/Approver/scribe_approver_edited_list', $data);
    }
    else
    {
      $this->load->view('admin/kyc/Approver/scribe_allocation_type');
    }
  }

  /* Get Member from admin_scribe_kyc_users */
  public function scribe_approver_edited_list()
  {
    echo "scribe_approver_edited_list"; //die;
    $tilte = $type = '';
    $description = $emptylistmsg = '';
    $allocates_arr = $members_arr = $result = $array = array();
    $data['result'] = array();
    $regstr = $searchText = $searchBy = '';
    $searchBy_regtype = '';
    $today = date('Y-m-d H:i:s');
    $per_page = 20;
    $last = 19;
    $start = 0;
    $list_type = 'New';
    $page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
    if ($this->input->post('regnumber') != '')
    {
      $searchBy = $this->input->post('regnumber');
    }
    $form_start_date = $form_end_date = '';
    if ($this->input->post('form_start_date') != '')
    {
      $form_start_date = $this->input->post('form_start_date');
    }

    if ($this->input->post('form_end_date') != '')
    {
      $form_end_date = $this->input->post('form_end_date');
    }
    $data['reg_no'] = ' ';
    if ($page != 0)
    {
      $start = $page - 1;
    }
    $allocates = array();

    //get  all  user loging today 
    if (isset($form_start_date) && isset($form_end_date))
    {

      //$type=$_POST['selectby'];
      $kyc_data = $this->master_model->getRecords("admin_scribe_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_type' => 'approver'), 'original_allotted_member_id');
      //echo "<br>SQL 1 =>".$this->db->last_query(); //exit;;
      $allocatedmemberarr = array();
      if (count($kyc_data) > 0)
      {
        foreach ($kyc_data as $row)
        {
          $allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
        }
      }
      $member_kyc = $this->db->query("SELECT regnumber,kyc_id
				FROM scribe_member_kyc
				WHERE kyc_id IN (
				SELECT MAX(kyc_id)
				FROM scribe_member_kyc
				GROUP BY regnumber
				) and (kyc_state = 2 OR kyc_state = 1) AND field_count > 0");
      //echo "<br>SQL 2 =>".$this->db->last_query(); exit;
      $recommendedmemberarr = array();
      if ($member_kyc->num_rows() > 0)
      {
        foreach ($member_kyc->result_array()  as $row)
        {
          $recommendedmemberarr[] = $row['regnumber'];
        }
      }
      $data_array = array();
      if (count($allocatedmemberarr) > 0)
      {
        // get the column data in a single array
        $data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
        /*if(count($data_array) > 0)
						{
						$this->db->where_not_in('member_kyc.regnumber',array_map('stripslashes', $data_array));
					}*/
      }
      // merge allocated member array with recommended members array
      $data_array = array_merge($data_array, $recommendedmemberarr);
      if (count($data_array) > 0)
      {
        $this->db->where_not_in('scribe_member_kyc.regnumber', array_map('stripslashes', $data_array));
      }
      $this->db->join('scribe_registration', 'scribe_registration.regnumber= scribe_member_kyc.regnumber', 'LEFT');
      $this->db->where('scribe_registration.scribe_kyc_status', '0');
      $this->db->where('scribe_member_kyc.kyc_status', '0');
      $this->db->where('scribe_registration.remark', '1');
      //$this->db->where('scribe_registration.registrationtype',$type);	
      $this->db->where('scribe_kyc_edit', '0');
      $this->db->where('((benchmark_disability = "Y" AND DATE(created_on)>="' . $form_start_date . '" 
				AND DATE(created_on)<="' . $form_end_date . '") OR 
				(scribe_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(scribe_edit_date)>="' . $form_start_date . '" 
				AND DATE(scribe_edit_date)<="' . $form_end_date . '"))');
      $this->db->group_by('scribe_registration.regnumber');
      $r_list = $this->master_model->getRecords("scribe_member_kyc", array('field_count' => 0, 'kyc_state' => 1), 'MAX(kyc_id),scribe_member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,recommended_date,email,record_source,scribe_member_kyc.kyc_status,scribe_member_kyc.mem_idproof, scribe_member_kyc.mem_declaration, scribe_member_kyc.mem_visually,scribe_member_kyc.mem_orthopedically,scribe_member_kyc.mem_cerebral 
				,remark,field_count', array('kyc_id' => 'DESC'), $start, $per_page);
      // echo "<br>SQL 3 =>".$this->db->last_query(); //exit;
      // 		echo '<pre>';
      // 	print_r($r_list);
      // exit;
      $today = date("Y-m-d H:i:s");
      $row_count = $this->master_model->getRecordCount("admin_scribe_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New'));
      //echo "<br>SQL 4 =>".$this->db->last_query(); exit;
      if ($row_count == 0)
      {
        $regstr = '';
        foreach ($r_list  as $row)
        {
          $allocates_arr[] = $row['regnumber'];
        }
        if (count($allocates_arr) > 0)
        {
          $regstr = implode(',', $allocates_arr);
        }
        //print_r($regstr);
        //exit;
        if ($regstr != '')
        {
          $insert_data = array(
            'user_type'      => $this->session->userdata('role'),
            'user_id'        => $this->session->userdata('kyc_id'),
            'allotted_member_id'  => $regstr,
            'original_allotted_member_id'  => $regstr,
            'allocated_count'     => count($allocates_arr),
            'allocated_list_count'     => '1',
            'date'                  => $today,
            'list_type'             => 'New',
            'pagination_total_count ' => count($allocates_arr)
          );
          $this->master_model->insertRecord('admin_scribe_kyc_users', $insert_data);
          //log activity 
          $tilte = 'Approver  KYC  member list allocation';
          $description = 'Approver has allocated ' . count($allocates_arr) . ' member';
          $user_id = $this->session->userdata('kyc_id');
          $this->KYC_Log_model->scribe_create_log($tilte, $user_id, '', '', $description);
        }
      }
    }

    $allocated_member_list = $this->master_model->getRecords("admin_scribe_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
    if (count($allocated_member_list) > 0)
    {
      $data['count'] = $allocated_member_list[0]['allocated_count'];
    }
    else
    {
      $data['count'] = 0;
    }
    //echo $this->db->last_query(); exit;
    //print_r(count($allocated_member_list));die;
    if (count($allocated_member_list) > 0)
    {
      $arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
      //$data['result'] = $members;
      //$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
      if (count($arraid) > 0)
      {
        if ($form_start_date != '' && $form_end_date != '')
        {
          if ($searchBy != '' && $form_start_date != '' && $form_end_date != '')
          {   //echo "<br>IF 1";
            //$this->db->join('scribe_member_kyc','scribe_member_kyc.regnumber=scribe_member_kyc.regnumber','LEFT');
            $this->db->where('regnumber', $searchBy);
            $this->db->where('((benchmark_disability = "Y" AND DATE(created_on)>="' . $form_start_date . '" 
							AND DATE(created_on)<="' . $form_end_date . '") OR 
							(scribe_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(scribe_edit_date)>="' . $form_start_date . '" 
							AND DATE(scribe_edit_date)<="' . $form_end_date . '"))');
            $this->db->where('scribe_kyc_status', '0');
            $members = $this->master_model->getRecords("scribe_registration", array('remark' => '1'));
          }
          else if ($searchBy != '')
          {  //echo "<br>eles if 2";
            $arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
            //$this->db->join('scribe_member_kyc','scribe_member_kyc.regnumber=scribe_member_kyc.regnumber','LEFT');
            $this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
            $this->db->where('regnumber', $searchBy);
            $this->db->where('scribe_kyc_status', '0');
            $members = $this->master_model->getRecords("scribe_registration", array('remark' => '1'));
          }
          else if ($form_start_date != '' && $form_end_date != '')
          { //echo "<br>eles if 3";
            //$this->db->join('scribe_member_kyc','scribe_member_kyc.regnumber=scribe_member_kyc.regnumber','LEFT');
            $this->db->where('((benchmark_disability = "Y" AND DATE(created_on)>="' . $form_start_date . '" 
							AND DATE(created_on)<="' . $form_end_date . '") OR 
							(scribe_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(scribe_edit_date)>="' . $form_start_date . '" 
							AND DATE(scribe_edit_date)<="' . $form_end_date . '"))');
            $this->db->where('scribe_kyc_status', '0');
            $members = $this->master_model->getRecords("scribe_registration", array('remark' => '1'));
          }
          if (count($members) > 0)
          {
            foreach ($members as $row)
            {
              $members_arr[][] = $row;
            }
          }
        }
        else
        {
          //default allocation list for 100 member
          foreach ($arraid as $row)
          {
            $this->db->join('scribe_registration', 'scribe_registration.regnumber=scribe_member_kyc.regnumber', 'LEFT');
            $this->db->where('scribe_registration.remark', '1');
            //$this->db->where('scribe_registration.isdeleted', '0');
            $this->db->where('scribe_member_kyc.kyc_status', '0');
            $this->db->where('scribe_registration.scribe_kyc_status', '0');
            $this->db->group_by('scribe_registration.regnumber');
            $members = $this->master_model->getRecords("scribe_member_kyc", array('scribe_member_kyc.regnumber' => $row, 'scribe_member_kyc.field_count' => '0', 'scribe_member_kyc.kyc_state' => 1), '', array('kyc_id' => 'DESC'), '0', '1');
            $members_arr[]  = $members;
          }
        }
      }
      //echo "<br>".$this->db->last_query();
      $data['result'] = call_user_func_array('array_merge', $members_arr);
      //echo '<pre>';
      //print_r($data['result']);
      //exit;
    }
    $total_row = 200;
    $url = base_url() . "admin/kyc/Approver/scribe_approver_edited_list/";
    $config = pagination_init($url, $total_row, $per_page, 2);
    $this->pagination->initialize($config);
    $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Search</li>
			</ol>';
    $str_links = $this->pagination->create_links();
    //var_dump($str_links);
    $data["links"] = $str_links;
    if (($start + $per_page) > $total_row)
      $end_of_total = $total_row;
    else
      $end_of_total = $start + $per_page;
    if ($total_row)
      $data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
    else
      $data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';
    $data['index'] = $start + 1;
    $emptylistmsg = ' No records available...!!<br />
			<a href=' . base_url() . 'admin/kyc/Approver/scribe_allocation_type/>Back</a>';
    /* Start Code To Get Recent Allotted Member Total Count */
    $pagination_total_count = $this->master_model->getRecords("admin_scribe_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
    if (!empty($pagination_total_count))
    {
      foreach ($pagination_total_count[0] as $k => $value)
      {
        if ($k == "pagination_total_count")
        {
          $data['totalRecCount'] = $value;
        }
        if ($k == "original_allotted_member_id")
        {
          $data['original_allotted_member_id'] = $value;
        }
      }
    }
    /* Close Code To Get Recent Allotted Member Total Count */
    $data['emptylistmsg']  = $emptylistmsg;
    //		redirect(base_url().'admin/kyc/Approver/approver_edited_list');	
    //$this->db->distinct('registrationtype');
    $data['mem_type'] = $this->master_model->getRecords('scribe_registration', array());
    $this->load->view('admin/kyc/Approver/scribe_approver_edited_list', $data);
  }

  /* Scribe KYC recommend and kyc complete start */
  function scribe_approver_edited_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
  {
    //echo"scribe_approver_edited_member";die;
    if ($regnumber)
    {
      //echo'regnumber';DIE;
      $oldfilepath = $file_path = $photo_file = '';
      $state = $next_id = $success = $error = $description = '';
      $data['result'] = $name = $update_data = $old_user_data = $member_kyc_lastest_record = $sql = array();
      $new_arrayid = $noarray = array();
      $today = $date = date('Y-m-d H:i:s');
      //$registrationtype = '';
      $data['reg_no'] = ' ';
      $field_count = 0;

      //on recommendation submit
      if (isset($_POST['btnSubmitRecmd']))
      {
        //echo'btnSubmitRecmd';DIE;
        $select = 'regnumber,scribe_uid,email,idproofphoto,declaration_img,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,mobile_scribe,email';
        $data = $this->master_model->getRecords('scribe_registration', array(
          'regnumber' => $regnumber,
          'remark' => '1',
          'scribe_kyc_status' => '0'
        ), $select);
        //echo $this->db->last_query();die;
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }
        $regnumber = $data[0]['regnumber'];
        $scribe_uid = $data[0]['scribe_uid'];
        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {
            $check_arr[] = $cbox;
          }
        }

        $regnumber = $data[0]['regnumber'];
        $scribe_uid = $_POST['scribe_uid'];
        $idproofphoto = $data[0]['idproofphoto'];
        $declaration_img = $data[0]['declaration_img'];
        $vis_imp_cert_img = $data[0]['vis_imp_cert_img'];
        $orth_han_cert_img = $data[0]['orth_han_cert_img'];
        $cer_palsy_cert_img = $data[0]['cer_palsy_cert_img'];

        $msg = 'Scribe Edit your profile as :-';
        if (count($check_arr) > 0)
        {
          $folder_name = date('d-m-Y');
          $new_img_name = date('H:i:s');

          if (in_array('idproof_checkbox', $check_arr))
          {
            $idproof_checkbox = '1';
          }
          else
          {
            //print_r($data[0]['idproofphoto']);DIE;
            if ($data[0]['idproofphoto'] != '')
            {
              $idproof_checkbox = '0';
              $field_count++;
              $update_data[] = 'Idproof';
              $msg .= 'Idproof,';
              if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
              }

              if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/idproof'))
                {
                  mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/idproof', 0777, true);
                }
              }


              $original_file = base_url() . "uploads/scribe/idproof/" . $idproofphoto;
              /*print_r($original_file);die;*/
              $newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/idproof/idproof_' . $regnumber . '_' . $new_img_name . '.jpg';

              // $newfile = getcwd() . '/uploads/kyc_img/' . $folder_name . '/photo/p_' . $regnumber . '_' . $new_img_name . '.jpg';
              $arrContextOptions = array(
                "ssl" => array(
                  "verify_peer" => false,
                  "verify_peer_name" => false,
                ),
              );
              copy($original_file, $newfile, stream_context_create($arrContextOptions));
              // die;
            }
            else
            {
              $idproof_checkbox = '3';
            }
          }
          if (in_array('declaration_checkbox', $check_arr))
          {
            $declaration_checkbox = '1';
          }
          else
          {
            if ($data[0]['declaration_img'] != '')
            {
              $declaration_checkbox = '0';
              $field_count++;
              $update_data[] = 'Declaration';
              $msg .= 'Declaration,';
              if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
              }

              if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/declaration'))
                {
                  mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/declaration', 0777, true);
                }
              }
              $original_file = base_url() . "uploads/scribe/declaration/" . $declaration_img;

              $newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/declaration/declaration_' . $regnumber . '_' . $new_img_name . '.jpg';

              $arrContextOptions = array(
                "ssl" => array(
                  "verify_peer" => false,
                  "verify_peer_name" => false,
                ),
              );
              copy($original_file, $newfile, stream_context_create($arrContextOptions));
            }
            else
            {
              $declaration_checkbox = '3';
            }
          }
          if (in_array('visually_checkbox', $check_arr))
          {
            $visually_checkbox = '1';
          }
          else
          {
            if ($data[0]['vis_imp_cert_img'] != '')
            {
              $visually_checkbox = '0';
              $field_count++;
              $update_data[] = 'Visually';
              $msg .= 'Visually,';
              if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
              }

              if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability'))
                {
                  mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability', 0777, true);
                }
              }
              $original_file = base_url() . "uploads/scribe/disability/" . $vis_imp_cert_img;
              /*print_r($original_file);die;*/
              $newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability/vis_imp_cert_' . $regnumber . '_' . $new_img_name . '.jpg';
              $arrContextOptions = array(
                "ssl" => array(
                  "verify_peer" => false,
                  "verify_peer_name" => false,
                ),
              );
              copy($original_file, $newfile, stream_context_create($arrContextOptions));
            }
            else
            {
              $visually_checkbox = '3';
            }
          }
          if (in_array('orthopedically_checkbox', $check_arr))
          {
            $orthopedically_checkbox = '1';
          }
          else
          {
            if ($data[0]['orth_han_cert_img'] != '')
            {
              $orthopedically_checkbox = '0';
              $field_count++;
              $update_data[] = 'Orthopedically';
              $msg .= 'Orthopedically,';
              if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
              }
              if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability'))
                {
                  mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability', 0777, true);
                }
              }
              $original_file = base_url() . "uploads/scribe/disability/" . $orth_han_cert_img;
              $newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability/orth_han_cert_' . $regnumber . '_' . $new_img_name . '.jpg';

              $arrContextOptions = array(
                "ssl" => array(
                  "verify_peer" => false,
                  "verify_peer_name" => false,
                ),
              );
              copy($original_file, $newfile, stream_context_create($arrContextOptions));
              //copy($original_file, $newfile);
            }
            else
            {
              $orthopedically_checkbox = '3';
            }
          }
          if (in_array('cerebral_checkbox', $check_arr))
          {
            $cerebral_checkbox = '1';
          }
          else
          {

            if ($data[0]['cer_palsy_cert_img'] != '')
            {
              $cerebral_checkbox = '0';
              $field_count++;
              $update_data[] = 'Cerebral';
              $msg .= 'Cerebral';
              if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
              }
              if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name))
              {
                if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability'))
                {
                  mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability', 0777, true);
                }
              }
              $original_file = base_url() . "uploads/scribe/disability/" . $cer_palsy_cert_img;
              $newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability/cer_palsy_cert_' . $regnumber . '_' . $new_img_name . '.jpg';

              //echo $original_file;echo "<br>";
              //echo $newfile;die;
              $arrContextOptions = array(
                "ssl" => array(
                  "verify_peer" => false,
                  "verify_peer_name" => false,
                ),
              );
              copy($original_file, $newfile, stream_context_create($arrContextOptions));
            }
            else
            {
              $cerebral_checkbox = '3';
            }
          }
        }
        else
        {
          if ($data[0]['idproofphoto'] != '')
          {
            $idproof_checkbox = '0';
            $msg .= 'Idproof';
            $field_count++;
            $update_data[] = 'Idproof';
          }
          else
          {
            $idproof_checkbox = '3';
          }

          if ($data[0]['declaration_img'] != '')
          {
            $declaration_checkbox = '0';
            $msg .= 'Declaration';
            $field_count++;
            $update_data[] = 'Declaration';
          }
          else
          {
            $declaration_checkbox = '3';
          }

          if ($data[0]['vis_imp_cert_img'] != '')
          {
            $visually_checkbox = '0';
            $msg .= 'Visually';
            $field_count++;
            $update_data[] = 'Visually';
          }
          else
          {
            $visually_checkbox = '3';
          }

          if ($data[0]['orth_han_cert_img'] != '')
          {
            $orthopedically_checkbox = '0';
            $msg .= 'Orthopedically';
            $field_count++;
            $update_data[] = 'Orthopedically';
          }
          else
          {
            $orthopedically_checkbox = '3';
          }

          if ($data[0]['cer_palsy_cert_img'] != '')
          {
            $cerebral_checkbox = '0';
            $msg .= 'Cerebral';
            $field_count++;
            $update_data[] = 'Cerebral';
          }
          else
          {
            $cerebral_checkbox = '3';
          }
        }
        //print_r($data[0]['email']);die;
        $email = $data[0]['email'];


        if ($visually_checkbox == '1' && $orthopedically_checkbox == '1' && $cerebral_checkbox == '1')
        {
          $this->session->set_flashdata('error', 'Please uncheck atleast one checkbox!!');
        }
        else
        {

          //die('else');
          $select = 'namesub,scribe_uid,firstname,middlename,lastname,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,idproofphoto,declaration_img,mobile_scribe,email';
          $old_user_data = $this->master_model->getRecords('scribe_registration', array(
            'regnumber' => $regnumber,
            //'scribe_uid' => $scribe_uid,
            'remark' => '1'
          ), $select);

          $userdata = $this->master_model->getRecords("scribe_registration", array(
            'regnumber' => $regnumber,
            //'scribe_uid' => $scribe_uid,
            'remark' => '1'
          ));

          $insert_data = array(
            //'scribe_uid' => $data[0]['scribe_uid'],
            'regnumber' => $data[0]['regnumber'],
            'mem_idproof' => $idproof_checkbox,
            'mem_declaration' => $declaration_checkbox,
            'mem_visually' => $visually_checkbox,
            'mem_orthopedically' => $orthopedically_checkbox,
            'mem_cerebral' => $cerebral_checkbox,
            'field_count' => $field_count,
            'old_data' => serialize($old_user_data),
            'kyc_status' => '0',
            'kyc_state' => '1',
            'recommended_by' => $this->session->userdata('kyc_id'),
            'user_type' => $this->session->userdata('role'),
            'recommended_date' => $today,
            'record_source' => 'New'
          );
          // insert the record and get latest  kyc_id
          $last_insterid = $this->master_model->insertRecord('scribe_member_kyc', $insert_data, true);
          //echo $this->db->last_query();die;
          $memberdata = $this->master_model->getRecords("member_registration", array(
            'regnumber' => $regnumber,
            'isactive' => '1'
          ));
          include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
          $key = $this->config->item('pass_key');
          $aes = new CryptAES();
          $aes->set_key(base64_decode($key));
          $aes->require_pkcs5();
          $userpass = '';
          if (count($memberdata) > 0)
          {
            $userpass = $aes->decrypt($memberdata[0]['usrpassword']);
          }

          $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
          $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
          $msg = implode(',', $update_data);
          $emailerstr = $this->master_model->getRecords('emailer', array(
            'emailer_name' => 'scribe_recommendation_email'
          ));
          $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
          $newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
          $newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
          $newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);

          $final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);
          //print_r($final_str);die;
          $info_arr = array(
            //'to'=> "kyciibf@gmail.com",
            'to' => 'Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
            'from' => $emailerstr[0]['from'],
            'subject' => $emailerstr[0]['subject'],
            'message' => $final_str
          );

          if ($this->Emailsending->mailsend($info_arr))
          {
            $this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');
            $select = 'namesub,scribe_uid,firstname,middlename,lastname,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,idproofphoto,declaration_img,mobile_scribe,email';
            $old_data = $this->master_model->getRecords("scribe_registration", array(
              'regnumber' => $regnumber,
              //'scribe_uid' => $scribe_uid,
              'remark' => '1'
            ), $select);
            //echo $this->db->last_query();die;
            $log_desc['old_data'] = $old_data;
            $log_desc['inserted_data'] = $insert_data;
            $description = serialize($log_desc);
            $this->KYC_Log_model->scribe_create_log('scribe Member recommend', $this->session->userdata('kyc_id'), $last_insterid, /*$scribe_uid,*/ $regnumber, $description);
            //echo $this->db->last_query();die;
            // email log
            $this->KYC_Log_model->scribe_email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', /*$scribe_uid,*/ $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            //echo $this->db->last_query();die;

            // make recommended fields empty  -
            if (in_array('Idproof', $update_data))
            {
              $updatedata['idproofphoto'] = '';
              $updatedata['idproof'] = '';
              $oldfilepath = "uploads/idproof/" . $row['idproofphoto'];
              $noarray = explode('/', $oldfilepath);
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_i_' . $noarray[1];
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->scribe_create_log('Recommended scribe Idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, /*$scribe_uid,*/ $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->scribe_create_log('fail to delete Scribe Idproof', $this->session->userdata('kyc_id'), 0, /*$scribe_uid,*/ $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->scribe_create_log('member scribe Idproof not found', $this->session->userdata('kyc_id'), 0,  /*$scribe_uid,*/ $regnumber, $description);
              }
            }
            if (in_array('Declaration', $update_data))
            {
              $updatedata['declaration_img'] = '';
              $updatedata['declaration'] = '';
              $oldfilepath = "uploads/disability/" . $row['declaration_img'];
              $noarray = explode('/', $oldfilepath);
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_v_' . $noarray[1];
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->scribe_create_log('Recommended scribe Declaration Rename ', $this->session->userdata('kyc_id'), $last_insterid, /*$scribe_uid,*/ $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->scribe_create_log('fail to delete Scribe Declaration', $this->session->userdata('kyc_id'), 0, /*$scribe_uid,*/ $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->scribe_create_log('member scribe Declaration not found', $this->session->userdata('kyc_id'), 0,  /*$scribe_uid,*/ $regnumber, $description);
              }
            }
            if (in_array('Visually', $update_data))
            {
              $updatedata['vis_imp_cert_img'] = '';
              $updatedata['visually_impaired'] = '';
              $oldfilepath = "uploads/disability/" . $row['vis_imp_cert_img'];
              $noarray = explode('/', $oldfilepath);
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_v_' . $noarray[1];
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->scribe_create_log('Recommended scribe Visually Rename ', $this->session->userdata('kyc_id'), $last_insterid, /*$scribe_uid,*/ $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->scribe_create_log('fail to delete Scribe Visually', $this->session->userdata('kyc_id'), 0, /*$scribe_uid,*/ $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->scribe_create_log('member scribe Visually not found', $this->session->userdata('kyc_id'), 0,  /*$scribe_uid,*/ $regnumber, $description);
              }
            }
            if (in_array('Visually', $update_data))
            {
              $updatedata['vis_imp_cert_img'] = '';
              $updatedata['visually_impaired'] = '';
              $oldfilepath = "uploads/disability/" . $row['vis_imp_cert_img'];
              $noarray = explode('/', $oldfilepath);
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_v_' . $noarray[1];
                $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->scribe_create_log('Recommended scribe Visually Rename ', $this->session->userdata('kyc_id'), $last_insterid, /*$scribe_uid,*/ $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->scribe_create_log('fail to delete Scribe Visually', $this->session->userdata('kyc_id'), 0, /*$scribe_uid,*/ $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->scribe_create_log('member scribe Visually not found', $this->session->userdata('kyc_id'), 0,  /*$scribe_uid,*/ $regnumber, $description);
              }
            }
            if (in_array('Orthopedically', $update_data))
            {
              $updatedata['orth_han_cert_img'] = '';
              $updatedata['orthopedically_handicapped'] = '';
              $oldfilepath = "uploads/disability/" . $row['orth_han_cert_img'];
              $noarray = explode('/o_', $oldfilepath);
              $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_o_' . $noarray[1];
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->scribe_create_log('Recommended scribe Orthopedically rename', $this->session->userdata('kyc_id'), $last_insterid,  /*$scribe_uid,*/ $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->scribe_create_log('fail to delete scribe Orthopedically', $this->session->userdata('kyc_id'), 0, /*$scribe_uid,*/ $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->scribe_create_log('member scribe Orthopedically not found ', $this->session->userdata('kyc_id'), 0, /*$scribe_uid,*/ $regnumber, $description);
              }
            }
            if (in_array('Cerebral', $update_data))
            {
              $updatedata['cer_palsy_cert_img'] = '';
              $updatedata['cerebral_palsy'] = '';
              $oldfilepath = "uploads/disability/" . $row['cer_palsy_cert_img'];
              $noarray = explode('/c_', $oldfilepath);
              $description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
              if (isset($noarray[1]))
              {
                $file_path = implode('/', explode('/', $oldfilepath, -1));
                $photo_file = 'k_c_' . $noarray[1];
                if (@rename($oldfilepath, $file_path . '/' . $photo_file))
                {
                  $this->KYC_Log_model->scribe_create_log('Recommended scribe cerebral Rename ', $this->session->userdata('kyc_id'), $last_insterid, /*$scribe_uid,*/ $regnumber, $description);
                }
                else
                {
                  $this->KYC_Log_model->scribe_create_log('fail to delete scribe cerebral ', $this->session->userdata('kyc_id'), 0, /*$scribe_uid,*/ $regnumber, $description);
                }
              }
              else
              {
                $this->KYC_Log_model->scribe_create_log('member scribe cerebral not found ', $this->session->userdata('kyc_id'), 0, /*$scribe_uid,*/ $regnumber, $description);
              }
            }
            if (!empty($updatedata))
            {
              $this->db->where('remark', '1');
              $this->master_model->updateRecord('scribe_registration', $updatedata, array(
                'regnumber' => $regnumber,
                'scribe_uid' => $scribe_uid
              ));
            }

            $member = $this->master_model->getRecords("admin_scribe_kyc_users", array(
              'DATE(date)' => date('Y-m-d'),
              'user_id' => $this->session->userdata('kyc_id')
            ));

            $arrayid = explode(',', $member[0]['allotted_member_id']);
            $index = array_search($regnumber, $arrayid, true);
            // get next record
            $currentid = $index;
            $nextid = $currentid + 1;
            if (array_key_exists($nextid, $arrayid))
            {
              $next_id = $arrayid[$nextid];
            }
            else
            {
              $next_id = $arrayid[0];
            }
            // end of next record
            // unset the  current id index
            unset($arrayid[$index]);
            if (count($arrayid) > 0)
            {
              foreach ($arrayid as $row)
              {
                $new_arrayid[] = $row;
              }
            }
            if (count($new_arrayid) > 0)
            {
              $regstr = implode(',', $new_arrayid);
            }
            else
            {
              $regstr = '';
              $next_id = '';
            }
            $update_data = array(
              'allotted_member_id' => $regstr
            );
            $this->db->where('DATE(date)', date('Y-m-d'));
            $this->db->where('list_type', 'New');
            $this->master_model->updateRecord('admin_scribe_kyc_users', $update_data, array(
              'user_id' => $this->session->userdata('kyc_id')
            ));

            /* Start Code To Showing Count On Member List*/
            if ($next_id == '')
            {
              $next_id = 0;
            }
            // $totalRecCount=$this->get_App_allocation_type_cnt();
            if ($srno > $totalRecCount)
            {
              // $srno=$totalRecCount;
              $srno = 1;
            }
            else
            {
              $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
              $arr = array_slice($original_allotted_Arr, -$totalRecCount);
              $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
              $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
              $memberNo = $next_id;
              $updated_list_index = array_search($memberNo, $reversedArr_list);
              $srno = $updated_list_index;
            }

            redirect(base_url() . 'admin/kyc/Approver/scribe_approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
            /* Close Code To Showing Count On Member List*/
          }
        }
      }
      // kyc submit
      if (isset($_POST['btnSubmitkyc']))
      {
        //echo'btnSubmitkyc';DIE;
        if (isset($_POST['cbox']))
        {
          $name = $this->input->post('cbox');
        }
        //print_r($_POST);die;
        $check_arr = array();
        if (count($name) > 0)
        {
          foreach ($name as $cbox)
          {
            $check_arr[] = $cbox;
          }
        }
        $regnumber = $this->input->post('regnumber');
        $scribe_uid = $this->input->post('scribe_uid');

        $this->db->where('regnumber', $regnumber);
        $this->db->where('remark', '1');
        $this->db->where('scribe_uid', $scribe_uid);
        $member_regtype = $this->master_model->getRecords('scribe_registration', '', 'vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,idproofphoto,declaration_img,mobile_scribe,email');

        /*echo "<pre>";
					
					echo "<br>".$member_regtype[0]['vis_imp_cert_img'];
					echo "<br>".$member_regtype[0]['orth_han_cert_img'];
					echo "<br>".$member_regtype[0]['cer_palsy_cert_img'];
					print_r($name);
					print_r($check_arr);
					exit;*/
        //print_r(count($check_arr));die;
        if (count($check_arr) == 5 && (in_array('visually_checkbox', $check_arr) || in_array('orthopedically_checkbox', $check_arr) || in_array('cerebral_checkbox', $check_arr)))
        //if($member_regtype[0]['vis_imp_cert_img'] != '' $member_regtype[0]['orth_han_cert_img'] )
        {

          $new_arrayid = $members = array();
          $status = '0';
          $state = '1';
          $date = date("Y-m-d H:i:s");
          // $this->db->where('recommended_date',$date);
          $this->db->where('regnumber', $regnumber);
          $this->db->where('scribe_uid', $scribe_uid);
          $member_kyc_details = $this->master_model->getRecords('scribe_member_kyc');
          if (isset($_POST['cbox']))
          {
            $name = $this->input->post('cbox');
          }
          //$regnumber=$data[0]['regnumber'];

          $check_arr = array();
          if (count($name) > 0)
          {
            foreach ($name as $cbox)
            {
              $check_arr[] = $cbox;
            }
          }
          $msg = 'scribe Edit your profile as :-';

          if (in_array('idproof_checkbox', $check_arr))
          {
            $idproof_checkbox = '1';
          }
          else
          {
            if ($member_regtype[0]['idproofphoto'] != '')
            {
              $idproof_checkbox = '0';
              $msg .= 'Idproof,';
            }
            else
            {
              $idproof_checkbox = '3';
            }
          }
          //print_r($idproof_checkbox);die;
          if (in_array('declaration_checkbox', $check_arr))
          {
            $declaration_checkbox = '1';
          }
          else
          {
            if ($member_regtype[0]['declaration_img'] != '')
            {
              $declaration_checkbox = '0';
              $msg .= 'Declaration,';
            }
            else
            {
              $declaration_checkbox = '3';
            }
          }

          if (in_array('visually_checkbox', $check_arr))
          {
            $visually_checkbox = '1';
          }
          else
          {
            if ($member_regtype[0]['vis_imp_cert_img'] != '')
            {
              $visually_checkbox = '0';
              $msg .= 'Visually,';
            }
            else
            {
              $visually_checkbox = '3';
            }
          }

          if (in_array('orthopedically_checkbox', $check_arr))
          {
            $orthopedically_checkbox = '1';
          }
          else
          {
            if ($member_regtype[0]['orth_han_cert_img'] != '')
            {
              $orthopedically_checkbox = '0';
              $msg .= 'Orthopedically';
            }
            else
            {
              $orthopedically_checkbox = '3';
            }
          }

          if (in_array('cerebral_checkbox', $check_arr))
          {
            $cerebral_checkbox = '1';
          }
          else
          {
            if ($member_regtype[0]['cer_palsy_cert_img'] != '')
            {
              $cerebral_checkbox = '0';
              $msg .= 'Cerebral_checkbox';
            }
            else
            {
              $cerebral_checkbox = '3';
            }
          }
          //print_r($scribe_uid);die;
          // $email=$data[0]['email'];
          $update_data = array(
            'mem_idproof' => $idproof_checkbox,
            'mem_declaration' => $declaration_checkbox,
            'mem_visually' => $visually_checkbox,
            'mem_orthopedically' => $orthopedically_checkbox,
            'mem_cerebral' => $cerebral_checkbox,
            'kyc_status' => '1',
            'kyc_state' => 3,
            'approved_by' => $this->session->userdata('kyc_id'),
            'approved_date' => $today,
            // 'record_source'			=>'Edit'
          );
          // query to update the latest record of the regnumber
          $sql = 'kyc_id in (SELECT max(kyc_id) FROM scribe_member_kyc GROUP BY regnumber )';
          $this->db->where($sql);
          $member_kyc_lastest_record = $this->master_model->getRecords("scribe_member_kyc", array(
            'regnumber' => $regnumber
            //'scribe_uid'=> $scribe_uid
          ), 'regnumber,kyc_state,kyc_id', array(
            'kyc_id' => 'DESC'
          ));
          // echo $this->db->last_query();echo "<br>";
          // echo $this->db->last_query();
          // print_r($member_kyc_lastest_record );exit;
          $this->db->where('remark', '1');
          $this->master_model->updateRecord('scribe_registration', array(
            'scribe_kyc_status' => '1'
          ), array(
            /*'scribe_uid' => $scribe_uid,*/
            'regnumber' => $regnumber,
          ));
          // echo $this->db->last_query();echo "<br>";
          // echo "<pre>";
          // 	print_r($update_data);
          // 	exit;

          // $this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber'],'kyc_id'=>$member_kyc_lastest_record[0]['kyc_id']));
          $this->db->where('kyc_id', $member_kyc_lastest_record[0]['kyc_id']);
          $this->master_model->updateRecord('scribe_member_kyc', $update_data, array(
            'regnumber' => $member_kyc_lastest_record[0]['regnumber']
            //'scribe_uid' => $scribe_uid
          ));
          /*reset the dowanload count*/
          //print_r($scribe_uid);//die;
          //echo $this->db->last_query();die;
          /*$where1 = array(
						'member_number' => $regnumber
						);
						$this->master_model->updateRecord('member_idcard_cnt', array(
						'card_cnt' => '0'
						) , $where1);*/

          $last_insterid = $this->master_model->getRecords("scribe_member_kyc", array(
            //'scribe_uid' => $scribe_uid,
            'regnumber' => $regnumber,
            //'scribe_uid' => $scribe_uid,
            'kyc_status' => '1',
            'kyc_state' => 3,
            'approved_by' => $this->session->userdata('kyc_id')
          ), 'kyc_id', array(
            'kyc_id' => 'DESC'
          ), '0', '1');
          //echo $this->db->last_query();die;

          $nomsg = '';
          $userdata = $this->master_model->getRecords("scribe_registration", array(
            'regnumber' => $regnumber,
            'remark' => '1',
            //'scribe_uid'=> $scribe_uid
          ));
          //echo $this->db->last_query();die;
          $username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
          $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
          $msg = implode(',', $update_data);
          $emailerstr = $this->master_model->getRecords('emailer', array(
            'emailer_name' => 'scribe_KYC_completion_email'
          ));
          //echo $this->db->last_query();die;
          $newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
          $final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);

          $info_arr = array(
            //'to'=> "kyciibf@gmail.com",
            //'to' => 'Je.mss3@iibf.org.in,Je.mss4@iibf.org.in',
            //'to' => $userdata[0]['email'],
            'from' => $emailerstr[0]['from'],
            'subject' => $emailerstr[0]['subject'],
            'message' => $final_str
          );
          //echo $final_str;die;
          //echo 'up';die;

          if ($this->Emailsending->mailsend($info_arr))
          {

            $this->session->set_flashdata('success', 'scribe KYC Complete for ' . $regnumber . '  (previous record)  candidate & Email sent successfully !!');
            // $success='KYC Completed for the candidate & Email sent successfully !!';
            // log activity

            $regnumber = $regnumber;
            //$scribe_uid = $scribe_uid;
            $user_id = $this->session->userdata('kyc_id');
            $tilte = 'Member Scribe KYC completed';
            $description = '' . $regnumber . ' has been approve by ' . $this->session->userdata('role') . '';
            //echo $regnumber;//die;
            $this->KYC_Log_model->scribe_create_log($tilte, $user_id, $last_insterid[0]['kyc_id'], $regnumber, $description);
            //echo $tilte;die;
            //
            // $this->session->set_flashdata('success','kyc completed Successfully  !!');
            // redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
            // email log
            $this->KYC_Log_model->scribe_email_log($last_insterid[0]['kyc_id'], $this->session->userdata('kyc_id'), '1', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
            //echo $this->db->last_query();die;
          }
          //echo 'out';die;
          // rebulide the array
          $member = $this->master_model->getRecords("admin_scribe_kyc_users", array(
            'DATE(date)' => date('Y-m-d'),
            'user_id' => $this->session->userdata('kyc_id')
          ));
          //echo $this->db->last_query();die;
          $arrayid = explode(',', $member[0]['allotted_member_id']);
          $index = array_search($regnumber, $arrayid, true);
          // get next record
          $currentid = $index;
          $nextid = $currentid + 1;
          if (array_key_exists($nextid, $arrayid))
          {
            $next_id = $arrayid[$nextid];
          }
          else
          {
            $next_id = $arrayid[0];
          }
          // end of next record
          unset($arrayid[$index]);
          if (count($arrayid) > 0)
          {
            foreach ($arrayid as $row)
            {
              $new_arrayid[] = $row;
            }
          }
          if (count($new_arrayid) > 0)
          {
            $regstr = implode(',', $new_arrayid);
          }
          else
          {
            $regstr = '';
            $next_id = '';
          }
          $update_data = array(
            'allotted_member_id' => $regstr
          );
          $this->db->where('DATE(date)', date('Y-m-d'));
          $this->db->where('list_type', 'New');
          $this->master_model->updateRecord('admin_scribe_kyc_users', $update_data, array(
            'user_id' => $this->session->userdata('kyc_id')
          ));
          //echo $this->db->last_query();die;
          /* Start Code To Showing Count On Member List*/
          if ($next_id == '')
          {
            $next_id = 0;
          }
          // $totalRecCount=$this->get_App_allocation_type_cnt();
          if ($srno > $totalRecCount)
          {
            // $srno=$totalRecCount;
            $srno = 1;
          }
          else
          {
            $original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
            $arr = array_slice($original_allotted_Arr, -$totalRecCount);
            $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
            $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
            $memberNo = $next_id;
            $updated_list_index = array_search($memberNo, $reversedArr_list);
            $srno = $updated_list_index;
          }
          redirect(base_url() . 'admin/kyc/Approver/scribe_approver_edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
          /* Close Code To Showing Count On Member List*/
        }
        else
        {
          //echo "out";die;
          $this->session->set_flashdata('error', 'Select all check box to complete the Kyc !!');
          // $error='Select all check-box to complete the Kyc !!';
          // redirect(base_url().'admin/kyc/Approver/approver_edited_member/'.$regnumber);
        }
      }
      //approve/recommend view member
      if ($regnumber)
      {

        $select = 'regnumber,scribe_uid,namesub,firstname,middlename,lastname,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,idproofphoto,declaration_img,mobile_scribe,email';
        $members = $this->master_model->getRecords("scribe_registration a", array(
          'regnumber' => $regnumber,
          'remark' => '1'
          //'scribe_uid' => $scribe_uid
        ), $select, "", '0', '1');
      }
      $recommnended_members_data = $this->master_model->getRecords("scribe_member_kyc", array(
        'regnumber' => $regnumber
        //'scribe_uid'=> $scribe_uid
      ), '', array(
        'kyc_id' => 'DESC'
      ), '0', '1');
      //echo $this->db->last_query();die;
      $data = array(
        'result' => $members,
        'next_id' => $next_id,
        'recomended_mem_data' => $recommnended_members_data,
        'error' => $error,
        'success' => $success
      );
      $data['srno'] = $srno;
      $data['totalRecCount'] = $totalRecCount;
      //echo "string"; print_r($members); die;
      $this->load->view('admin/kyc/Approver/scribe_approver_edited_screen', $data);
    }
    else
    {
      $this->session->set_flashdata('success', $this->session->flashdata('success'));
      redirect(base_url() . 'admin/kyc/Approver/scribe_approver_edited_list');
    }
  }
  /* Scribe KYC recommend and kyc complete Close */
}
