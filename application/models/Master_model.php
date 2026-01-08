<?php if (!defined('BASEPATH')) exit('No direct script access alloed');

class Master_model extends CI_Model
{
  /*
# function getRecordCount($tbl_name,$condition=FALSE)
# * indicates paramenter is must
# Use : 
1) return number of rows
# Parameters : 
$tbl_name* =name of table 
$condition=array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
# How to call:
$this->master_model->getRecordCount('tbl_name',$condition_array);
*/
  //priyanka D - 26-july-24
  function check_fedai_eligible($exam_code = 0, $exam_period = 0, $member_no = 0, $institute_id = 0)
  {

    if ($institute_id > 0) {
      $check_fedai_institute = $this->master_model->getRecords('fedai_institution_master', array(
        'institude_id' => $institute_id,
        'institution_delete' => 0,
      ));
      if (count($check_fedai_institute) <= 0) {
        return false;
      }
    }

    if ($member_no > 0) {
      // $api_url= "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/1009//".$exam_period.'/'.$member_no; // UAT API

      $api_url =  "http://10.10.233.76:8093/fedaieligibleapi/getFedaiEligible/" . $exam_code . "/" . $exam_period . "/" . $member_no;  //NEW LIVE API ADDED BY GAURAV ON 2024-05-27  
      // echo $api_url; exit;
      $string = preg_replace('/\s+/', '+', $api_url);
      $x = curl_init($string);
      curl_setopt($x, CURLOPT_HEADER, 0);
      curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

      $result = curl_exec($x);
      if (curl_errno($x)) //CURL ERROR
      {
        $api_res_msg = curl_error($x);
      } else {
        $api_res_flag = 'success';
        $api_res_msg = $result;
      }
      curl_close($x);

      $api_data = [];

      $api_data['api_url']      = $api_url;
      $api_data['api_response'] = $api_res_msg;
      $api_data['created_on']   = date('y-m-d H:i:s');

      if ($member_no != '' && $exam_period != '') {
        $api_data['member_no']    = $member_no;
        $api_data['exam_period']  = $exam_period;
      } else {
        $api_data['member_no']    = '';
        $api_data['exam_period']  = 0;
      }

      $this->insertRecord('fedai_eligible_api_logs', $api_data);

      $api_result_arr = array();
      $api_result_arr['api_res_flag']     = $api_res_flag;
      $api_result_arr['api_res_response'] = $api_res_msg;
      if ($api_result_arr['api_res_flag'] == 'success') {
        return $api_result_arr;
      }
      return $api_result_arr;
    }
    return true;
  }


  // Gaurav S - 26-july-25
  public function check_citap_eligible($exam_code = 0, $exam_period = 0, $member_no = 0)
  {
    if ($member_no > 0) {
      $api_url = "http://10.10.233.66:8093/citapeligibleapi/getCitapEligible/" . $exam_code . "/" . $exam_period . "/" . $member_no; // UAT API

      // $api_url= "http://10.10.233.76:8093/citapeligibleapi/getCitapEligible/".$exam_code."/".$exam_period."/".$member_no; // LIVE API

      /*if($this->get_client_ip_master() =='115.124.115.75' )
      {
        echo $api_url; exit;
      }*/

      $string = preg_replace('/\s+/', '+', $api_url);
      $x = curl_init($string);
      curl_setopt($x, CURLOPT_HEADER, 0);
      curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

      $result = curl_exec($x);

      if (curl_errno($x)) //CURL ERROR
      {
        $api_res_msg = curl_error($x);
      } else {
        $api_res_flag = 'success';
        $api_res_msg  = $result;
      }
      curl_close($x);

      $api_data = [];

      $api_data['api_url']      = $api_url;
      $api_data['api_response'] = $api_res_msg;
      $api_data['created_on']   = date('y-m-d H:i:s');

      if ($member_no != '' && $exam_period != '') {
        $api_data['member_no']    = $member_no;
        $api_data['exam_period']  = $exam_period;
      } else {
        $api_data['member_no']    = '';
        $api_data['exam_period']  = 0;
      }

      $this->insertRecord('fedai_eligible_api_logs', $api_data);

      $api_result_arr = array();
      $api_result_arr['api_res_flag']     = $api_res_flag;
      $api_result_arr['api_res_response'] = $api_res_msg;

      if ($api_result_arr['api_res_flag'] == 'success') {
        return $api_result_arr;
      }
      return $api_result_arr;
    }
    return true;
  }


  public function exemption_api_call_func($exam_code, $exam_period, $regnumber)
  {
    //$url = "http://10.10.233.66:8095/exemptionEligibleApi/getExemptionEligibleDetails/".$exam_code."/".$exam_period.'/'.$regnumber; //1007 exemption api
    $url = "http://10.10.233.76:8095/exemptionEligibleApi/getExemptionEligibleDetails/" . $exam_code . "/" . $exam_period . '/' . $regnumber; //1007 exemption api Live

    $string = preg_replace("/\s+/", "+", $url);
    $x = curl_init($string);
    curl_setopt($x, CURLOPT_HEADER, 0);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($x);

    //return 'true';
    if (curl_errno($x))  //CURL ERROR
    {
      $error_msg = curl_error($x);
      //echo $error_msg;exit;
      return 'false';
    } else {
      if ($result && !empty($result)) {

        $result = (array)json_decode($result);
        //  echo'<pre>==';print_r($result);exit;
        if (count($result) > 0 && isset($result[0][2]) && $result[0][2] == 'Y') {
          return 'true';
        } else
          return 'false';
      }
    }

    return 'false';
  }
  public function chk_exemption_application($exam_code, $regnumber)
  {
    $check_payment_val = $this->master_model->getRecords('payment_transaction', array(
      'status' => 2,
      'pay_type' => $this->config->item('exemption_pay_type'),
      'payment_transaction.exam_code' => $exam_code,
      'member_regnumber'             => $regnumber,
    ));
    if (count($check_payment_val) > 0) {
      return array('applicationexist' => 0, 'inprogress' => 1, 'msg' => 'Your transaction is in process. Please wait for some time.');
    }

    $check_payment_val = $this->master_model->getRecords('payment_transaction', array(
      'status' => 1,
      'pay_type' => $this->config->item('exemption_pay_type'),
      'payment_transaction.exam_code' => $exam_code,
      'member_regnumber'             => $regnumber,
    ));
    if (count($check_payment_val) > 0) {
      return array('applicationexist' => 1, 'inprogress' => 0, 'msg' => 'Your application is already exist for selected exam.');
    } else
      return array('applicationexist' => 0, 'inprogress' => 0, 'msg' => '');
  }
  /* public function getRecordCount($tbl_name, $condition = FALSE)
  {
    if ($condition != "" && count($condition) > 0)
    {
      foreach ($condition as $key => $val)
      {
        $this->db->where($key, $val);
      }
    }
    $num = $this->db->count_all_results($tbl_name);
    return $num;
  } */

