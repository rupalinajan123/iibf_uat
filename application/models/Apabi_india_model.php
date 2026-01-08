<?php

/********************************************************************************************************************
 ** Description: APABI INDIA Model
 ** Created BY: Sagar Matale On 30-09-2024
 ********************************************************************************************************************/
class Apabi_india_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('master_model');
    $this->load->model('Emailsending');
  }

  function check_apabi_india_session_login()
  /******** START : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON APABI INDIA ADMIN LOGIN PAGE ********/
  {
    $login_user_id = $this->session->userdata('APABI_INDIA_ADMIN_LOGIN_ID');
    if (isset($login_user_id) && $login_user_id != "")
    {
      $apabi_india_admin_data = $this->master_model->getRecords('apabi_india_admin', array('admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id');

      if (count($apabi_admin_data) > 0)
      {
        redirect(site_url('apabi_india_admin/dashboard_admin'), 'refresh');
      }
      else
      {
        redirect(site_url('apabi_india_admin/login/logout'), 'refresh');
      }
    }
  }
  /******** END : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON APABI INDIA ADMIN LOGIN PAGE ********/

  /******** START : CHECK SESSION AFTER LOGIN FOR ALL ADMIN PAGES ********/
  function check_apabi_session_all_pages()
  {
    $login_user_id = $this->session->userdata('APABI_INDIA_ADMIN_LOGIN_ID');

    if (!isset($login_user_id) || $login_user_id == "")
    {
      redirect(site_url('apabi_india_admin/login/logout'), 'refresh');
    }
    else
    {
      $admin_data = $this->master_model->getRecords('apabi_india_admin', array('admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id');

      if (count($admin_data) == 0)
      {
        redirect(site_url('apabi_india_admin/login/logout'), 'refresh');
      }
    }
  }
  /******** END : CHECK SESSION AFTER LOGIN FOR ALL ADMIN PAGES ********/

  public function getLoggedInUserDetails($user_id, $type)
  /******** START : GET LOGGED IN ADMIN, AGENCY DETAILS ********/
  {
    $disp_name = '';
    $disp_sidebar_name = '';
    if ($type == 'admin')
    {
      $disp_name = 'Admin';
      $admin_data = $this->master_model->getRecords('apabi_india_admin', array('admin_id' => $user_id), 'admin_id, admin_name');

      if (count($admin_data) > 0)
      {
        $disp_name = $admin_data[0]['admin_name'];
      }
    }

    $data['disp_name'] = $disp_name;
    $data['disp_sidebar_name'] = $disp_sidebar_name;
    return $data;
  }
  /******** END : GET LOGGED IN ADMIN, AGENCY DETAILS ********/

  function insert_user_login_logs($user_id = 0, $user_type = 0, $type = 0)
  /******** START : MAINTAIN LOGIN - LOGOUT LOGS ********/
  {
    $this->load->helper('url');
    $this->load->library('user_agent');

    $add_log['user_id'] = $user_id;
    $add_log['user_type'] = $user_type;
    $add_log['ip_address'] = get_ip_address(); //general_helper.php
    $add_log['browser'] = $this->agent->browser() . " - " . $this->agent->version() . " - " . $this->agent->platform();
    $add_log['details'] = $_SERVER['HTTP_USER_AGENT'];
    $add_log['type'] = $type;
    $add_log['status'] = 1;
    $add_log['created_on'] = date('Y-m-d H:i:s');
    $this->master_model->insertRecord('apabi_india_login_logs', $add_log);
  }
  /******** END : MAINTAIN LOGIN - LOGOUT LOGS ********/

  public function password_encryption($password = '')
  /******** START : PASSWORD ENCRYPTION ********/
  {
    if ($_SERVER['HTTP_HOST'] == "localhost")
    {
      return $password;
    }
    else
    {
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('pass_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      return $aes->encrypt($password);

      /* $iv_length = openssl_cipher_iv_length($this->config->item('ciphering'));
        $options = 0;
        return $encpass = openssl_encrypt($password, $this->config->item('ciphering'), $this->config->item('encryption_key'), $options, $this->config->item('encryption_iv')); */
    }
  }
  /******** END : PASSWORD ENCRYPTION ********/

  public function password_decryption($password = '')
  /******** START : PASSWORD ENCRYPTION ********/
  {
    if ($_SERVER['HTTP_HOST'] == "localhost")
    {
      return $password;
    }
    else
    {
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('pass_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      return $aes->decrypt(trim($password));
    }
  }
  /******** END : PASSWORD ENCRYPTION ********/

  /******** START : MAINTAIN ALL GLOBAL LOGS ********/
  function insert_common_log($title = '', $tbl_name = '', $qry = '', $pk_id = '', $module_slug = '', $description = '', $posted_data = '', $insert_log_into = '')
  {
    $this->load->helper('url');
    $this->load->library('user_agent');

    $add_log['module_slug'] = $module_slug;
    $add_log['title'] = $title;
    $add_log['tbl_name'] = $tbl_name;
    $add_log['pk_id'] = $pk_id;
    $add_log['class_name'] = $this->router->fetch_class();
    $add_log['method_name'] = $this->router->fetch_method();
    $add_log['current_url'] = current_url();
    $add_log['qry'] = $qry;
    $add_log['posted_data'] = $posted_data;
    $add_log['description'] = $description;
    $add_log['ip_address'] = get_ip_address(); //general_helper.php

    $insert_tbl_name = 'apabi_india_common_logs';
    if ($insert_log_into != '')
    {
      $insert_tbl_name = $insert_log_into;
    }
    $this->master_model->insertRecord($insert_tbl_name, $add_log);
  }
  /******** END : MAINTAIN ALL GLOBAL LOGS ********/

  public function fun_validate_password($str) /******** START : CALLBACK CI VALIDATION FUNCTIONS FOR PASSWORD CHECK ********/
  {
    $result['flag'] = 'error';
    $result['response'] = '';

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $str)) 
    {
      $result['response'] = 'The password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.';
    }
    else
    {
      $result['flag'] = 'success';
    }

    return $result;
  } /******** END : CALLBACK CI VALIDATION FUNCTIONS FOR PASSWORD CHECK ********/
}
