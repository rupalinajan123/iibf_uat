<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_settlement extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('master_model');
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper');
    $this->load->helper('file');
    $this->load->helper('getregnumber_helper');
  }

  function custom_invoice()
  {
    /* $exam_invoice_data = $this->master_model->getRecords('exam_invoice', array('app_type' => 'BC', 'invoice_no !='=>''));
    if(count($exam_invoice_data) > 0)
    {
      $i = 1;
      foreach($exam_invoice_data as $res)
      {
        $current_invoice_no = $res['invoice_no'];
        $new_invoice_no = str_replace('IIBFBC/', 'BC/',$current_invoice_no);

        echo '<br><br>'.$i.'. '.$current_invoice_no.' => '.$new_invoice_no.'<br>';

        $this->master_model->updateRecord('exam_invoice',array('invoice_no'=>$new_invoice_no), array('invoice_id' => $res['invoice_id']));
        _pq();
        echo '<br>'.$invoice_img_path = genarate_iibf_bcbf_exam_invoice($res['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php
        $i++;
        //exit;
      }
    } */
    
    $exam_invoice_data = $this->master_model->getRecords('exam_invoice', array('app_type' => 'BC', 'invoice_no !='=>''));
    if(count($exam_invoice_data) > 0)
    {
      foreach($exam_invoice_data as $res)
      {
        //$invoice_id = '5006905';
        $invoice_id = $res['invoice_id'];
        echo '<br>'.$invoice_img_path = genarate_iibf_bcbf_exam_invoice($invoice_id); // Use helpers/iibfbcbf/iibf_bcbf_helper.php 
      }
    }
  }

  function invoice_data_settlement()
  {
    $this->db->join('iibfbcbf_payment_transaction pt', 'pt.receipt_no = ei.receipt_no', 'INNER');
    $this->db->join('iibfbcbf_member_exam me', 'me.member_exam_id = pt.exam_ids', 'INNER');
    $this->db->join('iibfbcbf_exam_centre_master ecm', 'ecm.centre_code = me.exam_centre_code AND ecm.exam_name = ei.exam_code AND ecm.exam_period = ei.exam_period', 'INNER');
    $exam_invoice_data = $this->master_model->getRecords('exam_invoice ei', array('ei.app_type' => 'BC', 'ei.invoice_no !='=>'', 'pt.payment_mode !=' => 'Bulk'), 'ei.invoice_id, ei.center_code, ei.center_name, ei.state_of_center, ei.state_name, ei.invoice_no, ei.fee_amt, ei.cgst_rate, ei.cgst_amt, ei.sgst_rate, ei.sgst_amt, ei.cs_total, ei.igst_rate, ei.igst_amt, ei.igst_total, ei.tax_type, pt.id, pt.exam_ids, me.member_exam_id, me.exam_centre_code, ecm.state_code, ei.exam_code');
    echo count($exam_invoice_data);
    //_pa($exam_invoice_data);

    $err_cnt = 0;
    if(count($exam_invoice_data) > 0)
    {
      foreach($exam_invoice_data as $res)
      {
        if($res['state_code'] == 'MAH')
        {
          if($res['tax_type'] == 'Intra' && $res['state_of_center'] == $res['state_code']) {} //VALID
          else
          {
            _pa($res);
            $err_cnt++;
          }
        }
        else
        {
          if($res['tax_type'] == 'Inter' && $res['state_of_center'] == $res['state_code']) {} //VALID
          else
          {
            _pa($res);
            $err_cnt++;
          }
        }
      }
    }

    echo '<br> Error Count : '.$err_cnt;
  }

  function update_regnumber()
  { exit;
    $exam_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.pay_status' => '1', 'me.regnumber'=>''), 'me.member_exam_id, me.candidate_id');
    if(count($exam_data) > 0)
    {
      $cnt = 0;
      foreach($exam_data as $res)
      {
        $cand_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('candidate_id' => $res['candidate_id'], 'regnumber !=' =>''), 'regnumber');
        if(count($cand_data) > 0)
        {
          $this->master_model->updateRecord('iibfbcbf_member_exam',array('regnumber'=>$cand_data[0]['regnumber']), array('member_exam_id' => $res['member_exam_id']));

          $cnt++;
        }
      }

      echo $cnt.' records updated';
    }
  }

  function update_pt_id()
  { exit;
    /* $this->db->where_in('member_exam_id', array(8,10)); */
    $exam_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array(/* 'me.pay_status' => '1', */ 'me.pt_id'=>'0'), 'me.member_exam_id, me.exam_code, me.exam_period');
    
    if(count($exam_data) > 0)
    {
      $cnt = 0;
      foreach($exam_data as $res)
      {
        $this->db->where(" FIND_IN_SET(".$res['member_exam_id'].", exam_ids) ");
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('exam_code' =>$res['exam_code'], 'exam_period' =>$res['exam_period']/* , 'status'=>'1' */), 'id');
        if(count($payment_data) > 0)
        {
          if(count($payment_data) > 1) { echo '<br>'.count($payment_data).' >> '.$res['member_exam_id']; }
          //$this->master_model->updateRecord('iibfbcbf_member_exam',array('pt_id'=>$payment_data[0]['id']), array('member_exam_id' => $res['member_exam_id']));

          $cnt++;
        }
      }

      echo '<br>'.$cnt.' records updated';
    }
  }

  function reset_kyc_records_bcbf()
  {
    $candidate_photo_path = 'uploads/iibfbcbf/photo';
    $candidate_sign_path = 'uploads/iibfbcbf/sign';
    $id_proof_file_path = 'uploads/iibfbcbf/id_proof';

    $this->db->where(" ((kyc_status != 0 OR recommender_id != 0 OR approver_id != 0) OR (regnumber != '' AND (candidate_photo = '' OR candidate_sign = '' OR id_proof_file = '')))");
    $kyc_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array(), 'candidate_id, regnumber, img_ediited_on, candidate_photo, candidate_sign, id_proof_file, kyc_photo_flag, kyc_sign_flag, kyc_id_card_flag, kyc_status, kyc_recommender_status, recommender_id, kyc_approver_status, approver_id, kyc_recommender_date, kyc_approver_date');
    _pq();
    _pa($kyc_data);

    if(count($kyc_data) > 0)
    {
      foreach($kyc_data as $res)
      {
        $up_data = array();

        if($res['candidate_photo'] == '')
        {
          if(file_exists($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpg'))
          {
            //echo '<br>exist '.$candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpg'; exit;
            $up_data['candidate_photo'] = 'photo_'.$res['regnumber'].'.jpg';
            rename($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpg', $candidate_photo_path.'/photo_'.$res['regnumber'].'.jpg');
          }
          else if(file_exists($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpeg'))
          {
            //echo '<br>exist '.$candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpeg'; exit;
            $up_data['candidate_photo'] = 'photo_'.$res['regnumber'].'.jpeg';
            rename($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpeg', $candidate_photo_path.'/photo_'.$res['regnumber'].'.jpeg');
          }
          else if(file_exists($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.png'))
          {
            //echo '<br>exist '.$candidate_photo_path.'/k_photo_'.$res['regnumber'].'.png'; exit;
            $up_data['candidate_photo'] = 'photo_'.$res['regnumber'].'.png';
            rename($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.png', $candidate_photo_path.'/photo_'.$res['regnumber'].'.png');
          }
          else
          {
            copy('uploads/photoo.jpg', $candidate_photo_path.'/photo_'.$res['regnumber'].'.jpg');
            $up_data['candidate_photo'] = 'photo_'.$res['regnumber'].'.jpg';
          }
        }

        if($res['candidate_sign'] == '')
        {
          if(file_exists($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpg'))
          {
            //echo '<br>exist '.$candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpg'; exit;
            $up_data['candidate_sign'] = 'sign_'.$res['regnumber'].'.jpg';
            rename($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpg', $candidate_sign_path.'/sign_'.$res['regnumber'].'.jpg');
          }
          else if(file_exists($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpeg'))
          {
            //echo '<br>exist '.$candidate_sign_path.'/sign_'.$res['regnumber'].'.jpeg'; exit;
            $up_data['candidate_sign'] = 'sign_'.$res['regnumber'].'.jpeg';
            rename($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpeg', $candidate_sign_path.'/sign_'.$res['regnumber'].'.jpeg');
          }
          else if(file_exists($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.png'))
          {
            //echo '<br>exist '.$candidate_sign_path.'/sign_'.$res['regnumber'].'.png'; exit;
            $up_data['candidate_sign'] = 'sign_'.$res['regnumber'].'.png';
            rename($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.png', $candidate_sign_path.'/sign_'.$res['regnumber'].'.png');
          }
          else
          {
            copy('uploads/signoo.jpg', $candidate_sign_path.'/sign_'.$res['regnumber'].'.jpg');
            $up_data['candidate_sign'] = 'sign_'.$res['regnumber'].'.jpg';
          }
        }

        if($res['id_proof_file'] == '')
        {
          if(file_exists($id_proof_file_path.'/k_id_proof_'.$res['regnumber'].'.jpg'))
          {
            //echo '<br>exist '.$id_proof_file_path.'/k_id_proof_'.$res['regnumber'].'.jpg'; exit;
            $up_data['id_proof_file'] = 'id_proof_'.$res['regnumber'].'.jpg';
            rename($id_proof_file_path.'/k_id_proof_'.$res['regnumber'].'.jpg', $id_proof_file_path.'/id_proof_'.$res['regnumber'].'.jpg');
          }
          else if(file_exists($id_proof_file_path.'/k_id_proof_'.$res['regnumber'].'.jpeg'))
          {
            //echo '<br>exist '.$id_proof_file_path.'/id_proof_'.$res['regnumber'].'.jpeg'; exit;
            $up_data['id_proof_file'] = 'id_proof_'.$res['regnumber'].'.jpeg';
            rename($id_proof_file_path.'/k_id_proof_'.$res['regnumber'].'.jpeg', $id_proof_file_path.'/id_proof_'.$res['regnumber'].'.jpeg');
          }
          else if(file_exists($id_proof_file_path.'/k_id_proof_'.$res['regnumber'].'.png'))
          {
            //echo '<br>exist '.$id_proof_file_path.'/id_proof_'.$res['regnumber'].'.png'; exit;
            $up_data['id_proof_file'] = 'id_proof_'.$res['regnumber'].'.png';
            rename($id_proof_file_path.'/k_id_proof_'.$res['regnumber'].'.png', $id_proof_file_path.'/id_proof_'.$res['regnumber'].'.png');
          }
          else
          {
            copy('uploads/id_proofoo.jpg', $id_proof_file_path.'/id_proof_'.$res['regnumber'].'.jpg');
            $up_data['id_proof_file'] = 'id_proof_'.$res['regnumber'].'.jpg';
          }
        }

        $up_data['img_ediited_on'] = '';
        $up_data['kyc_photo_flag'] = '';
        $up_data['kyc_sign_flag'] = '';
        $up_data['kyc_id_card_flag'] = '';
        $up_data['kyc_status'] = '0';
        $up_data['kyc_recommender_status'] = '';
        $up_data['recommender_id'] = '0';
        $up_data['kyc_approver_status'] = '';
        $up_data['approver_id'] = '0';
        $up_data['kyc_recommender_date'] = '';
        $up_data['kyc_approver_date'] = '';
        if(count($up_data) > 0)
        {
          $this->master_model->updateRecord('iibfbcbf_batch_candidates',$up_data, array('candidate_id'=>$res['candidate_id']));
          echo '<br>'; _pq();
        }
      }
    }
  }
  
  function reset_kyc_records_dra()
  {
    $candidate_photo_path = 'uploads/iibfdra';
    $candidate_sign_path = 'uploads/iibfdra';
    $id_proof_file_path = 'uploads/iibfdra';

    $this->db->where(" ((kyc_status != 0 OR recommender_id != 0 OR approver_id != 0) OR (regnumber != '' AND (scannedphoto = '' OR scannedsignaturephoto = '' OR idproofphoto = '')))");
    $kyc_data = $this->master_model->getRecords('dra_members', array(), 'regid, regnumber, img_ediited_on, scannedphoto, scannedsignaturephoto, idproofphoto, kyc_photo_flag, kyc_sign_flag, kyc_id_card_flag, kyc_status, kyc_recommender_status, recommender_id, kyc_approver_status, approver_id, kyc_recommender_date, kyc_approver_date');
    _pq();
    //_pa($kyc_data);

    if(count($kyc_data) > 0)
    {
      foreach($kyc_data as $res)
      {
        $up_data = array();

        if($res['scannedphoto'] == '')
        {
          if(file_exists($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpg'))
          {
            $up_data['scannedphoto'] = 'photo_'.$res['regnumber'].'.jpg';
            rename($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpg', $candidate_photo_path.'/photo_'.$res['regnumber'].'.jpg');
          }
          else if(file_exists($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpeg'))
          {
            $up_data['scannedphoto'] = 'photo_'.$res['regnumber'].'.jpeg';
            rename($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.jpeg', $candidate_photo_path.'/photo_'.$res['regnumber'].'.jpeg');
          }
          else if(file_exists($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.png'))
          {
            $up_data['scannedphoto'] = 'photo_'.$res['regnumber'].'.png';
            rename($candidate_photo_path.'/k_photo_'.$res['regnumber'].'.png', $candidate_photo_path.'/photo_'.$res['regnumber'].'.png');
          }
          else
          {
            /* copy('uploads/photoo.jpg', $candidate_photo_path.'/photo_'.$res['regnumber'].'.jpg');
            $up_data['scannedphoto'] = 'photo_'.$res['regnumber'].'.jpg'; */
          }
        }

        if($res['scannedsignaturephoto'] == '')
        {
          if(file_exists($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpg'))
          {
            $up_data['scannedsignaturephoto'] = 'sign_'.$res['regnumber'].'.jpg';
            rename($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpg', $candidate_sign_path.'/sign_'.$res['regnumber'].'.jpg');
          }
          else if(file_exists($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpeg'))
          {
            $up_data['scannedsignaturephoto'] = 'sign_'.$res['regnumber'].'.jpeg';
            rename($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.jpeg', $candidate_sign_path.'/sign_'.$res['regnumber'].'.jpeg');
          }
          else if(file_exists($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.png'))
          {
            $up_data['scannedsignaturephoto'] = 'sign_'.$res['regnumber'].'.png';
            rename($candidate_sign_path.'/k_sign_'.$res['regnumber'].'.png', $candidate_sign_path.'/sign_'.$res['regnumber'].'.png');
          }
          else
          {
            /* copy('uploads/signoo.jpg', $candidate_sign_path.'/sign_'.$res['regnumber'].'.jpg');
            $up_data['scannedsignaturephoto'] = 'sign_'.$res['regnumber'].'.jpg'; */
          }
        }

        if($res['idproofphoto'] == '')
        {
          if(file_exists($id_proof_file_path.'/k_idproof_'.$res['regnumber'].'.jpg'))
          {
            $up_data['idproofphoto'] = 'idproof_'.$res['regnumber'].'.jpg';
            rename($id_proof_file_path.'/k_idproof_'.$res['regnumber'].'.jpg', $id_proof_file_path.'/idproof_'.$res['regnumber'].'.jpg');
          }
          else if(file_exists($id_proof_file_path.'/k_idproof_'.$res['regnumber'].'.jpeg'))
          {
            $up_data['idproofphoto'] = 'idproof_'.$res['regnumber'].'.jpeg';
            rename($id_proof_file_path.'/k_idproof_'.$res['regnumber'].'.jpeg', $id_proof_file_path.'/idproof_'.$res['regnumber'].'.jpeg');
          }
          else if(file_exists($id_proof_file_path.'/k_idproof_'.$res['regnumber'].'.png'))
          {
            $up_data['idproofphoto'] = 'idproof_'.$res['regnumber'].'.png';
            rename($id_proof_file_path.'/k_idproof_'.$res['regnumber'].'.png', $id_proof_file_path.'/idproof_'.$res['regnumber'].'.png');
          }
          else
          {
            /* copy('uploads/id_proofoo.jpg', $id_proof_file_path.'/idproof_'.$res['regnumber'].'.jpg');
            $up_data['idproofphoto'] = 'idproof_'.$res['regnumber'].'.jpg'; */
          }
        }

        $up_data['img_ediited_on'] = '';
        $up_data['kyc_photo_flag'] = '';
        $up_data['kyc_sign_flag'] = '';
        $up_data['kyc_id_card_flag'] = '';
        $up_data['kyc_status'] = '0';
        $up_data['kyc_recommender_status'] = '';
        $up_data['recommender_id'] = '0';
        $up_data['kyc_approver_status'] = '';
        $up_data['approver_id'] = '0';
        $up_data['kyc_recommender_date'] = '';
        $up_data['kyc_approver_date'] = '';
        if(count($up_data) > 0)
        {
          $this->master_model->updateRecord('dra_members',$up_data, array('regid'=>$res['regid']));
          echo '<br><br>'; _pq();
        }
      }
    }
  }

  function update_kyc_eligible_date_bcbf()
  {
    $this->db->where(" (regnumber IS NOT NULL AND regnumber != '' ) ");
    $this->db->where(" (kyc_eligible_date IS NULL OR kyc_eligible_date = '0000-00-00' ) ");
    $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array(), 'candidate_id, regnumber, kyc_eligible_date, created_on');
    _pq();

    '<br>Count : '.count($candidate_data);
    
    if(count($candidate_data) > 0)
    {
      foreach($candidate_data as $cand_res)
      {
        $this->db->limit(1);
        $this->db->order_by('member_exam_id','ASC');
        $exam_data = $this->master_model->getRecords('iibfbcbf_member_exam', array('candidate_id'=>$cand_res['candidate_id'], 'pay_status'=>'1'), 'member_exam_id, created_on');

        if(count($exam_data) > 0 && $exam_data[0]['created_on'] != '0000-00-00 00:00:00')
        {
          $update_date = date('Y-m-d', strtotime("+3days", strtotime($exam_data[0]['created_on'])));
        }
        else if($cand_res['createdon'] != '0000-00-00 00:00:00')
        {
          $update_date = date('Y-m-d', strtotime("+3days", strtotime($cand_res['created_on'])));
        }

        if(count($update_date) > 0)
        {
          $this->master_model->updateRecord('iibfbcbf_batch_candidates',array('kyc_eligible_date'=>$update_date), array('candidate_id'=>$cand_res['candidate_id']));
          echo '<br>'; _pq();
        }
      }
    }
  }

  function update_kyc_eligible_date_dra()
  {
    $this->db->where(" (regnumber IS NOT NULL AND regnumber != '' ) ");
    $this->db->where(" (kyc_eligible_date IS NULL OR kyc_eligible_date = '0000-00-00' ) ");
    $candidate_data = $this->master_model->getRecords('dra_members', array(), 'regid, regnumber, kyc_eligible_date, createdon');
    _pq();

    '<br>Count : '.count($candidate_data);
    
    if(count($candidate_data) > 0)
    {
      foreach($candidate_data as $cand_res)
      {
        $this->db->limit(1);
        $this->db->order_by('id','ASC');
        $exam_data = $this->master_model->getRecords('dra_member_exam', array('regid'=>$cand_res['regid'], 'pay_status'=>'1'), 'id, created_on');

        if(count($exam_data) > 0 && $exam_data[0]['created_on'] != '0000-00-00 00:00:00')
        {
          $update_date = date('Y-m-d', strtotime("+3days", strtotime($exam_data[0]['created_on'])));
        }
        else if($cand_res['createdon'] != '0000-00-00 00:00:00')
        {
          $update_date = date('Y-m-d', strtotime("+3days", strtotime($cand_res['createdon'])));
        }

        if(count($update_date) > 0)
        {
          $this->master_model->updateRecord('dra_members',array('kyc_eligible_date'=>$update_date), array('regid'=>$cand_res['regid']));
          echo '<br>'; _pq();
        }
      }
    }
  }
}
