<!DOCTYPE html>
<html>
<style>
  .modal-dialog {
    position: relative;
    display: table;
    overflow-y: auto;
    overflow-x: auto;
    width: 920px;
    min-width: 300px;
  }

  #confirm .modal-dialog {
    position: relative;
    display: table;
    overflow-y: auto;
    overflow-x: auto;
    width: 420px;
    min-width: 400px;
  }

  .skin-blue .main-header .navbar {
    background-color: #fff;
  }

  body.layout-top-nav .main-header h1 {
    color: #0699dd;
    margin-bottom: 0;
    margin-top: 30px;
  }

  .container {
    position: relative;
  }

  .box-header.with-border {
    background-color: #7fd1ea;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    margin-bottom: 10px;
  }

  .header_blue {
    background-color: #2ea0e2 !important;
    color: #fff !important;
    margin-bottom: 0 !important;
  }

  .box {
    border: none;
    box-shadow: none;
    border-radius: 0;
    margin-bottom: 0;
  }

  .nobg {
    background: none !important;
    border: none !important;
  }

  .box-title-hd {
    color: #3c8dbc;
    font-size: 16px;
    margin: 0;
  }

  .blue_bg {
    background-color: #e7f3ff;
  }

  .m_t_15 {
    margin-top: 15px;
  }

  .main-footer {
    padding-left: 160px;
    padding-right: 160px;
  }

  .content-header>h1 {
    font-size: 22px;
    font-weight: 600;
  }

  h4 {
    margin-top: 5px;
    margin-bottom: 10px !important;
    font-size: 14px;
    line-height: 18px;
    padding: 0 5px;
    font-weight: 600;
    text-align: justify;
  }

  .form-horizontal .control-label {
    padding-top: 4px;
  }

  .pad_top_2 {
    padding-top: 2px !important;
  }

  .pad_top_0 {
    padding-top: 0px !important;
  }

  div.form-group:nth-child(odd) {
    background-color: #dcf1fc;
    padding: 5px 0;
  }

  #confirmBox {
    display: none;
    background-color: #eee;
    border-radius: 5px;
    border: 1px solid #aaa;
    position: fixed;
    width: 300px;
    left: 50%;
    margin-left: -150px;
    padding: 6px 8px 8px;
    box-sizing: border-box;
    text-align: center;
    z-index: 1;
    box-shadow: 0 1px 3px #000;
  }

  #confirmBox .button {
    background-color: #ccc;
    display: inline-block;
    border-radius: 3px;
    border: 1px solid #aaa;
    padding: 2px;
    text-align: center;
    width: 80px;
    cursor: pointer;
  }

  #confirmBox .button:hover {
    background-color: #ddd;
  }

  #confirmBox .message {
    text-align: left;
    margin-bottom: 8px;
  }

  .form-group {
    margin-bottom: 10px;
  }

  .form-horizontal .form-group {
    margin-left: 0;
    margin-right: 0;
  }

  .form-control {
    border-color: #888;
  }

  .form-horizontal .control-label {
    font-weight: normal;
  }

  a.forget {
    color: #9d0000;
  }

  a.forget:hover {
    color: #9d0000;
    text-decoration: underline;
  }

  ol li {
    line-height: 18px;
  }

  .example {
    text-align: left !important;
    padding: 0 10px;
  }

  .note {
    font-size: 12px;
    line-height: 16px;
    display: inline-block;
    margin: 5px 0 0 0;
    color: blue;
  }

  .note-error {
    color: rgb(185, 74, 72);
    font-size: small;
  }
</style>

<head>
  <?php $this->load->view('scribe_form/inc_header'); ?>
  <?php //print_r($undergraduate);echo'<pre>';print_r($graduate);die;//print_r($this->session->userdata);echo "<br>"; 
  ?>
  <?php //print_r($member_details);echo "bjhcvjv";die
  ?>
  <?php //print_r($subject_name);die;
  ?>
</head>