  public function getRecordCount($tbl_name, $condition = [])
  {
    if (empty($tbl_name)) {
      return 0;
    } // Ensure the table name is not empty

    if (!is_array($condition)) {
      return 0;
    } // Ensure the condition is an array

    if (!empty($condition)) {
      $this->db->where($condition);
    } // Add conditions to the query
    $num = $this->db->count_all_results($tbl_name); // Get the count of records
    return $num; // Return the count
  }


  /*
# function getRecords($tbl_name,$condition=FALSE,$select=FALSE,$order_by=FALSE,$limit=FALSE,$start=FALSE)
# * indicates paramenter is must
# Use : 
1) return array of records from table
# Parameters : 
1) $tbl_name* =name of table 
2) $condition=array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
3) $select=('col1,col2,col3');
4) $order_by=array('colname1'=>order,'colname2'=>order); Order='ASC OR DESC'
5) $limit= limit for paging
6) $start=start for paging
# How to call:
$this->master_model->getRecords('tbl_name',$condition_array,$select,...);
# In case where we need joins, you can pass joins in controller also.
Ex: 
$this->db->join('tbl_nameB','tbl_nameA.col=tbl_nameB.col','left');
$this->master_model->getRecords('tbl_name',$condition_array,$select,...);
# Instruction 
1) check number of counts in controller or where you are displying records
*/
  /* public function getRecords($tbl_name, $condition = FALSE, $select = FALSE, $order_by = FALSE, $start = FALSE, $limit = FALSE)
  {
    if ($select != "")
    {
      $this->db->select($select);
    }
    if (count($condition) > 0 && $condition != "")
    {
      $condition = $condition;
    }
    else
    {
      $condition = array();
    }
    if (is_array($order_by) && count($order_by) > 0 && $order_by != "")
    {
      foreach ($order_by as $key => $val)
      {
        $this->db->order_by($key, $val);
      }
    }
    if ($limit != "" || $start != "")
    {
      $this->db->limit($limit, $start);
    }
    $rst = $this->db->get_where($tbl_name, $condition);
    // $rst = trim($rst);
    //echo $this->db->last_query();
    return $rst->result_array();
  } */

  public function getRecords($tbl_name, $condition = [], $select = '', $order_by = [], $start = 0, $limit = 0)
  {
    if (empty($tbl_name)) {
      return [];
    } // Ensure the table name is not empty    

    if (!empty($select)) {
      $this->db->select($select);
    } // Apply select clause    

    if (!empty($condition)) {
      $this->db->where($condition);
    } // Apply conditions

    if (!empty($order_by)) {
      foreach ($order_by as $key => $val) {
        $this->db->order_by($key, $val);
      }
    } // Apply order by clause

    if (!empty($limit)) {
      $this->db->limit($limit, $start);
    } // Apply limit and start

    $query = $this->db->get($tbl_name); // Execute the query

    return $query->result_array(); // Return the result as an array
  }


  /* public function getValue($tbl_name, $condition = FALSE, $field = FALSE)
  {
    $value = '';
    if ($field != "")
    {
      $this->db->select($field);
    }
    if (count($condition) > 0 && $condition != "")
    {
      $condition = $condition;
    }
    else
    {
      $condition = array();
    }
    $query = $this->db->get_where($tbl_name, $condition);
    //return $rst->result_array();
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $value = $row->$field;
    }
    return $value;
  } */

  public function getValue($tbl_name, $condition = [], $field = '')
  {
    if (empty($tbl_name)) {
      return '';
    } // Ensure the table name is not empty    

    if (empty($field)) {
      return '';
    } // Ensure the field name is not empty    

    $this->db->select($field); // Apply select clause    

    if (!empty($condition)) {
      $this->db->where($condition);
    } // Apply conditions    

    $query = $this->db->get($tbl_name); // Execute the query    

    if ($query->num_rows() > 0) {
      $row = $query->row();
      return $row->$field;
    } // Check if any row is returned and fetch the field value

    return '';
  }

  /* public function insertRecord($tbl_name, $data_array, $insert_id = FALSE)
  {
    if ($this->db->insert($tbl_name, $data_array))
    {
      if ($insert_id == true)
      {
        return $this->db->insert_id();
      }
      else
      {
        return true;
      }
    }
    else
    {
      return false;
    }
  } */

  public function insertRecord($tbl_name, $data_array, $return_insert_id = false)
  {
    if (empty($tbl_name) || empty($data_array)) {
      return false;
    } // Ensure the table name and data array are not empty

    if ($this->db->insert($tbl_name, $data_array)) // Perform the insert operation
    {
      if ($return_insert_id) {
        return $this->db->insert_id();
      } // Return the insert ID if requested
      return true;
    }

    return false; // Return false if insert operation failed
  }

