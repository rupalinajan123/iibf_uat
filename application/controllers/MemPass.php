<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  class MemPass extends CI_Controller
  {
    public function __construct(){
      parent::__construct();
      $this->load->model('UserModel');
      $this->load->model('Master_model');
      $this->load->helper('pagination_helper');
      $this->load->library('pagination');
      $this->load->helper('upload_helper');
      $this->load->library('email');
      $this->load->model('Emailsending');
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
      
      error_reporting(E_ALL);
      ini_set("display_errors", 1);
      
		}
		/*public function index()
			{
			
			$regnumber='';
			$this->db->where('regnumber != ','');
      
      $this->db->order_by('regnumber','random');
			$this->db->limit(10);
      $res = $this->master_model->getRecords('member_registration','','regnumber,usrpassword');
			//echo $this->db->last_query();
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $aes = new CryptAES();
      
      $result_arr = array();
			if(!empty($res))
			{
			foreach($res as $val)
			{
			$key = $this->config->item('pass_key');				
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$decpass = $aes->decrypt(trim($val['usrpassword']));
			
			$res_data['regnumber'] = $val['regnumber'];
			$res_data['usrpassword'] = $decpass;
			
			$result_arr[] = $res_data;
			}
			}
      
      
      $data['res'] = $result_arr;
			$this->load->view('mempass/member_list',$data);
			
			}
		*/
    public function index()
    {			
      $result_arr = array();
			$search_str = '';
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $aes = new CryptAES();
      
			if(isset($_POST['btnSearch'])) 
      {
				$member['member_no'] = $search_str = $this->input->post('member_no');
				$regnumber = $member['member_no'];
				$this->db->where_in('regnumber',$regnumber, FALSE);
				$res = $this->master_model->getRecords('member_registration','','regnumber,usrpassword');
				//echo $this->db->last_query(); exit;
        if(!empty($res))
        {
          foreach($res as $val)
          {
            $key = $this->config->item('pass_key');				
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $decpass = $aes->decrypt(trim($val['usrpassword']));
            
            $res_data['regnumber'] = $val['regnumber'];
            $res_data['usrpassword'] = $decpass;
            
            $result_arr[] = $res_data;
					}
				}
			}
      
      // print_r($result_arr1);
      $data['res'] = $result_arr;
      $data['search_str'] = $search_str;
			$this->load->view('mempass/member_list',$data);
		}
		
    
	}
