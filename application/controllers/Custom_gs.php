<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_gs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model');
        $this->load->model('log_model');
        $this->load->model('Emailsending');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set("memory_limit", "-1");
    }

    public function get_client_ip() {
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

    public function update_candidate_count()
    {
        $print_query = "SELECT id,batch_code,total_registered_candidates,batch_status FROM agency_batch WHERE batch_status = 'Approved' AND total_registered_candidates = 0 ORDER BY id ASC LIMIT 100"; //ACTUAL QUERY
        
        $Result = $this->db->query($print_query); 
        $Rows = $Result->result_array();
        // echo "<pre>"; print_r($Rows); exit;
        foreach ($Rows as $key => $value) {
             
            $dra_members_query  = "SELECT count(regid) as can_count FROM dra_members WHERE batch_id = ".$value['id']." AND isdeleted = 0";
            $dra_members_result = $this->db->query($dra_members_query); 
            $arr_data = $dra_members_result->result_array();
            // echo $value['batch_code']."<pre>"; print_r($arr_data);
            
            if ($arr_data[0]['can_count'] != 0) {
                $actual_registered_candidates = $arr_data[0]['can_count'];
                $res = $this->master_model->updateRecord('agency_batch',['total_registered_candidates'=>$actual_registered_candidates],['id'=>$value['id']]);   
            }
        }
    }

    public function send_sms_gaurav()
    {
        $otp            = rand(100000, 999999);;
        $otp_sent_on    = date('Y-m-d H:i:s');
        $otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_login_with_otp'));

        $sms_text = $emailerstr[0]['sms_text'];
        $sms_text = str_replace('#OTP#', $otp, $sms_text);

        $mobile_no   = '8308318490';
        $message     = $sms_text;
        $template_id = $emailerstr[0]['sms_template_id'];
        $sender_id   = $emailerstr[0]['sms_sender'];        

        $sms_user     = 'IIBF';
        $sms_api_key  = 'c6b75a20f6XX';
        $sms_entityid = '1701162807222263362';

        if($mobile_no != '' && $message != '' && $template_id != '')
        {
            $mobile_no = '+91'.str_replace(",",",+91",$mobile_no);

            $xml_data = 'user='.$sms_user.'&key='.$sms_api_key.'&mobile='.$mobile_no.'&message='.$message.'&senderid='.$sender_id.'&accusage=1&entityid='.$sms_entityid.'&tempid='.$template_id;
            
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
            echo "Error : ".$err;
            echo "Response : ".$response; exit;
            if ($err) 
            {
                $return_arr['status'] = $status = 'fail';
                $return_arr['message'] = $err;
            } 
            else 
            {
                $response_arr = explode(",",$response); 
                if(count($response_arr) > 0 && trim($response_arr[0]) == 'fail')  
                {
                    $return_arr['status'] = $status = 'fail';
                }
                else
                {
                    $return_arr['status'] = $status = 'success';
                }
                $return_arr['message'] = $response;
                $return_arr['data'] = $xml_data;
            }
        }
        else
        {
            $return_arr['status'] = $status = 'fail';
            $return_arr['message'] = 'Invalid parameter supplied to function';
        }        
    }

    public function my_ipaddress()
    {
        $my_ip = $this->get_client_ip();
        echo '<span style="text-align: center;font-size: 40;font-weight: bold;">'.$my_ip.'</span>';
    }

    public function sms_test()
    {
      $sms_final_str1 = 'You have successfully subscribed for IIBF Finquest.';
      $r=$this->master_model->send_sms_common_all(8308318490, $sms_final_str1, '1707163293328405009', 'IIBFCO');
      print_r($r);
    }

    public function check_sms_trustsignal()
    {
        $mobile  = '9881191703';
        $message = 'Thanks for enrolling for BCBF-Payment Bank-exam. Your exam form and fee 105 is received vide transaction 105105. Refer email for details. IIBF Team';
        $res     = $this->master_model->send_sms_trustsignal(intval($mobile), $message, 'J0DWe39nR', '', '', 'IIBFSM');
        echo '<pre>';
        print_r($res);
    }

    public function check_sms_trustsignal_vishal()
    {
        $mobile  = '7588553132';
        $message = 'Thanks for enrolling for BCBF-Payment Bank-exam. Your exam form and fee 105 is received vide transaction 105105. Refer email for details. IIBF Team';
        $res     = $this->send_sms_trustsignal(intval($mobile), $message, 'J0DWe39nR', '', '', 'IIBFSM');
        echo '<pre>';
        print_r($res);
    }
}