  /*
# function updateRecord($tbl_name,$data_array,$pri_col,$id)
# * indicates paramenter is must
# Use : 
1) updates record, on successful updates return true.
# Parameters : 
1) $tbl_name* = name of table 
2) $data_array* = array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
3) $pri_col* = primary key or column name depending on which update query need to fire.
4) $id* = primary column or condition column value.
# How to call:
$this->master_model->updateRecord('tbl_name',$data_array,$pri_col,$id)
*/

  /* public function updateRecord($tbl_name, $data_array, $where_arr)
  {
    //$this->db->where($where_arr,NULL,FALSE);
    $this->db->where($where_arr);  // changed on 20-06-2017, by Prafull + Bhagwan
    if ($this->db->update($tbl_name, $data_array))
    {
      return true;
    }
    else
    {
      return false;
    }
  } */

  public function updateRecord($tbl_name, $data_array, $where_arr)
  {
    if (empty($tbl_name) || empty($data_array) || empty($where_arr)) {
      return false;
    } // Ensure the table name, data array, and where array are not empty    

    $this->db->where($where_arr); // Apply the where conditions    

    if ($this->db->update($tbl_name, $data_array)) {
      return true;
    } // Perform the update operation    

    return false; // Return false if update operation failed
  }

  /*
# function deleteRecord($tbl_name,$pri_col,$id)
# * indicates paramenter is must
# Use : 
1) delete record from table, on successful deletion returns true.
# Parameters : 
1) $tbl_name* = name of table 
2) $pri_col* = primary key or column name depending on which update query need to fire.
3) $id* = primary column or condition column value.
# How to call:
$this->master_model->deleteRecord('tbl_name','pri_col',$id)
# It will useful while deleting record from  single table. delete join will not work here.
*/

  /* public function deleteRecord($tbl_name, $pri_col, $id)
  {
    $this->db->where($pri_col, $id);
    if ($this->db->delete($tbl_name))
    {
      return true;
    }
    else
    {
      return false;
    }
  } */

  public function deleteRecord($tbl_name, $pri_col, $id)
  {
    if (empty($tbl_name) || empty($pri_col) || empty($id)) {
      return false;
    } // Ensure the table name, primary column, and id are not empty    

    $this->db->where($pri_col, $id); // Apply the where condition    

    if ($this->db->delete($tbl_name)) {
      return true;
    } // Perform the delete operation    

    return false; // Return false if delete operation failed
  }

  /* 
# function createThumb($file_name,$path,$width,$height,$maintain_ratio=FALSE)
# * indicates paramenter is must
# Use : 
1) create thumb of uploaded image.
# Parameters : 
1) $file_name* = name of uploaded file 
2) $path* = path of directory
3) $width* = width of thumb
4) $height* = height of thumb
5) $maintain_ratio = if need to maintain ration of original image then pass true, in this case thumb may vary in
height and width provided. default it is FALSE.
# How to call:
$this->master_model->createThumb($file_name,$path,$width,$height,$maintain_ratio=FALSE)
# !!Important : thumb foler  name must be 'thumb'
*/
  public function createThumb($file_name, $path, $width, $height, $maintain_ratio = FALSE)
  {
    $config_1['image_library'] = 'gd2';
    $config_1['source_image'] = $path . $file_name;
    $config_1['create_thumb'] = TRUE;
    $config_1['maintain_ratio'] = $maintain_ratio;
    $config_1['thumb_marker'] = '';
    $config_1['new_image'] = $path . "thumb/" . $file_name;
    $config_1['width'] = $width;
    $config_1['height'] = $height;
    $this->load->library('image_lib', $config_1);
    $this->image_lib->initialize($config_1);
    $this->image_lib->resize();
    if (!$this->image_lib->resize())
      echo $this->image_lib->display_errors();
  }

  /* create slug */
  function create_slug($phrase, $tbl_name, $title_col, $pri_col = '', $id = '', $maxLength = 100000000000000)
  {
    $result = strtolower($phrase);
    $result = preg_replace("/[^A-Za-z0-9\s-._\/]/", "", $result);
    $result = trim(preg_replace("/[\s-]+/", " ", $result));
    $result = trim(substr($result, 0, $maxLength));
    $result = preg_replace("/\s/", "-", $result);
    $slug = $result;
    if ($id != "") {
      $this->db->where($pri_col . ' !=', $id);
    }
    $rst = $this->db->get_where($tbl_name, array($title_col => $slug));
    if ($rst->num_rows() > 0) {
      $count = $rst->num_rows() + 1;
      return $slug = $slug . $count;
    } else {
      return $slug;
    }
  }