<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">
    <?php $this->load->view('scribe_form/inc_navbar'); ?>
    <div class="container">
      <section class="content">
        <section class="content-header">
          <h1 class="register">Apply For Scribe</h1><br />
        </section>
        <div id="already_exist" style="display:none">
          <div class="alert alert-danger" style="font-size:150%"> <?php echo 'Scribe is not available for given exam date.'; ?> </div>
        </div>

        <div class="box box-info" style="padding: 0 10px 0 10px;">
          <?php if ($this->session->flashdata('error') != '') { ?>
            <div class="alert alert-danger alert-dismissible" id="error_id">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <?php echo $this->session->flashdata('error'); ?>
            </div>

          <?php }
          if ($this->session->flashdata('success') != '') { ?>
            <div class="alert alert-success alert-dismissible" id="success_id">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <?php echo $this->session->flashdata('success'); ?>
            </div>
          <?php } ?>

          <form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" action="<?php echo site_url('Scribe_form/getDetails_Scribe/'); ?>" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" class="csrf_iibf_name" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" autocomplete='false'>
            <?php
            if (count($member_details) > 0 && isset($member_details[0]['regnumber'])) {
              $regnumber = $member_details[0]['regnumber'];
              $readonly = "readonly='readonly'";
            } else if ($this->session->has_userdata('session_array')) {
              $regnumber = (isset($session_data['member_no'])) ? $session_data['member_no'] : '';
              $readonly = "readonly='readonly'";
            } else {
              $regnumber = '';
            }

            if ($regnumber != "") {  ?>
              <div class="form-group text-center">

                <div class="col-sm-8">
                  <input type="hidden" class="form-control " id="member_no" name="member_no" placeholder="Membership/Registration No." value="<?php echo $regnumber; ?>" <?php echo $readonly; ?>>
                </div>
              </div>

            <?php } ?>
            <input type="hidden" class="form-control " id="exam_code" name="exam_code" value="<?php echo $exam_name[0]['exam_code']; ?>">
            <!-- <input type="hidden" class="form-control " id="exam_code" name="exam_code" value="<?php echo $member_details[0]['exam_date']; ?>"> -->
            <section class="content">
              <div class="row">
                <div class="col-md-12">

                  <div class="box box-info">

                    <div class="box-body">
                      <?php //print_r($center_master); 
                      ?>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">First Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-2">
                          <input type="text" class="form-control" id="sel_namesub" name="sel_namesub" value="<?php if (isset($member_details[0]['namesub'])) {
                                                                                                                echo $member_details[0]['namesub'];
                                                                                                              } ?>" readonly="readonly" placeholder="Prefix" required>
                        </div>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php if (isset($member_details[0]['firstname'])) {
                                                                                                                                              echo $member_details[0]['firstname'];
                                                                                                                                            } ?>" readonly="readonly">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Middle Name&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="middlename" id="middlename" value="<?php if (isset($member_details[0]['middlename'])) {
                                                                                                              echo $member_details[0]['middlename'];
                                                                                                            } ?>" readonly="readonly" placeholder="Middle Name" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Last Name&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="lastname" id="lastname" value="<?php if (isset($member_details[0]['lastname'])) {
                                                                                                          echo $member_details[0]['lastname'];
                                                                                                        } ?>" placeholder="Last Name" readonly="readonly" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Email&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php if (isset($member_details[0]['email'])) {
                                                                                                                        echo $member_details[0]['email'];
                                                                                                                      } ?>" readonly="readonly" required>
                          <span class="error"> </span>
                        </div>
                      </div>


                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Mobile&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" value="<?php if (isset($member_details[0]['mobile'])) {
                                                                                                                          echo $member_details[0]['mobile'];
                                                                                                                        } ?>" readonly="readonly" required>
                          <span class="error"></span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Center Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="tel" class="form-control" id="selCenterName" name="selCenterName" value="<?php if (isset($member_details[0]['center_name'])) {
                                                                                                                  echo $member_details[0]['center_name'];
                                                                                                                } ?>" required readonly="readonly">
                          <span class="error"></span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Center Code&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="tel" class="form-control" id="selCenterCode" name="selCenterCode" value="<?php if (isset($member_details[0]['center_code'])) {
                                                                                                                  echo $member_details[0]['center_code'];
                                                                                                                } ?>" required readonly="readonly">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Exam Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="exam_name" name="exam_name" placeholder="exam name" value="<?php if (isset($exam_name[0]['description'])) {
                                                                                                                                    echo $exam_name[0]['description'];
                                                                                                                                  } ?>" readonly="readonly" required>
                          <span class="error"> </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Subject Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="subject" name="subject" placeholder="subject" value="<?php if (isset($subject_name[0]['subject_description'])) {
                                                                                                                              echo $subject_name[0]['subject_description'];
                                                                                                                            } ?>" readonly="readonly" required>
                          <span class="error"> </span>
                          <input type="hidden" id="subject_code" name="subject_code" value="<?php if (isset($subject_name[0]['subject_code'])) {
                                                                                              echo $subject_name[0]['subject_code'];
                                                                                            } ?>">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Exam Date&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="exam_date" id="exam_date" value="<?php if (isset($member_details[0]['exam_date'])) {
                                                                                                            echo $member_details[0]['exam_date'];
                                                                                                          } ?>" required readonly="readonly" placeholder="Exam Date" />
                        </div>
                      </div>


                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Person with Benchmark Disability</label>
                        <div class="col-sm-3">
                          <input value="Y" name="benchmark_disability" id="benchmark_disability" type="radio" <?php echo set_radio('benchmark_disability', 'Y'); ?> class="benchmark_disability_y" checked="checked">
                          Yes
                          <input value="N" name="benchmark_disability" id="benchmark_disability" type="radio" <?php echo set_radio('benchmark_disability', 'N'); ?> class="benchmark_disability_n" disabled>
                          No
                          <span class="error"></span>
                          <span id="benchmark_disability_err"></span>
                        </div>
                      </div>

                      <div id="benchmark_disability_div" style="display:none;">

                        <div class="form-group">
                          <label for="roleid" class="col-sm-4 control-label">Visually impaired</label>
                          <div class="col-sm-3">
                            <input value="Y" name="visually_impaired" id="visually_impaired" type="radio" <?php echo set_radio('visually_impaired', 'Y'); ?> class="visually_impaired_y">
                            Yes
                            <input value="N" name="visually_impaired" id="visually_impaired" type="radio" <?php echo set_radio('visually_impaired', 'N'); ?> class="visually_impaired_n" checked="checked">
                            No <span class="error"></span>
                          </div>
                        </div>

                        <div class="form-group" id="vis_imp_cert_div" style="display:none;">
                          <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
                          <div class="col-sm-5">
                            <input onchange="readURL(this,'scanned_vis_imp_cert_preview')" type="file" name="scanned_vis_imp_cert" id="scanned_vis_imp_cert" required style="word-wrap: break-word;width: 100%;">
                            <input type="hidden" id="hidden_vis_imp_cert" name="hidden_vis_imp_cert">
                            <div id="error_vis_imp_cert"></div>
                            <br>
                            <div id="error_vis_imp_cert_size"></div>
                            <span class="vis_imp_cert_text" style="display:none;"></span> <span class="error"> </span>
                            <img class="mem_reg_img_prev" id="scanned_vis_imp_cert_preview" height="100" width="100" src="/assets/images/default1.png" />
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="roleid" class="col-sm-4 control-label">Orthopedically handicapped</label>
                          <div class="col-sm-3">
                            <input value="Y" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio" <?php echo set_radio('orthopedically_handicapped', 'Y'); ?> class="orthopedically_handicapped_y">
                            Yes
                            <input value="N" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio" <?php echo set_radio('orthopedically_handicapped', 'N'); ?> class="orthopedically_handicapped_n" checked="checked">
                            No <span class="error"></span>
                          </div>
                        </div>

                        <div class="form-group" id="orth_han_cert_div" style="display:none;">
                          <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
                          <div class="col-sm-5">
                            <input onchange="readURL(this,'scanned_orth_han_cert_preview')" type="file" name="scanned_orth_han_cert" id="scanned_orth_han_cert" required style="word-wrap: break-word;width: 100%;">
                            <input type="hidden" id="hidden_orth_han_cert" name="hidden_orth_han_cert">
                            <div id="error_orth_han_cert"></div>
                            <br>
                            <div id="error_orth_han_cert_size"></div>
                            <span class="orth_han_cert_text" style="display:none;"></span>
                            <span class="error"> </span>
                            <img class="mem_reg_img_prev" id="scanned_orth_han_cert_preview" height="100" width="100" src="/assets/images/default1.png" />
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="roleid" class="col-sm-4 control-label">Cerebral palsy</label>
                          <div class="col-sm-3">
                            <input value="Y" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php echo set_radio('cerebral_palsy', 'Y'); ?> class="cerebral_palsy_y">
                            Yes
                            <input value="N" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php echo set_radio('cerebral_palsy', 'N'); ?> class="cerebral_palsy_n" checked="checked">
                            No <span class="error"></span>
                          </div>
                        </div>

                        <div class="form-group" id="cer_palsy_cert_div" style="display:none;">
                          <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
                          <div class="col-sm-5">
                            <input onchange="readURL(this,'scanned_cer_palsy_cert_preview')" type="file" value="<?php //echo set_value('scanned_cer_palsy_cert');
                                                                                                                ?>" name="scanned_cer_palsy_cert" id="scanned_cer_palsy_cert" required style="word-wrap: break-word;width: 100%;">
                            <input type="hidden" id="hidden_cer_palsy_cert" name="hidden_cer_palsy_cert">
                            <div id="error_cer_palsy_cert"></div>
                            <br>
                            <div id="error_cer_palsy_cert_size"></div>
                            <span class="cer_palsy_cert_text" style="display:none;"></span> <span class="error"> </span>
                            <img class="mem_reg_img" id="scanned_cer_palsy_cert_preview" height="100" width="100" src="/assets/images/default1.png" />
                          </div>
                        </div>


                        <div class="form-group">
                          <div class="col-sm-11">
                            <span><label class="box-title">Declaration Form :</label>It is mandatory to upload your Declaration Form.</span>
                            <div><a style="color:#FF0000;" href="https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf" target="_blank"><strong style="color:#F00; text-decoration:underline">Click here to download the Scribe Guidelines and Declaration form. .</strong></a></div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="roleid" class="col-sm-4 control-label">Upload your Declaration Form <span style="color:#F00">**</span></label>
                          <div class="col-sm-5">
                            <input type="file" value="<?php echo set_value('declarationform'); ?>" name="declarationform" id="declarationform" required>
                            <input type="hidden" id="hiddendeclarationform" name="hiddendeclarationform">
                            <span class="note">Please Upload only .jpg, .jpeg File from 8KB to 300KB</span></br>
                            <div class="note-error" id="error_declaration"></div>
                            <br>
                            <div class="note-error" id="error_declaration_size"></div>
                            <span class="declaration_proof_text" style="display:none;"></span> <span class="error">
                              <?php echo form_error('declarationform'); ?>
                            </span>
                          </div>
                          <img class="mem_reg_img" id="image_upload_declarationform_preview" height="100" width="100" src="/assets/images/default1.png" />
                          <div class="col-sm-12">

                          </div>
                        </div>

                      </div>
                      <!-- Benchmark Disability Code End -->


                      <div class="box-header with-border header_blue">
                        <h3 class="box-title ">Scribe Details</h3>
                      </div> <br />
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Name of Scribe&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" value="<?php echo set_value('scribe_name'); ?>" class="form-control" id="scribe_name" name="scribe_name" placeholder="Scribe name" value="" required>
                          <span class="error"> <?php echo form_error('scribe_name'); ?> </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Date of Birth&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-3">
                          <?php
                          if (isset($row['scribe_dob'])) {
                            $originalDate = $row['scribe_dob'];
                            $newDate      = date("d/m/Y", strtotime($originalDate));
                          }
                          ?>
                          <input type="date" class="form-control" id="dob1" name="dob1" placeholder="Date of Birth" value="<?php
                                                                                                                            if (isset($row['scribe_dob'])) {
                                                                                                                              echo $newDate;
                                                                                                                            } ?>" required onchange="">
                        </div>
                        <div class="col-sm-3">(DD/MM/YYYY)</div>
                        <span class="error"></span>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Scribe Mobile&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="number" value="<?php echo set_value('mobile_scribe'); ?>" class="form-control" id="mobile_scribe" name="mobile_scribe" placeholder="Scribe Mobile" onKeyPress="if(this.value.length==10) return false;" required>
                          <span class="error">
                            <?php //echo form_error('mobile_scribe');
                            ?>
                          </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Email of Scribe&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="scribe_email" name="scribe_email" placeholder="Scribe Email" value="<?php echo set_value('scribe_email'); ?>" value="" required>
                          <span class="error"><?php echo form_error('scribe_email'); ?></span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Qualification <span style="color:#F00">*</span></label>
                        <div class="col-sm-6">
                          <input type="radio" class="minimal" id="U" attr="optedu" name="optedu" value="U" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'U'); ?>required>
                          Under Graduate
                          <input type="radio" class="minimal" id="G" attr="optedu" name="optedu" value="G" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'G'); ?>>
                          Graduate
                          <input type="radio" class="minimal" id="P" attr="optedu" name="optedu" value="P" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'P'); ?>>
                          Post Graduate
                          <span class="error">
                            <?php echo form_error('optedu'); ?>
                          </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Please specify <span style="color:#F00">*</span></label>
                        <div class="col-sm-5"
                          <?php if (set_value('eduqual1') || set_value('eduqual2') || set_value('eduqual3')) {
                            echo 'style="display:none"';
                          } else {
                            echo 'style="display:block"';
                          } ?> id="edu">
                          <select id="eduqual" name="eduqual" class="form-control" <?php if (!set_value('eduqual1') && !set_value('eduqual2') && !set_value('eduqual3')) {
                                                                                      echo 'required';
                                                                                    } ?>>
                            <option value="" selected="selected">--Select--</option>
                          </select>
                          <span class="error">
                            <?php echo form_error('eduqual'); ?>
                          </span>
                        </div>

                        <div class="col-sm-5"
                          <?php if (set_value('optedu') == 'U') {
                            echo 'style="display:block;"';
                          } else if (!set_value('optedu')) {
                            echo 'style="display:none;"';
                          } else {
                            echo 'style="display:none;"';
                          } ?> id="UG">
                          <select class="form-control" id="eduqual1" name="eduqual1" <?php if (set_value('optedu') == 'U') {
                                                                                        echo 'required';
                                                                                      } ?>>
                            <option value="">--Select--</option>
                            <?php if (count($undergraduate)) {
                              foreach ($undergraduate as $row1) {   ?>
                                <option value="<?php echo $row1['qid']; ?>" <?php echo set_select('eduqual1', $row1['qid']); ?>><?php echo $row1['name']; ?></option>
                            <?php }
                            } ?>
                          </select>
                          <span class="error">
                            <?php echo form_error('eduqual1'); ?>
                          </span>
                        </div>

                        <div class="col-sm-5"
                          <?php if (set_value('optedu') == 'G') {
                            echo 'style="display:block"';
                          } else {
                            echo 'style="display:none"';
                          } ?> id="GR">
                          <select class="form-control" id="eduqual2" name="eduqual2" <?php if (set_value('optedu') == 'G') {
                                                                                        echo 'required';
                                                                                      } ?>>
                            <option value="">--Select--</option>
                            <?php if (count($graduate)) {
                              foreach ($graduate as $row2) {  ?>
                                <option value="<?php echo $row2['qid']; ?>" <?php echo  set_select('eduqual2', $row2['qid']); ?>><?php echo $row2['name']; ?></option>
                            <?php }
                            } ?>
                          </select>
                          <span class="error">
                            <?php echo form_error('eduqual2'); ?>
                          </span>
                        </div>

                        <div class="col-sm-5" <?php if (set_value('optedu') == 'P') {
                                                echo 'style="display:block"';
                                              } else {
                                                echo 'style="display:none"';
                                              } ?>id="PG">
                          <select class="form-control" id="eduqual3" name="eduqual3" <?php if (set_value('optedu') == 'P') {
                                                                                        echo 'required';
                                                                                      } ?>>
                            <option value="">--Select--</option>
                            <?php if (count($postgraduate)) {
                              foreach ($postgraduate as $row3) {  ?>
                                <option value="<?php echo $row3['qid']; ?>" <?php echo  set_select('eduqual3', $row3['qid']); ?>><?php echo $row3['name']; ?></option>
                            <?php }
                            } ?>
                          </select>
                          <span class="error">
                            <?php echo form_error('eduqual3'); ?>
                          </span>
                        </div>
                      </div>


                      <input type="hidden" id="education_type" value="">

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Employment details of Scribe&nbsp;<br />(Name of the organization).<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" value="<?php echo set_value('emp_details_scribe'); ?>" class="form-control" id="emp_details_scribe" name="emp_details_scribe" placeholder="Employment Details" value="" required>
                          <span class="error">
                            <?php echo form_error('emp_details_scribe'); ?>
                          </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-11">
                          <div class=""><label class="box-title"> Id Proof : </label> It is mandatory to upload ID proof of the scribe.
                            <!-- <ol>
                          <li>Aadhaar Card</li>
                          <li>Passport</li>
                          <li>PAN Card</li>
                          <li>Voter ID Card</li>
                          <li>Driving Licence</li>
                        </ol> -->
                          </div>
                        </div>
                      </div>
                      <!-- POOJA MANE : 27/07/2022 -->

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">ID proof Number of Scribe&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                        <div class="col-sm-5">
                          <input type="text" value="<?php echo set_value('photoid_no'); ?>" class="form-control" id="photoid_no" name="photoid_no" placeholder="Photo ID Number" value="<?php if (isset($member_details[0]['photoid_no'])) {
                                                                                                                                                                                          echo $member_details[0]['photoid_no'];
                                                                                                                                                                                        } ?>" required>
                          <span class="error">
                            <?php //echo form_error('photoid_no');
                            ?>
                          </span>
                        </div>

                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Upload ID proof of Scribe<span style="color:#F00">**</span></label>
                        <div class="col-sm-5">
                          <input type="file" name="idproofphoto" id="idproofphoto" required>
                          <input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">
                          <span class="note">Please Upload only .jpg, .jpeg File from 8KB to 300KB</span>
                          <div class="note-error" id="error_dob"></div>
                          <br>
                          <div class="note-error" id="error_dob_size"></div>
                          <span class="dob_proof_text" style="display:none;"></span>
                          <span class="error">
                            <?php echo form_error('idproofphoto'); ?>
                          </span>
                        </div>
                        <img class="mem_reg_img" id="image_upload_idproof_preview" height="100" width="100" src="/assets/images/default1.png" />
                      </div>

                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label" style="line-height:20px;">Security Code <span style="color:#F00; ">*</span></label>
                        <div class="col-sm-3">
                          <input type="text" name="captcha_code" id="captcha_code" required class="form-control" placeholder="Security Code" maxlength="5" value="">
                        </div>

                        <div class="col-sm-5">
                          <div id="captcha_img"><?php echo $captcha_img; ?></div>
                          <a href="javascript:void(0);" onclick="refresh_captcha_img();" class="text-danger">Change Image</a>
                        </div>
                      </div>

                      <div class="col-sm-12 text-center">
                        <?php //<button type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit">submit </button> &nbsp;&nbsp; 
                        ?>
                        <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">&nbsp;&nbsp;

                        <input type="button" class="btn btn-info" value="Cancel" onclick="window.location='<?php echo base_url('Scribe_form/index'); ?>'">
                      </div>

                    </div>
                  </div>
                </div>
            </section>
          </form>
        </div>

        <?php $this->load->view('scribe_form/inc_footerbar'); ?>
      </section>
    </div>
  </div>

  <?php $this->load->view('scribe_form/inc_footer'); ?>

  <script>
    /*const benchmark_disability = document.getElementById('benchmark_disability');

	benchmark_disability.setAttribute('disabled', '');*/

    // Add an event listener to your calendar input field to trigger the function
    document.getElementById('dob1').addEventListener('change', calculateAgeAndDisplayMessage);

    function calculateAgeAndDisplayMessage() {
      // Get the value from the DOB input field
      var dob = document.getElementById('dob1').value;
      // Convert the input value into a date object
      var birthDate = new Date(dob);
      var today = new Date();
      var age = today.getFullYear() - birthDate.getFullYear();
      var m = today.getMonth() - birthDate.getMonth();

      // Adjust the age if the current date is before the birth date
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }

      // Display the appropriate message based on the age
      if (age < 12) {
        alert('Scribe age should be more than 16 Years');
      } else {
        // alert('You are ' + age + ' years old.');
      }
    }

    function showScribe() {
      var x = document.getElementById("scribe_div");
      x.style.transition = 'all 5s linear';
      x.style.display = "block";
    }


    function changedu(dval) {
      //alert("JHJLDJHG");

      $('#education_type').val(dval)
      var UGid = document.getElementById('UG');
      var GRid = document.getElementById('GR');
      var PGid = document.getElementById('PG');
      var EDUid = document.getElementById('edu');

      if (dval == 'U') {
        $('#eduqual1').attr('required', 'required');
        $('#eduqual2').removeAttr('required');
        $('#eduqual3').removeAttr('required');
        $('#eduqual').removeAttr('required');
        //  $('#noOptEdu').hide();

        if (UGid != null) {
          //  alert('UG');
          document.getElementById('UG').style.display = "block";
        }
        if (GRid != null) {
          document.getElementById('GR').style.display = "none";
        }
        if (PGid != null) {
          document.getElementById('PG').style.display = "none";
        }
        if (EDUid != null) {
          document.getElementById('edu').style.display = "none";
        }
      } else if (dval == 'G') {
        $('#eduqual1').removeAttr('required');;
        $('#eduqual2').attr('required', 'required');
        $('#eduqual3').removeAttr('required');
        $('#eduqual').removeAttr('required');
        //$('#noOptEdu').hide();

        if (UGid != null) {
          document.getElementById('UG').style.display = "none";
        }
        if (GRid != null) {
          document.getElementById('GR').style.display = "block";
        }
        if (PGid != null) {
          document.getElementById('PG').style.display = "none";
        }
        if (EDUid != null) {
          document.getElementById('edu').style.display = "none";
        }

      } else if (dval == 'P') {
        $('#eduqual1').removeAttr('required');;
        $('#eduqual2').removeAttr('required');
        $('#eduqual3').attr('required', 'required');
        $('#eduqual').removeAttr('required');
        //$('#noOptEdu').hide();

        if (UGid != null) {
          document.getElementById('UG').style.display = "none";
        }
        if (GRid != null) {
          document.getElementById('GR').style.display = "none";
        }
        if (PGid != null) {
          document.getElementById('PG').style.display = "block";
        }
        if (EDUid != null) {
          document.getElementById('edu').style.display = "none";
        }
      } else {
        //$('#noOptEdu').show();  
      }
    }
    $(document).ready(function() {
      $("#usersAddForm").on('submit', function(event) {});
    });

    $(document).ready(function() {
      $.validator.addMethod("customFileCheck", function(value, element) {

        if ($("#scanned_vis_imp_cert").get(0).files.length === 0 && $("#scanned_orth_han_cert").get(0).files.length === 0 && $("#scanned_cer_palsy_cert").get(0).files.length === 0) {
          return false;
        } else {
          return true;
        }

      }, "Atleast one disability Certificate must be uploaded");

      $("#usersAddForm").validate({
        rules: {
          <?php if (count($member_info) > 0 && isset($member_info[0]['regnumber'])) { ?>
            member_no: {
              required: true
            },
            /* , remote: { url: "<?php echo site_url('ApplyElearning/check_member_no_ajax') ?>", type: "post" } */
          <?php } ?>
          name_of_scribe: {
            required: true,
            maxlength: 15
          },
          emp_details_scribe: {
            required: true,
            maxlength: 20
          },
          qualification: {
            required: true
          },
          //lastname: { maxlength:20 }, 
          captcha_code: {
            required: true,
            remote: {
              url: "<?php echo site_url('Scribe_form/check_captcha_code_ajax1') ?>",
              type: "post",
              data: {
                "session_name": "LOGIN_SCRIBE_FORM"
              }
            }
          },
          email: {
            required: true,
            valid_email: true,
            maxlength: 45,
            /* <?php if ($this->session->has_userdata('session_array')) {
                } else { ?>
              remote: { url: "<?php echo site_url('ApplyElearning/check_email_exist_ajax') ?>", type: "post", data: { "member_no": function() { return "<?php echo $member_no; ?>" } } } 
            <?php } ?> */
          },
          mobile_scribe: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10,
            remote: {
              url: "<?php echo site_url('scribe_form/check_mobile_exist_ajax') ?>",
              type: "post",
              async: false,
              data: {
                member_no: $("#member_no").val(),
                exam_date: $("#exam_date").val()
              }

            }

          },
          photoid_no: {
            required: true,
            maxlength: 15,
            remote: {
              url: "<?php echo site_url('scribe_form/check_photoid_exist_ajax') ?>",
              type: "post",
              async: false,
              data: {
                member_no: $("#member_no").val(),
                exam_date: $("#exam_date").val()
              }

            }

          },
          /*at least one certificate*/
          benchmark_disability: {
            customFileCheck: true,

          },
          exam_name: {
            required: true
          },

        },
        messages: {
          member_no: {
            required: "Please enter Membership/Registration No.",
            remote: "Please enter valid Membership/Registration No."
          },
          namesub: {
            required: "Please select Title"
          },
          firstname: {
            required: "Please enter First Name"
          },
          //middlename: { },
          email: {
            required: "Please enter Email",
            maxlength: "Please enter maximum 45 characters in Email",
            valid_email: "Please enter Valid Email",
            remote: "Email already exist"
          },
          mobile_scribe: {
            required: "Please enter Mobile",
            digits: "Please enter Number",
            minlength: "Please enter 10 numbers in Mobile",
            maxlength: "Please enter 10 numbers in Mobile",
            remote: "Mobile already exist"
          },
          photoid_no: {
            required: "Please enter Photo Id",
            remote: "Scribe not available for given slot"
          },
          exam_name: {
            required: "Please select Exam"
          },
          captcha_code: {
            required: "Please enter code",
            remote: "Please enter valid captcha"
          }
        },

        submitHandler: function(form) {
          form.submit();

        },
        errorPlacement: function(error, element) {
          if (element.attr("name") == "benchmark_disability") {
            error.insertAfter("#benchmark_disability_err");
          }

        },
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      $('.loading').delay(0).fadeOut('slow');
    });
  </script>
</body>

</html>
<script>
  function refresh_captcha_img() {
    $(".loading").show();
    $.ajax({
      type: 'POST',
      url: '<?php echo site_url("Scribe_form/generate_captcha_ajax1/"); ?>',
      data: {
        "session_name": "LOGIN_SCRIBE_FORM"
      },
      async: false,
      success: function(res) {
        if (res != '') {
          $('#captcha_img').html(res);
          $("#captcha_code").val("");
          $("#captcha_code-error").html("");
        }
        $(".loading").hide();
      }
    });
  }

  $(document).ready(function() {
    $("#regnumber").focus();
    var flag = $("#flag").val();
    if (flag == 1) {
      $("#regnumber").val('');
      $("#regnumber").prop("readonly", false);
      $("#modify").hide();
      $("#btnGet").show();
    }
  });

  history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
  window.addEventListener('popstate', function(event) {
    window.location.assign(site_url + "blended/");
  });

  $('#Close').click(function(event) {
    event.preventDefault();
    $("#residential_phone").css("position", "relative");
    $("#phone").css("position", "relative");
  });


  function createCookie(name, value, days) {
    var expires;
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toGMTString();
    } else {
      expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
  }


  $(function() {
    function readCookie(name) {
      var nameEQ = encodeURIComponent(name) + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
      }
      return null;
    }

    if (readCookie('member_register_form')) {
      $('#error_id').html('');
      $('#error_id').removeClass("alert alert-danger alert-dismissible");
      createCookie('member_register_form', "", -1);
    }


    /*$(document).keydown(function(event) {
         if (event.ctrlKey==true && (event.which == '67' || event.which == '86')) {
             if(event.which == '67')
       {
         alert('Key combination CTRL + C has been disabled.');
       }
       if(event.which == '86')
       {
         alert('Key combination CTRL + V has been disabled.');
       }
       event.preventDefault();
          }
     });*/

    $("body").on("contextmenu", function(e) {
      return false;
    });
    $(this).scrollTop(0);

  });
  ///////////////////// ID Proof validation //////////////////////

  $("#idproofphoto").change(function() {

    var filesize2 = this.files[0].size / 1024 > 300;
    var filesize3 = this.files[0].size / 1024 < 8;

    var flag = 1;


    var dob_proof_image = document.getElementById('idproofphoto');

    var dob_proof_im = dob_proof_image.value;

    var ext3 = dob_proof_im.substring(dob_proof_im.lastIndexOf('.') + 1).toLowerCase();



    if (dob_proof_image.value != "" && ext3 != 'jpg' && ext3 != 'JPG' && ext3 != 'jpeg' && ext3 != 'JPEG')

    {

      $('#error_dob_size').show();

      $('#error_dob_size').fadeIn(300);

      document.getElementById('error_dob_size').innerHTML = "Please upload .jpg, .jpeg file only.";

      setTimeout(function() {

        $('#error_dob_size').css('color', '#B94A48');

        document.getElementById("idproofphoto").value = "";

        $('#hiddenidproofphoto').val('');


      }, 30);

      flag = 0;

      $(".dob_proof_text").hide();

    } else if (filesize3) {
      $('#error_dob_size').show();
      $('#error_dob_size').fadeIn(300);
      document.getElementById('error_dob_size').innerHTML = "Please upload file having size more than 8KB";
      setTimeout(function() {
        $('#error_dob_size').css('color', '#B94A48');
        document.getElementById("idproofphoto").value = "";
        $('#hiddenidproofphoto').val('');
        //$('#error_bussiness_image').fadeOut('slow');
      }, 30);
      flag = 0;
      $(".dob_proof_text").hide();
    } else if (filesize2)

    {

      $('#error_dob_size').show();

      $('#error_dob_size').fadeIn(300);

      document.getElementById('error_dob_size').innerHTML = "Please upload file less than 300KB";

      setTimeout(function() {

        $('#error_dob_size').css('color', '#B94A48');

        document.getElementById("idproofphoto").value = "";

        $('#hiddenidproofphoto').val('');

        //$('#error_bussiness_image').fadeOut('slow');

      }, 30);

      flag = 0;

      $(".dob_proof_text").hide();

    }

    if (flag == '1')

    {

      $('#error_dob_size').html('');

      $('#error_dob').html('');

      var files = !!this.files ? this.files : [];

      if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support



      if (/^image/.test(files[0].type)) { // only image file

        var reader = new FileReader(); // instance of the FileReader

        reader.readAsDataURL(files[0]); // read the local file

        reader.onloadend = function() { // set image data as background of div

          $('#hiddenidproofphoto').val(this.result);

          $('#declaration_id').hide();

        }

      }



      readURL(this, 'image_upload_idproof_preview');

      return true;

    } else

    {

      return false;

    }

  });
  ///////// Declaration form validation/////////////

  $("#declarationform").change(function() {
    var filesize2 = this.files[0].size / 1024 > 300;
    var filesize3 = this.files[0].size / 1024 < 8;
    var flag = 1;
    //$("#p_dob_proof").hide();

    var declartion_proof_image = document.getElementById('declarationform');
    var declaration_proof_im = declartion_proof_image.value;
    var ext3 = declaration_proof_im.substring(declaration_proof_im.lastIndexOf('.') + 1).toLowerCase();

    if (declartion_proof_image.value != "" && ext3 != 'jpg' && ext3 != 'JPG' && ext3 != 'jpeg' && ext3 != 'JPEG') {
      $('#error_declaration_size').show();
      $('#error_declaration_size').fadeIn(300);
      document.getElementById('error_declaration_size').innerHTML = "Please upload .jpg, .jpeg file only.";
      setTimeout(function() {
        $('#error_declaration_size').css('color', '#B94A48');
        document.getElementById("declarationform").value = "";
        $('#hiddendeclarationform').val('');
        //$('#error_bussiness_image').fadeOut('slow');
      }, 30);
      flag = 0;
      $(".declaration_proof_text").hide();
    } else if (filesize3) {
      $('#error_declaration_size').show();
      $('#error_declaration_size').fadeIn(300);
      document.getElementById('error_declaration_size').innerHTML = "Please upload file having size more than 8KB";
      setTimeout(function() {
        $('#error_declaration_size').css('color', '#B94A48');
        document.getElementById("declarationform").value = "";
        $('#hiddendeclarationform').val('');
        //$('#error_bussiness_image').fadeOut('slow');
      }, 30);
      flag = 0;
      $(".declaration_proof_text").hide();
    } else if (filesize2) {
      $('#error_declaration_size').show();
      $('#error_declaration_size').fadeIn(300);
      document.getElementById('error_declaration_size').innerHTML = "Please upload file less than 300KB";
      setTimeout(function() {
        $('#error_declaration_size').css('color', '#B94A48');
        document.getElementById("declarationform").value = "";
        $('#hiddendeclarationform').val('');
        //$('#error_bussiness_image').fadeOut('slow');
      }, 30);
      flag = 0;
      $(".declaration_proof_text").hide();
    }

    if (flag == '1') {
      $('#error_declaration_size').html('');
      $('#error_declaration').html('');
      var files = !!this.files ? this.files : [];
      if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

      if (/^image/.test(files[0].type)) { // only image file
        var reader = new FileReader(); // instance of the FileReader
        reader.readAsDataURL(files[0]); // read the local file
        reader.onloadend = function() { // set image data as background of div
          $('#hiddendeclarationform').val(this.result);
        }
      }

      readURL(this, 'image_upload_declarationform_preview');
      return true;
    } else {
      return false;
    }
  });

  function readURL(input, div) {

    if (input.files && input.files[0]) {

      var reader = new FileReader();



      reader.onload = function(e) {

        $('#' + div).attr('src', e.target.result);

      }



      reader.readAsDataURL(input.files[0]);

    }

  }



  function refresh_captcha_img() {
    $(".loading").show();
    $.ajax({
      type: 'POST',
      url: '<?php echo site_url("Scribe_form/generate_captcha_ajax1/"); ?>',
      data: {
        "session_name": "LOGIN_SCRIBE_FORM"
      },
      async: false,
      success: function(res) {
        if (res != '') {
          $('#captcha_img').html(res);
          $("#captcha_code").val("");
          $("#captcha_code-error").html("");
        }
        $(".loading").hide();
      }
    });
  }
</script>