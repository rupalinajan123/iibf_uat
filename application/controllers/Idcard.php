<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Idcard extends CI_Controller
{

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

  public function __construct()
  {
    parent::__construct();
    //load mPDF library
    //$this->load->library('m_pdf');
    $this->load->model('Master_model');
    //accedd denied due to GST
    //$this->master_model->warning();
    //exit;
  }

  public function index()
  {
    try {

      $member_number = $this->session->userdata('regnumber');
      $hisaarr = array(
        'member_number' => $this->session->userdata('regnumber')
      );
      $order = array(
        "card_id" => "Desc"
      );
      $cnthistory = $this->master_model->getRecords('member_idcard', $hisaarr, '', $order);
      if (count($cnthistory) == 0) {
        $insert_array = array(
          "member_number" => $this->session->userdata('regnumber'),
          "card_cnt" => 1,
          "dwn_date" => date("Y-m-d")
        );
        $this->master_model->insertRecord('member_idcard', $insert_array, true);
      } else {
        if ($cnthistory[0]['card_cnt'] == 2) {
          $new_cnt = 1;
        } else {
          $new_cnt = $cnthistory[0]['card_cnt'] + 1;
        }

        $insert_array = array(
          "member_number" => $this->session->userdata('regnumber'),
          "card_cnt" => $cnthistory[0]['card_cnt'] + 1,
          "dwn_date" => date("Y-m-d")
        );
        $this->master_model->insertRecord('member_idcard', $insert_array, true);
      }

      $dataarr = array(
        'regnumber' => $this->session->userdata('regnumber')
      );
      $user_info = $this->master_model->getRecords('member_registration', $dataarr);
      $name = $user_info[0]['namesub'] . " " . $user_info[0]['firstname'] . " " . $user_info[0]['middlename'] . " " . $user_info[0]['lastname'];

      $insarray = array('institude_id' => $user_info[0]['associatedinstitute']);
      $ins_info = $this->master_model->getRecords('institution_master', $insarray);
      if (isset($ins_info[0]['name'])) {
        $place_of_work = $ins_info[0]['name'];
      } else {
        $place_of_work = '';
      }

      $data = array("member_number" => $user_info[0]['regnumber'], "name" => $name, "dob" => $user_info[0]['dateofbirth'], "dateofissue" => date("Y-m-d"), "place_of_work" => $place_of_work);

      $html = $this->load->view('idcard', $data, true);
      $this->load->library('m_pdf');
      $pdf = $this->m_pdf->load();
      $pdfFilePath = "ID_Card_" . $member_id . ".pdf";
      $pdf->WriteHTML($html);
      $pdf->Output($pdfFilePath, "D");
    } catch (Exception $e) {
      echo "Message : " . $e->getMessage();
    }
  }

  public function view()
  {
    try {
      $member_number = $this->session->userdata('regnumber');
      $dataarr = array(
        'regnumber' => $this->session->userdata('regnumber')
      );
      $user_info = $this->master_model->getRecords('member_registration', $dataarr);
      $name = $user_info[0]['namesub'] . " " . $user_info[0]['firstname'] . " " . $user_info[0]['middlename'] . " " . $user_info[0]['lastname'];

      $insarray = array('institude_id' => $user_info[0]['associatedinstitute']);
      $ins_info = $this->master_model->getRecords('institution_master', $insarray);
      if (isset($ins_info[0]['name'])) {
        $place_of_work = $ins_info[0]['name'];
      } else {
        $place_of_work = '';
      }

      $data = array("member_number" => $user_info[0]['regnumber'], "name" => $name, "dob" => $user_info[0]['dateofbirth'], "dateofissue" => date("Y-m-d"), "place_of_work" => $place_of_work);

      $this->load->view('idcard', $data);
    } catch (Exception $e) {
      echo "Message : " . $e->getMessage();
    }
  }

  public function downloadidcard()
  {
    $currdate = date("Y-m-d");
    $regnumber = $this->session->userdata('regnumber');

    if ($this->uri->segment(3) != '') {

      $where = array('member_number' => $regnumber);
      $orderby = array("card_id" => "Desc");
      $getdwncnt = $this->master_model->getRecords('member_idcard', $where, 'card_cnt', $orderby);

      $where1 = array('regnumber' => $regnumber);
      $orderby1 = array("kyc_id" => "Desc");
      $chkuser = $this->master_model->getRecords('member_kyc', $where1, 'kyc_status,user_edited_date', $orderby1);
      $kyc_status = $chkuser[0]['kyc_status'];
      $edit_date = explode(" ", $chkuser[0]['user_edited_date']);

      if ($currdate == $edit_date[0]) {
        if (isset($getdwncnt[0]['card_cnt'])) {
          if ($getdwncnt[0]['card_cnt'] == 2) {
            if ($kyc_status == 1) {
              $mod = $getdwncnt[0]['card_cnt'] % 2;
              if ($mod == 0) {
                $dwn_cnt = 0;
              } else {
                $dwn_cnt = 1;
              }
            } elseif ($kyc_status == 0) {
              $dwn_cnt = 2;
            }
          } else {
            $mod = $getdwncnt[0]['card_cnt'] % 2;
            if ($mod == 0) {
              $dwn_cnt = 2;
            } else {
              $dwn_cnt = 1;
            }
          }
        } else {
          $dwn_cnt = 0;
        }
      } else {
        $where = array('member_number' => $regnumber, 'dwn_date' => $currdate);
        $orderby = array("card_id" => "Desc");
        $getdwncnt = $this->master_model->getRecords('member_idcard', $where, 'card_cnt', $orderby);
        if (isset($getdwncnt[0]['card_cnt'])) {
          $mod = $getdwncnt[0]['card_cnt'] % 2;
          if ($mod == 0) {
            $dwn_cnt = 2;
          } else {
            $dwn_cnt = 1;
          }
        } else {
          $dwn_cnt = 0;
        }
      }

      if ($dwn_cnt >= 2) {

        $where1 = array('regnumber' => $regnumber);
        $orderby1 = array("kyc_id" => "Desc");
        $chkuser = $this->master_model->getRecords('member_kyc', $where1, 'kyc_status,user_edited_date', $orderby1);
        $kyc_status = $chkuser[0]['kyc_status'];
        $error = "You  have completed your download attempt!!!";

        $data = array('middle_content' => 'download_idcard', "error" => $error, "kyc_status" => $kyc_status);
        $this->load->view('common_view', $data);
      } else {
        $member_number = $this->session->userdata('regnumber');
        $hisaarr = array(
          'member_number' => $this->session->userdata('regnumber')
        );
        $order = array(
          "card_id" => "Desc"
        );
        $cnthistory = $this->master_model->getRecords('member_idcard', $hisaarr, '', $order);
        if (count($cnthistory) == 0) {
          $insert_array = array(
            "member_number" => $this->session->userdata('regnumber'),
            "card_cnt" => 1,
            "dwn_date" => date("Y-m-d")
          );
          $this->master_model->insertRecord('member_idcard', $insert_array, true);
        } else {
          if ($cnthistory[0]['card_cnt'] == 2) {
            $new_cnt = 1;
          } else {
            $new_cnt = $cnthistory[0]['card_cnt'] + 1;
          }

          $insert_array = array(
            "member_number" => $this->session->userdata('regnumber'),
            "card_cnt" => $cnthistory[0]['card_cnt'] + 1,
            "dwn_date" => date("Y-m-d")
          );
          $this->master_model->insertRecord('member_idcard', $insert_array, true);
        }

        $dataarr = array(
          'regnumber' => $this->session->userdata('regnumber')
        );
        $user_info = $this->master_model->getRecords('member_registration', $dataarr);
        $name = $user_info[0]['namesub'] . " " . $user_info[0]['firstname'] . " " . $user_info[0]['middlename'] . " " . $user_info[0]['lastname'];

        $insarray = array('institude_id' => $user_info[0]['associatedinstitute']);
        $ins_info = $this->master_model->getRecords('institution_master', $insarray);
        if (isset($ins_info[0]['name'])) {
          $place_of_work = $ins_info[0]['name'];
        } else {
          $place_of_work = '';
        }

        $data = array("member_number" => $user_info[0]['regnumber'], "name" => $name, "dob" => $user_info[0]['dateofbirth'], "dateofissue" => date("Y-m-d"), "place_of_work" => $place_of_work);

        $html = $this->load->view('idcard', $data, true);
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdfFilePath = "ID_Card_" . $member_id . ".pdf";
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
      }
    } else {
      $where = array('member_number' => $regnumber);
      $orderby = array("card_id" => "Desc");
      $getdwncnt = $this->master_model->getRecords('member_idcard', $where, 'card_cnt', $orderby);

      $where1 = array('regnumber' => $regnumber);
      $orderby1 = array("kyc_id" => "Desc");
      $chkuser = $this->master_model->getRecords('member_kyc', $where1, 'kyc_status,user_edited_date', $orderby1);
      $kyc_status = $chkuser[0]['kyc_status'];
      $edit_date = explode(" ", $chkuser[0]['user_edited_date']);

      if ($currdate == $edit_date[0]) {
        if (isset($getdwncnt[0]['card_cnt'])) {
          if ($getdwncnt[0]['card_cnt'] == 2) {
            if ($kyc_status == 1) {
              $dwn_cnt = 0;
            } elseif ($kyc_status == 0) {
              $dwn_cnt = 2;
            }
          } else {
            $mod = $getdwncnt[0]['card_cnt'] % 2;
            if ($mod == 0) {
              $dwn_cnt = 2;
            } else {
              $dwn_cnt = 1;
            }
          }
        } else {
          $dwn_cnt = 0;
        }
      } else {
        $where = array('member_number' => $regnumber, 'dwn_date' => $currdate);
        $orderby = array("card_id" => "Desc");
        $getdwncnt = $this->master_model->getRecords('member_idcard', $where, 'card_cnt', $orderby);

        if (isset($getdwncnt[0]['card_cnt'])) {
          $mod = $getdwncnt[0]['card_cnt'] % 2;
          if ($mod == 0) {
            $dwn_cnt = 2;
          } else {
            $dwn_cnt = 1;
          }
        } else {
          $dwn_cnt = 0;
        }
      }



      if ($dwn_cnt >= 2) {
        $showlink = 'no';
        $error = "You have completed your download attempt!!!";
      } else {
        $showlink = 'yes';
        $error = '';
      }
      $data = array('middle_content' => 'download_idcard', 'dwn_cnt' => $dwn_cnt, 'kyc_status' => $kyc_status, 'showlink' => $showlink, 'error' => $error);
      $this->load->view('common_view', $data);
    }
  }
  //changes by pooja godse 
  public function downloadidcard_new()
  {

    $chkuser = array();
    $regnumber = $this->session->userdata('regnumber');
    $error = '';

    $where1 = array('regnumber' => $regnumber);
    $orderby1 = array("kyc_id" => "Desc");
    $chkuser = $this->master_model->getRecords('member_kyc', $where1, 'kyc_status,user_edited_date', $orderby1);



    if (isset($chkuser[0]['kyc_status'])) 
    {

      $kyc_status = $chkuser[0]['kyc_status'];
      $hisaarr = array('member_number' => $regnumber);
      $cnthistory = $this->master_model->getRecords('member_idcard_cnt', $hisaarr);

      if ($this->uri->segment(3) != '') {
        if (isset($cnthistory[0]['card_cnt'])) {
          if ($cnthistory[0]['card_cnt'] == 2) {
            $showlink = 'no';
            $error = "You have completed your eligible free 2 downloads of your Membership ID Card.";
          } else {

            //	$error = " You have completed 1 download of your Membership ID Card. Only 1 more free down load remains.";
            $showlink = 'yes';
            $this->managecnt(); // insert cnt in database
            $this->downloadcard(); // download membership card
          }
        } else {
          //$error = " You have completed 1 download of your Membership ID Card. Only 1 more free down load remains.";
          $showlink = 'yes';
          $this->managecnt(); // insert cnt in database
          $this->downloadcard();  // download membership card
        }
        $data = array('middle_content' => 'download_idcard', 'kyc_status' => $kyc_status, 'error' => $error);
        $this->load->view('common_view', $data);
      } else {
        if (!isset($cnthistory[0]['card_cnt'])) {

          $showlink = 'yes';
        } else {
          if ($cnthistory[0]['card_cnt'] == 2) {
            $showlink = 'no';
            //			$error = "You have completed your eligible free 2 downloads of your Membership ID Card.";
          } else {
            $showlink = 'yes';
            //	$error = " You have completed 1 download of your Membership ID Card. Only 1 more free down load remains.";
          }
        }
        $data = array('middle_content' => 'download_idcard', 'kyc_status' => $kyc_status, 'error' => $error, 'regnumber' => $regnumber);
        $this->load->view('common_view', $data);
      }
    } else {

      $regnumber = $this->session->userdata('regnumber');
      $error = '';
      $chkuser = array();
      //$where1 = array('regnumber'=> $regnumber);
      $chkuser = $this->master_model->getRecords('member_registration', array('regnumber' => $regnumber), 'kyc_status');

      //echo $this->session->userdata('regnumber');exit;
      $where1 = array('regnumber' => $regnumber);
      $orderby1 = array("did" => "Desc");
      $pay_status = $this->master_model->getRecords('duplicate_icard', $where1, 'pay_status,did', $orderby1);
      if ($chkuser[0]['kyc_status'] == 0 && $pay_status[0]['pay_status'] = 1) {
        
        $kyc_status = $chkuser[0]['kyc_status'];
        $hisaarr = array('member_number' => $regnumber);
        $cnthistory = $this->master_model->getRecords('member_idcard_cnt', $hisaarr);

        if ($this->uri->segment(3) != '') {
          if (isset($cnthistory[0]['card_cnt'])) {
            if ($cnthistory[0]['card_cnt'] == 2) {
              $showlink = 'no';
              $error = "You have completed your eligible free 2 downloads of your Membership ID Card.";
            } else {
              //				$error = " You have completed 1 download of your Membership ID Card. Only 1 more free down load remains.";
              $showlink = 'yes';
              $this->managecnt(); // insert cnt in database
              $this->downloadcard(); // download membership card
            }
          } else {
            //		$error = " You have completed 1 download of your Membership ID Card. Only 1 more free down load remains.";
            $showlink = 'yes';
            $this->managecnt(); // insert cnt in database
            $this->downloadcard();  // download membership card
          }
          $data = array('middle_content' => 'download_idcard', 'kyc_status' => $kyc_status, 'error' => $error);
          $this->load->view('common_view', $data);
        } else {
          if (!isset($cnthistory[0]['card_cnt'])) {
            $showlink = 'yes';
          } else {
            if ($cnthistory[0]['card_cnt'] == 2) {
              $showlink = 'no';
              $error = "You have completed your eligible free 2 downloads of your Membership ID Card.";
            } else {
              //	$error = " You have completed 1 download of your Membership ID Card. Only 1 more free down load remains.";
              $showlink = 'yes';
            }
          }
          $data = array('middle_content' => 'download_idcard', 'kyc_status' => $kyc_status, 'error' => $error, 'regnumber' => $regnumber);

          $this->load->view('common_view', $data);
        }
      }
    }
    //$kyc_status = 0;
    //$data=array('middle_content'=>'download_idcard','kyc_status'=>$kyc_status,'error'=>$error,'regnumber'=> $regnumber);
    //$this->load->view('common_view',$data);	

  }

  public function managecnt()
  {
    $regnumber = $this->session->userdata('regnumber');

    $hisaarr1 = array('member_number' => $regnumber);
    $order1 = array("card_id" => "Desc");
    $cnthistory1 = $this->master_model->getRecords('member_idcard', $hisaarr1, '', $order1);

    $hisaarr = array('member_number' => $regnumber);
    $cnthistory = $this->master_model->getRecords('member_idcard_cnt', $hisaarr);

    if (count($cnthistory1) == 0) {
      $insert_array1 = array(
        "member_number" => $regnumber,
        "card_cnt" => 1,
        "dwn_date" => date("Y-m-d")
      );
      $this->master_model->insertRecord('member_idcard', $insert_array1, true);
    } else {
      if ($cnthistory1[0]['card_cnt'] == 2) {
        $new_cnt = 1;
      } else {
        $new_cnt = $cnthistory1[0]['card_cnt'] + 1;
      }
      $insert_array1 = array(
        "member_number" => $regnumber,
        "card_cnt" => $cnthistory1[0]['card_cnt'] + 1,
        "dwn_date" => date("Y-m-d")
      );
      $this->master_model->insertRecord('member_idcard', $insert_array1, true);
    }
    if (!isset($cnthistory[0]['card_cnt'])) {
      $insert_array = array(
        "member_number" => $regnumber,
        "card_cnt" => 1,
        "dwn_date" => date("Y-m-d")
      );
      $this->master_model->insertRecord('member_idcard_cnt', $insert_array, true);
    } else {
      $where = array("member_number" => $regnumber);
      $insert_array = array(
        "member_number" => $regnumber,
        "card_cnt" => $cnthistory[0]['card_cnt'] + 1,
        "dwn_date" => date("Y-m-d")
      );
      $this->master_model->updateRecord('member_idcard_cnt', $insert_array, $where, true);
    }
  }

  public function downloadcard()
  {
    /* User Log Activities : Pooja */
    $uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'regid');
    $user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));
    $log_title = "Membership icard downloaded : " . $uerlog[0]['regid'];
    $log_message = serialize($user_info);
    $rId = $uerlog[0]['regid'];
    $regNo = $this->session->userdata('regnumber');
    storedUserActivity($log_title, $log_message, $rId, $regNo);
    /* Close User Log Actitives */

    $regnumber = $this->session->userdata('regnumber');
    $dataarr = array(
      'regnumber' => $regnumber
    );
    $user_info = $this->master_model->getRecords('member_registration', $dataarr);
    if (count($user_info)) {
      if ($user_info[0]['displayname'] != '') {
        $name = $user_info[0]['displayname'];
      } else {
        $name = $user_info[0]['namesub'] . " " . $user_info[0]['firstname'] . " " . $user_info[0]['middlename'] . " " . $user_info[0]['lastname'];
      }
    }
    $insarray = array('institude_id' => $user_info[0]['associatedinstitute']);
    $ins_info = $this->master_model->getRecords('institution_master', $insarray);
    if (isset($ins_info[0]['name'])) {
      $place_of_work = $ins_info[0]['name'];
    } else {
      $place_of_work = '';
    }

    $data1 = array("member_number" => $user_info[0]['regnumber'], "name" => $name, "dob" => $user_info[0]['dateofbirth'], "dateofissue" => date("Y-m-d"), "place_of_work" => $place_of_work);

    $html = $this->load->view('idcard', $data1, true);
    //echo $html; exit;
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();

    //$pdf->showImageErrors = true;
    $member_id = $this->session->userdata('regnumber');

    $pdfFilePath = "ID_Card_" . $member_id . ".pdf";
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "I");
  }



  public function test()
  {
    $this->test_two();
    $this->test_tree();
  }
  public function test_two()
  {
    echo "here";
  }
  public function test_tree()
  {
    echo "here123";
  }
  ############get download count #############
  public function getCount()
  {
    $hisaarr = array('member_number' => $this->session->userdata('regnumber'));
    $cnthistory = $this->master_model->getRecords('member_idcard_cnt', $hisaarr);
    $dowanload_count = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')), 'card_cnt');
    echo $dowanload_count[0]['card_cnt'];
  }
}