  public function video_image($url)
  {
    $image_url = parse_url($url);
    if ($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com') {
      $array = explode("&", $image_url['query']);
      return "http://img.youtube.com/vi/" . substr($array[0], 2) . "/0.jpg";
    } else if ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com') {
      $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . substr($image_url['path'], 1) . ".php"));
      return $hash[0]["thumbnail_large"];
    }
  }

  public function document_upload($file_name, $id, $config, $company_id, $config1)
  {
    $this->load->library('upload');
    $this->upload->initialize($config1);
    if ($this->upload->do_upload($file_name)) {
      echo 'model';
      exit;
      $dt = $this->upload->data();
      $config_1['image_library'] = 'gd2';
      $config_1['source_image'] = "uploads/project_document/" . $dt['file_name'];
      $config_1['thumb_marker'] = '';
      $this->load->library('image_lib', $config_1);
      $this->image_lib->initialize($config_1);
      $this->image_lib->resize();
      $qry = array('company_id' => $company_id, 'project_id' => $id, 'document_name' => $dt['file_name']);
      if ($this->db->insert('project_document', $qry)) { //echo $this->db->last_query();
        //	exit;
        return true;
      } else {
        return false;
      }
    } else {
      //print_r($this->upload->display_errors());	
    }
  }

  public $master_skill = array();

  public function get_all_skill($parent, $level = FALSE)
  {
    global $master_skill;
    if ($level != "") {
      $this->db->where('depth != ' . $level . '', NULL, FALSE);
    }
    $this->db->where('parent_id', $parent);
    $this->db->order_by('skill_name');
    $rst = $this->db->get('skill_master');
    if ($rst->num_rows() > 0) {
      $row = $rst->result();
      foreach ($row as $row) {
        $master_skill[$row->skill_id] = $row;
        $this->get_all_skill($row->skill_id, $level);
      }
    }
    return $master_skill;
  }

  //##----------send SMS-----------##(Prafull)
  public function send_sms($mobile = NULL, $text = NULL)
  {
    if ($mobile != NULL && $text != NULL) {
      $url = "http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=" . $mobile . "&text=" . urlencode($text) . "&senderid=OTPSMS&route_id=2&Unicode=0";
      $string = preg_replace('/\s+/', '', $url);
      $x = curl_init($string);
      curl_setopt($x, CURLOPT_HEADER, 0);
      curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
      $reply = curl_exec($x);
      /*if($reply)
{
$inser_array=array('respond'=>htmlspecialchars_decode($reply),'mobile'=>$mobile,'status'=>'success');
}
else
{
$inser_array=array('respond'=>htmlspecialchars_decode($reply),'mobile'=>$mobile,'status'=>'fail');
}
$this->insertRecord('sms_log',$inser_array);*/
      curl_close($x);
      $res = $this->sms_balance_notify($reply);
    }
  }
  public function get_client_ip_master()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }

  //START : FUNCTION FOR SENDING SMS USING TRUSTSIGNAL GATEWAY. ADDED ON 01-11-2021
  public function send_sms_trustsignal($mobile_no = '', $message = '', $template_id = '', $exam_code = '', $route = '', $sender_id = '')
  {
    //
    //$mobile_no=9145642016;
    //$mobile_arr = array('7588096918','9370877830','9511905565','8369179684','7710033822','9769917717','9833560731','7977765069'); 
    $mobile_arr = array('7588096918');
    if (!in_array($mobile_no, $mobile_arr)) {
      return 1;
      exit();
    }

    //if($this->get_client_ip_master() == '115.124.115.75' || $this->get_client_ip_master() == '115.124.115.69'  || $this->get_client_ip_master() =='182.73.101.70'){ return 1; }

    $return_arr = $add_log = array();
    $api_key = '6cc49b51-5a2e-4e4d-a34a-ef5c2c203da2';
    $status = $response = $data_string = '';
    if ($mobile_no != '' && $message != '' && $template_id != '') {
      if ($route == '') {
        $route = 'transactional';
      }
      if ($sender_id == '') {
        $sender_id = 'IIBFCO';
      }

      $mobile_no = array($mobile_no);
      $data = array("sender_id" => $sender_id, "to" => $mobile_no, "message" => $message, "route" => $route, "template_id" => $template_id);
      $data_string = json_encode($data);
      $ch = curl_init('https://api.trustsignal.io/v1/sms?api_key=' . $api_key);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string)
        )
      );
      $response = curl_exec($ch);
      $err = curl_error($ch);
      curl_close($ch);
      $msg_res = json_decode($response, true);
      //echo'<pre>';print_r($msg_res);
      if ($err) {
        $return_arr['status'] = $status = 'fail';
        $return_arr['message'] = $err;
      } else {
        $return_arr['status'] = $status = 'success';
        $return_arr['message'] = json_decode($response, true);
        $return_arr['data'] = $data;
      }
    } else {
      $return_arr['status'] = $status = 'fail';
      $return_arr['message'] = 'Invalid parameter supplied to function';
    }
    $this->load->helper('url');
    $add_log['mobile_no'] = json_encode($mobile_no);
    $add_log['message'] = json_encode($message);
    $add_log['template_id'] = json_encode($template_id);
    $add_log['api_key'] = $api_key;
    $add_log['response'] = json_encode($response);
    $add_log['data_string'] = $data_string;
    $add_log['exam_code'] = $exam_code;
    $add_log['class_name'] = $this->router->fetch_class();
    $add_log['method_name'] = $this->router->fetch_method();
    $add_log['current_url'] = current_url();
    $this->insertRecord('sms_log_trustsignal', $add_log);
    return $return_arr;
  }
  //END : FUNCTION FOR SENDING SMS USING TRUSTSIGNAL GATEWAY. ADDED ON 01-11-2021

  //START : FUNCTION FOR SENDING SMS USING mobicomm GATEWAY. ADDED ON 07-09-2023
  //$mobile_no='7588096918,9881191703,9850098500'  
  public function send_sms_common_all_old($mobile_no = '', $message = '', $template_id = '', $sender_id = '', $exam_code = '', $route = '')
  {
    $mobile_arr = array('9527676118');
    if (!in_array($mobile_no, $mobile_arr)) {
      return 1;
      exit();
    }

    $return_arr = $add_log = array();
    $status = $response = $data_string = '';
    $sms_user = 'IIBF';
    $sms_api_key = 'c6b75a20f6XX';
    $sms_entityid = '1701162807222263362';

    if ($mobile_no != '' && $message != '' && $template_id != '') {
      if ($route == '') {
        $route = 'transactional';
      }
      if ($sender_id == '') {
        $sender_id = 'IIBFCO';
      }

      $mobile_no = '+91' . str_replace(",", ",+91", $mobile_no);

      $xml_data = 'user=' . $sms_user . '&key=' . $sms_api_key . '&mobile=' . $mobile_no . '&message=' . $message . '&senderid=' . $sender_id . '&accusage=1&entityid=' . $sms_entityid . '&tempid=' . $template_id;

      $ch = curl_init("http://redirect.ds3.in/submitsms.jsp?");
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
      curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $response = curl_exec($ch);
      $err = curl_error($ch);
      curl_close($ch);
      echo $err;
      exit;
      if ($err) {
        $return_arr['status'] = $status = 'fail';
        $return_arr['message'] = $err;
      } else {
        $response_arr = explode(",", $response);
        if (count($response_arr) > 0 && trim($response_arr[0]) == 'fail') {
          $return_arr['status'] = $status = 'fail';
        } else {
          $return_arr['status'] = $status = 'success';
        }
        $return_arr['message'] = $response;
        $return_arr['data'] = $xml_data;
      }
    } else {
      $return_arr['status'] = $status = 'fail';
      $return_arr['message'] = 'Invalid parameter supplied to function';
    }

    $this->load->helper('url');
    $add_log['mobile_no'] = json_encode($mobile_no);
    $add_log['message'] = json_encode($message);
    $add_log['template_id'] = $template_id;
    $add_log['api_key'] = $sms_api_key;
    $add_log['sms_user'] = $sms_user;
    $add_log['sms_entityid'] = $sms_entityid;
    $add_log['sender_id'] = $sender_id;
    $add_log['response'] = trim($response);
    $add_log['data_string'] = $data_string;
    $add_log['exam_code'] = $exam_code;
    $add_log['class_name'] = $this->router->fetch_class();
    $add_log['method_name'] = $this->router->fetch_method();
    $add_log['current_url'] = current_url();
    $this->insertRecord('sms_log_mobicomm ', $add_log);
    return $return_arr;
  } //END : FUNCTION FOR SENDING SMS USING mobicomm GATEWAY. ADDED ON 07-09-2023

  //START : FUNCTION FOR SENDING SMS USING mobicomm GATEWAY. ADDED ON 07-09-2023
  //$mobile_no='7588096918,9881191703,9850098500'  
  public function send_sms_common_all($mobile_no = '', $message = '', $template_id = '', $sender_id = '', $exam_code = '', $route = '')
  {
    $return_arr = array();
    $return_arr['status'] = $status = 'success';
    // return $return_arr; exit;

    $mobile_arr = array('8308318490', '9511905565', '7588096918', '9921198257', '9145642016', '9833560731', '9769917717', '9819389941', '9404729618', '7503787233', '8051540821', '8668401523', '7017145851', '9527676118', '7745021222', '8459298095', '9958845774', '9172211503', '9100681078', '8317550652', '7678510108', '7984258624', '8077847373', '9819635768', '7303934390', '7387764879', '9175498397', '7620851847', '9763430995', '9422701938', '9172211503', '9833560731','9962306186','9766711906');

    if (!in_array($mobile_no, $mobile_arr)) {
      return 1;
      exit();
    }

    $return_arr = $add_log = array();
    $status = $response = $data_string = '';
    $sms_user = 'IIBF';
    $sms_api_key = 'c6b75a20f6XX';
    $sms_entityid = '1701162807222263362';

    if ($mobile_no != '' && $message != '' && $template_id != '') {
      if ($route == '') {
        $route = 'transactional';
      }
      if ($sender_id == '') {
        $sender_id = 'IIBFCO';
      }

      $mobile_no = '+91' . str_replace(",", ",+91", $mobile_no);

      $xml_data = 'user=' . $sms_user . '&key=' . $sms_api_key . '&mobile=' . $mobile_no . '&message=' . $message . '&senderid=' . $sender_id . '&accusage=1&entityid=' . $sms_entityid . '&tempid=' . $template_id;

      $ch = curl_init("http://redirect.ds3.in/submitsms.jsp?");
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
      curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $response = curl_exec($ch);
      $err = curl_error($ch);
      curl_close($ch);
      // echo "Error : ".$err;
      // echo "Response : ".$response; exit;
      if ($err) {
        $return_arr['status'] = $status = 'fail';
        $return_arr['message'] = $err;
      } else {
        $response_arr = explode(",", $response);
        if (count($response_arr) > 0 && trim($response_arr[0]) == 'fail') {
          $return_arr['status'] = $status = 'fail';
        } else {
          $return_arr['status'] = $status = 'success';
        }
        $return_arr['message'] = $response;
        $return_arr['data'] = $xml_data;
      }
    } else {
      $return_arr['status'] = $status = 'fail';
      $return_arr['message'] = 'Invalid parameter supplied to function';
    }

    $this->load->helper('url');
    $add_log['mobile_no'] = json_encode($mobile_no);
    $add_log['message'] = json_encode($message);
    $add_log['template_id'] = $template_id;
    $add_log['api_key'] = $sms_api_key;
    $add_log['sms_user'] = $sms_user;
    $add_log['sms_entityid'] = $sms_entityid;
    $add_log['sender_id'] = $sender_id;
    $add_log['response'] = trim($response);
    $add_log['data_string'] = $data_string;
    $add_log['exam_code'] = $exam_code;
    $add_log['class_name'] = $this->router->fetch_class();
    $add_log['method_name'] = $this->router->fetch_method();
    $add_log['current_url'] = current_url();
    $this->insertRecord('sms_log_mobicomm ', $add_log);
    return $return_arr;
  } //END : FUNCTION FOR SENDING SMS USING mobicomm GATEWAY. ADDED ON 07-09-2023

  // SMS Balance Notification, Added By Bhagwan Sahane, on 24-04-2017
  public function sms_balance_notify($html)
  {
    $this->load->library('email');
    $sms_balance = 0;
    //$html = file_get_contents('sms_api_reply.php'); //get the html returned from the following url
    $dom = new DOMDocument();
    libxml_use_internal_errors(TRUE); // disable libxml errors
    if (!empty($html)) { // if any html is actually returned
      $dom->loadHTML($html);
      libxml_clear_errors(); // remove errors for yucky html
      $dom_xpath = new DOMXPath($dom);
      // get all the h2's with an id
      $dom_row = $dom_xpath->query('//span[@id="Label6"]');
      if ($dom_row->length > 0) {
        foreach ($dom_row as $row) {
          $sms_balance_str = $row->nodeValue;
          //echo $sms_balance_str;
        }
        $sms_balance = (int) trim(str_replace("Your current balance is : ", "", $sms_balance_str));
        // check current sms balance
        if ($sms_balance == 1000 || $sms_balance == 500 || $sms_balance == 300 || $sms_balance == 100) {
          // send email notification
          $from_name = 'IIBF';
          $from_email = 'noreply@iibf.org.in';
          $subject = 'SMS Balance Alert';
          // email receipient list -
          //$recipient_list = array('bhagwan.sahane@esds.co.in', 'shruti.samdani@esds.co.in', 'prafull.tupe@esds.co.in');
          $recipient_list = array('iibfdevp@esds.co.in');
          $message = 'Your current balance is : ' . $sms_balance;
          $config = array('mailtype' => 'html', 'charset' => 'utf-8', 'wordwrap' => TRUE);
          $this->email->initialize($config);
          $this->email->from($from_email, $from_name);
          $this->email->to($recipient_list);
          $this->email->subject($subject);
          $this->email->message($message);
          if ($this->email->send()) {
            //echo 'Email Sent.';
            //$this->email->print_debugger();
            //echo $this->email->print_debugger();
            return true;
          } else {
            //echo 'Email Not Sent.';
            return false;
          }
        }
      }
    }
  }

  public function checkadmitcarddate($exam_code)
  {
    try {
      $this->db->select('from_date,to_date');
      $exam = $this->db->get_where('admitcardsetting', array('exam_code' => $exam_code));
      $exam_result = $exam->row();
      //echo $exam_result->from_date."|";
      $curr_date = date("Y-m-d");
      if ($curr_date >= $exam_result->from_date && $curr_date <= $exam_result->to_date) {
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      echo "Message:" . $e->getMessage();
    }
  }

  public function showcarddownloadlink($regnumber)
  {
    try {
      $this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
      $this->db->from('admitcard_info');
      $this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
      $this->db->where(array('admitcard_info.mem_mem_no' => $regnumber));
      $record = $this->db->get();
      $result = $record->row();
      if (isset($result)) {
        $exam_code = $result->exm_cd;
        $this->db->select('description');
        $exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
        $exam_result = $exam->row();
        $exam_name = $exam_result->description;
        $isvalid = $this->checkadmitcarddate($exam_code);
        if ($exam_name != '' && $isvalid != '') {
          $admit_arr = array('is_show' => 'yes', 'exam_name' => $exam_name);
        } else {
          $admit_arr = array('is_show' => 'no', 'exam_name' => '');
        }
      } else {
        $admit_arr = array('is_show' => 'no', 'exam_name' => '');
      }
      return $admit_arr;
    } catch (Exception $e) {
      echo "" . $e->getMessage();
    }
  }

  public function exam_set_cookie($reg_id = NULL)
  {
    $this->load->helper('cookie');
    if ($reg_id != NULL) {
      $cookie = array(
        'name'   => 'examcookie',
        'value'  => $reg_id,
        'expire' => time() + 86500,
      );
      $this->input->set_cookie($cookie);
    }
  }

  public function exam_get_cookie()
  {
    $this->load->helper('cookie');
    $val = $this->input->cookie('examcookie', TRUE);
    if ($val) {
      return $val;
    } else {
      return false;
    }
  }

  public function warning()
  {
    if (date('Y-m-d') >= '2017-06-28') {
      redirect(base_url() . 'Warning');
    }
  }

  function professional_bankers_api_curl($exam_code = 0, $member_number = 0, $return_flag = 0)
  {
    $final_arr = $response_msg = array();
    $response = '';

    //$url="http://10.10.233.66:8082/professionalbankersapi/getProfessionalBankersDetails/".$exam_code."/".$member_number; //for staging

    $url = "http://10.10.233.76:8086/professionalbankersapi/getProfessionalBankersDetails/" . $exam_code . "/" . $member_number; //for production

    $string = preg_replace('/\s+/', '+', $url);
    $x = curl_init($string);
    curl_setopt($x, CURLOPT_HEADER, 0);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

    $result = curl_exec($x);

    if (curl_errno($x)) //CURL ERROR
    {
      $response = 'error';
      $response_msg = curl_error($x);
    } else {
      $response = "success";
      $response_msg = $result;

      //Response string : exam_code, member_number, eligible or not (Y/N), EXM CD1, Y/N, EXM CD2, Y/N, EXM CD3, Y/N, EXM CD4, Y/N, EXM CD5, Y/N, EXM CD6, Y/N
      // If exam code is 0, then ignore that exam code
    }
    curl_close($x);

    $final_arr['response'] = $response;
    $final_arr['response_msg'] = $response_msg;

    if ($return_flag == 0) {
      return $final_arr;
    } else {
      print_r(json_encode($final_arr));
    }
  }

  function institute_subscription_api_curl($institute_no = 0, $invoice_no = 0)
  {
    $final_arr = $response_msg = array();
    $response = '';

    /* $final_arr['response'] = 'success';
$final_arr['response_msg'] = '[["1418","IM-1418-215-2324","ABC BANK LTD","2360","2024-2025","test"]]';			
return json_encode($final_arr); exit; */

    //$url="http://10.10.233.66:8085/instituteSubscriptionAPI/getInstituteSubscription/".$institute_no."/".$invoice_no;
    $url = "http://10.10.233.76:8088/instituteSubscriptionAPI/getInstituteSubscription/" . $institute_no . "/" . $invoice_no;

    $string = preg_replace('/\s+/', '+', $url);
    $x = curl_init($string);
    curl_setopt($x, CURLOPT_HEADER, 0);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

    $result = curl_exec($x);
    if (curl_errno($x)) //CURL ERROR
    {
      $response = 'error';
      $response_msg = curl_error($x);
    } else {
      $decoded_res = json_decode($result);
      //echo '<pre>'; print_r($decoded_res); echo '</pre>';  exit;

      if (count($decoded_res) > 0) {
        if (array_key_exists(0, $decoded_res)) {
          $res_institute_no = $res_invoice_no = 0;

          if (array_key_exists(0, $decoded_res[0])) {
            $res_institute_no = $decoded_res[0][0];
          }
          if (array_key_exists(1, $decoded_res[0])) {
            $res_invoice_no = $decoded_res[0][1];
          }

          if ($res_institute_no == $institute_no && $res_invoice_no == $invoice_no) {
            $response = "success";
            $response_msg = $result;
          } else {
            $response = 'error';
            $response_msg = "Invalid combination of Institute No & Invoice No";
          }
        } else {
          $response = 'error';
          $response_msg = "Invalid combination of Institute No & Invoice No";
        }
      } else {
        $response = 'error';
        $response_msg = "Invalid combination of Institute No & Invoice No";
      }
    }
    curl_close($x);

    $final_arr['response'] = $response;
    $final_arr['response_msg'] = $response_msg;

    return json_encode($final_arr);
    //print_r(json_encode($final_arr));
  }

  function disa_api_curl($exam_code = 0, $exam_period = 0, $member_number = 0)
  {
    $final_arr = $response_msg = array();
    $flag = 'error';
    $eligible_flag = '';

    //$url = "http://10.10.233.66:8094/disaeligibleapi/getDisaEligible/" . $exam_code . "/" . $exam_period . "/" . $member_number; //for staging
    $url = "http://10.10.233.76:8094/disaeligibleapi/getDisaEligible/" . $exam_code . "/" . $exam_period . "/" . $member_number; //for production

    $string = preg_replace('/\s+/', '+', $url);
    $x = curl_init($string);
    curl_setopt($x, CURLOPT_HEADER, 0);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

    $result = curl_exec($x);

    if (curl_errno($x)) //CURL ERROR
    {
      $response_msg = curl_error($x);
    } else {
      $response_msg = $result;
      //Response string : EXAM_ID, MEMBERSHIP_NO, ELIGIBLE_FLAG ( 'N' - Not Eligible / 'Y' - Eligible)

      if ($result != "") {
        $result_arr = json_decode($result, true);
        if (isset($result_arr[0]) && count($result_arr[0]) > 0) {
          if (isset($result_arr[0][0]) && $result_arr[0][0] == $exam_code && isset($result_arr[0][1]) && $result_arr[0][1] == $member_number) {
            if (isset($result_arr[0][2])) {
              $flag = "success";

              $response_flag = $result_arr[0][2];
              if ($response_flag == 'Y') {
                $eligible_flag = $response_flag;
              }
            }
          }
        }
      }
    }
    curl_close($x);

    $final_arr['flag'] = $flag;
    $final_arr['api_endpoint'] = $url;
    $final_arr['response_msg'] = $response_msg;
    $final_arr['eligible_flag'] = $eligible_flag;

    // print_r($final_arr); exit;
    return $final_arr;
  }

  // Gaurav Shewale 26th Jun 2025
  function citap_api_curl($exam_code = 0, $exam_period = 0, $member_number = 0)
  {
    $final_arr = $response_msg = array();
    $flag = 'error';
    $eligible_flag = '';

    // $url  = "http://10.10.233.66:8099/citapeligibleapi/getCitapEligible/" . $exam_code . "/" . $exam_period . "/" . $member_number; //for staging

    $url = "http://10.10.233.76:8100/citapeligibleapi/getCitapEligible/" . $exam_code . "/" . $exam_period . "/" . $member_number; //for production

    $string = preg_replace('/\s+/', '+', $url);
    $x = curl_init($string);
    curl_setopt($x, CURLOPT_HEADER, 0);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

    $result = curl_exec($x);

    if (curl_errno($x)) //CURL ERROR
    {
      $response_msg = curl_error($x);
    } else {
      $response_msg = $result;
      //Response string : EXAM_ID, MEMBERSHIP_NO, ELIGIBLE_FLAG ( 'N' - Not Eligible / 'Y' - Eligible)

      if ($result != "") {
        $result_arr = json_decode($result, true);

        if (isset($result_arr[0]) && count($result_arr[0]) > 0) {
          if (isset($result_arr[0][0]) && $result_arr[0][0] == $exam_code && isset($result_arr[0][1]) && $result_arr[0][1] == $member_number) {
            if (isset($result_arr[0][2])) {
              $flag = "success";

              $response_flag = $result_arr[0][2];
              if ($response_flag == 'Y') {
                $eligible_flag = $response_flag;
              }
            }
          }
        }
      }
    }
    curl_close($x);

    $final_arr['flag'] = $flag;
    $final_arr['api_endpoint'] = $url;
    $final_arr['response_msg'] = $response_msg;
    $final_arr['eligible_flag'] = $eligible_flag;

    $api_data = [];

    $api_data['api_url']      = $url;
    $api_data['api_response'] = $response_msg;
    $api_data['created_on']   = date('y-m-d H:i:s');
    $api_data['member_no']    = $member_number;
    $api_data['exam_period']  = $exam_period;

    $this->insertRecord('citap_eligible_api_logs', $api_data);


    return $final_arr;
  }

  function fedai_api_curl($exam_code = 0, $exam_period = 0, $member_number = 0)
  {
    $response_msg = '';
    $api_res_flag = 'error';

    // $url = "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/1009/811/500066883";

    $url = "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/" . $exam_code . "/" . $exam_period . "/" . $member_number;

    $string = preg_replace('/\s+/', '+', $url);
    $x = curl_init($string);
    curl_setopt($x, CURLOPT_HEADER, 0);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);


    $result = curl_exec($x);
    if (curl_errno($x)) //CURL ERROR
    {
      $api_res_msg = curl_error($x);
    } else {
      $api_res_flag = 'success';
      $api_res_msg = $result;
    }
    curl_close($x);

    $api_result_arr = array();
    $api_result_arr['api_res_flag']     = $api_res_flag;
    $api_result_arr['api_res_response'] = $api_res_msg;
    $api_result_arr['api_endpoint']     = $url;
    // print_r($api_result_arr); exit;    
    return $api_result_arr;
  }

  function fedai_institute_api_curl($exam_code = 0, $exam_period = 0, $member_number = 0)
  {
    $api_res_flag = 'error';
    $api_res_msg = '';

    $api_url = "http://10.10.233.66:8091/masterData/getFedaiMasterDetails/" . $exam_code . "/" . $exam_period . "/1";  //NEW API ADDED BY GAURAV ON 2024-05-27          

    $string = preg_replace('/\s+/', '+', $api_url);
    $x = curl_init($string);
    curl_setopt($x, CURLOPT_HEADER, 0);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

    $result = curl_exec($x);
    if (curl_errno($x)) //CURL ERROR
    {
      $api_res_msg = curl_error($x);
    } else {
      $api_res_flag = 'success';
      $api_res_msg = $result;
    }
    curl_close($x);

    $api_result_arr = array();
    $api_result_arr['api_res_flag']     = $api_res_flag;
    $api_result_arr['api_res_response'] = $api_res_msg;
    return $api_result_arr;
  }


  /** ADDED BY SAGAR M ON 10-06-2024 */
  /******** START : ADMITCARD PHOTO / SIGNATURE : CHECK IF IMAGE HEIGHT OR WIDTH IS HIGHER THAN 500PX. IF YES, THEN COPY ORIGINAL IMAGE INTO uploads/admitcard_resize_images FOLDER AND RESIZE THE ACTUAL IMAGE ********/
  function resize_admitcard_images($file_full_path)
  {
    '<br>file_full_path : ' . $file_full_path;
    $explode_arr = explode("/", $file_full_path);
    '<br>file_name : ' . $file_name = $explode_arr[count($explode_arr) - 1];
    '<br>file_path : ' . $file_path = str_replace($file_name, '', $file_full_path);

    $headers = @get_headers(base_url() . $file_full_path);
    //echo '<pre>'; print_r($headers); exit;
    if (stripos($headers[0], "200 OK")) {
      list($width, $height) = getimagesize(base_url() . $file_full_path);
      //echo "<br>Image exists. Width: $width px, Height: $height px";
      if ($width > 500 || $height > 500) {
        '<br>copy_directory : ' . $copy_directory = str_replace('uploads/', 'uploads/admitcard_resize_images/', $file_path);
        $this->create_directories($copy_directory);
        if (copy($file_path . $file_name, $copy_directory . $file_name)) {
          $this->load->helper('file');
          $this->load->helper('url');
          $this->load->library('image_lib');
          $config_1['image_library'] = 'gd2';
          $config_1['source_image'] = $file_path . $file_name; //'./uploads/photograph/p_510259010.jpg'; //
          $config_1['create_thumb'] = TRUE;
          $config_1['maintain_ratio'] = TRUE;
          $config_1['thumb_marker'] = '';
          $config_1['new_image'] = $file_path . $file_name; //'./uploads/photograph/thumb_p_510259010.jpg'; //
          $config_1['width'] = '500';
          $config_1['height'] = '500';

          //echo '<pre>';print_r($config_1);
          $this->image_lib->clear();
          $this->image_lib->initialize($config_1);
          $this->image_lib->resize();
        }
      }
    }
  }
  /******** END : ADMITCARD PHOTO / SIGNATURE : CHECK IF IMAGE HEIGHT OR WIDTH IS HIGHER THAN 500PX. IF YES, THEN COPY ORIGINAL IMAGE INTO uploads/admitcard_resize_images FOLDER AND RESIZE THE ACTUAL IMAGE ********/

  /** ADDED BY SAGAR M ON 10-06-2024 */
  /******** START : CREATE N NUMBER OF NESTED DIRECTORIES ********/
  //"./uploads/FOLDER1/FOLDER2/FOLDER3"
  function create_directories($directory_path = '')
  {
    $directory_path = str_replace("./", "", $directory_path);
    $directory_path_arr = explode("/", $directory_path);
    $chk_dir_path = './';
    if (count($directory_path_arr) > 0) {
      $i = 0;
      foreach ($directory_path_arr as $res) {
        if ($i > 0) {
          $chk_dir_path .= "/";
        }
        $chk_dir_path .= $res;

        if (!is_dir($chk_dir_path)) {
          $dir = mkdir($chk_dir_path, 0755);
          $myfile = fopen($chk_dir_path . "/index.php", "w") or die("Unable to open file!");
          $txt = "";
          fwrite($myfile, $txt);
          fclose($myfile);
        }
        $i++;
      }
    }
    return $chk_dir_path;
  }
  /******** END : CREATE N NUMBER OF NESTED DIRECTORIES ********/

  //START : CHECK AND FIND CSC UNIQUE RECEIPT NUMBER ADDED ON 26 May 2025 ANIL & SAGAR
  function generate_csc_receipt_number()
  {
    do {
      $receipt_no = rand(22222222, 99999999);
      $exists_in_main = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $receipt_no), 'receipt_no');
      $exists_in_secondary = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no), 'receipt_no');
    } while (!empty($exists_in_main) || !empty($exists_in_secondary));

    return $receipt_no;
  }
  //END : CHECK AND FIND CSC UNIQUE RECEIPT NUMBER ADDED ON 26 May 2025 ANIL & SAGAR

}
