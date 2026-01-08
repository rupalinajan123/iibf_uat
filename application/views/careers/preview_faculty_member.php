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

  label {
    font-weight: bold !important;
  }
</style>
<?php
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<div class="container">
  <section class="content-header">
    <h1> Please go through the given detail, correction may be made if necessary. <a href="javascript:window.history.go(-1);">Modify</a> </h1>
    <br>
  </section>

  <?php
  //echo "<pre>";print_r($this->session->userdata('enduserinfo'));
  ?>
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" enctype="multipart/form-data"
    action="<?php echo base_url() ?>Careers/addmember/">
    <input type="hidden" id="position_id" name="position_id" value="<?php echo $this->session->userdata['enduserinfo']['position_id']; ?>">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">BASIC DETAILS</h3>
              <div style="float:right;"> </div>
            </div>
            <!-- form start -->
            <div class="box-body">
              <?php //echo validation_errors(); 
              ?>
              <?php if ($this->session->flashdata('error') != '') { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php }
              if ($this->session->flashdata('success') != '') { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('success'); ?>
                </div>
              <?php }
              if (validation_errors() != '') { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo validation_errors(); ?>
                </div>
              <?php }
              ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Application for the post of </label>
                <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['position_selection']; ?><!-- Faculty Member on contract basis (HRM) --></div>

              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Title</label>
                <div class="col-sm-1"> <?php echo $this->session->userdata['enduserinfo']['sel_namesub']; ?> </div>
                <div class="col-sm-0"> <?php echo $this->session->userdata['enduserinfo']['firstname']; ?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Middle Name</label>
                <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['middlename']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Last Name</label>
                <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['lastname']; ?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Spouse's Name</label>
                <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['spouse_name']; ?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Father's Name</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['father_husband_name']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Mother's Name</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['mother_name']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Date of Birth </label>
                <div class="col-sm-2 example"> <?php echo $this->session->userdata['enduserinfo']['dateofbirth']; ?> </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Gender </label>
                <div class="col-sm-6">
                  <?php if ($this->session->userdata['enduserinfo']['gender'] == 'female') {
                    echo 'Female';
                  } ?>
                  <?php if ($this->session->userdata['enduserinfo']['gender'] == 'male') {
                    echo 'Male';
                  } ?>
                </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Religion </label>
                <div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['religion']; ?> </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Email Id</label>
                <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['email']; ?> </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Marital Status</label>
                <div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['marital_status']; ?> </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Are you a person with Physically Disbaility? </label>
                <div class="col-sm-6">
                  <?php if ($this->session->userdata['enduserinfo']['physical_disbaility'] == 'no') {
                    echo 'No';
                  } ?>
                  <?php if ($this->session->userdata['enduserinfo']['physical_disbaility'] == 'yes') {
                    echo 'Yes';
                  } ?>
                </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Type of Disability</label>
                <div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['physical_disbaility_desc']; ?> </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Mobile Number</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['mobile']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Alternate Mobile Number</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['alternate_mobile']; ?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">PAN Number</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['pan_no']; ?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Aadhar Card Number</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['aadhar_card_no']; ?></div>
              </div>
            </div>
          </div>
          <!-- Basic Details box closed-->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">COMMUNICATION ADDRESS</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line I </label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline1']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line II</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline2']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line III</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline3']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line IV</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline4']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">District </label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['district']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">City </label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['city']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">State </label>
                <div class="col-sm-3">
                  <?php if (count($states) > 0) {
                    foreach ($states as $row1) {   ?>
                      <?php if ($this->session->userdata['enduserinfo']['state'] == $row1['state_code']) {
                        echo  $row1['state_name'];
                      } ?>
                  <?php }
                  } ?>
                </div>
                <label for="roleid" class="col-sm-2 control-label">Pincode </label>
                <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['pincode']; ?> </div>
              </div>
              <!-- <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Contact Number</label>
            <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['contact_number']; ?> </div>
          </div>-->
              <div class="box-header with-border">
                <h3 class="box-title">PERMANENT ADDRESS</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line I </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline1_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line II</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline2_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line III</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline3_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line IV</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline4_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">District </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['district_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">City </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['city_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">State </label>
                  <div class="col-sm-3">
                    <?php if (count($states) > 0) {
                      foreach ($states as $row1) {   ?>
                        <?php if ($this->session->userdata['enduserinfo']['state_pr'] == $row1['state_code']) {
                          echo  $row1['state_name'];
                        } ?>
                    <?php }
                    } ?>
                  </div>
                  <label for="roleid" class="col-sm-2 control-label">Pincode </label>
                  <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['pincode_pr']; ?> </div>
                </div>
                <!--<div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Contact Number</label>
              <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['contact_number_pr']; ?> </div>
            </div>-->
                <!------------------------------| Education Qualification |--------------------------->
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">EDUCATION QUALIFICATION</h3>
                  </div>
                </div>
                <div class="box-title box-header"><strong>ESSENTIAL</strong> </div>
                <br /><br />
                <b>Education Qualification I - Graduation *</b></br>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Qualification</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['ess_course_name']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Subject </label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['ess_pg_stream_subject']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">College/ Institution </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_college_name']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">University </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_university']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Period </label>
                  <div class="col-sm-5">From: <?php echo $this->session->userdata['enduserinfo']['ess_from_date']; ?> - To: <?php echo $this->session->userdata['enduserinfo']['ess_to_date']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Aggregate Marks Obtained </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_aggregate_marks_obtained']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Aggregate Maximum Marks </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_aggregate_max_marks']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Final Percentage </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_percentage']; ?></div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Class/Grade </label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['ess_class']; ?> </div>
                </div>


                <?php
                $endinfo = $this->session->userdata['enduserinfo'];

                // count how many PG entries exist
                $total_pg = isset($endinfo['post_qua_name']) && is_array($endinfo['post_qua_name'])
                  ? count($endinfo['post_qua_name'])
                  : 1;
                ?>

                <b>Education Qualification II - Post Graduation</b><br><br>

                <?php for ($i = 0; $i < $total_pg; $i++) { ?>

                  <b>Post Graduation Entry <?php echo $i + 1; ?></b><br><br>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Qualification</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['post_qua_name']) ? $endinfo['post_qua_name'][$i] : $endinfo['post_qua_name']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Subject</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['post_gra_sub']) ? $endinfo['post_gra_sub'][$i] : $endinfo['post_gra_sub']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">College/ Institution</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['post_gra_college_name']) ? $endinfo['post_gra_college_name'][$i] : $endinfo['post_gra_college_name']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">University</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['post_gra_university']) ? $endinfo['post_gra_university'][$i] : $endinfo['post_gra_university']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Period</label>
                    <div class="col-sm-5">
                      From:
                      <?php echo is_array($endinfo['post_gra_from_date']) ? $endinfo['post_gra_from_date'][$i] : $endinfo['post_gra_from_date']; ?>
                      -
                      To:
                      <?php echo is_array($endinfo['post_gra_to_date']) ? $endinfo['post_gra_to_date'][$i] : $endinfo['post_gra_to_date']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Aggregate Marks Obtained</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['post_aggregate_marks_obtained']) ? $endinfo['post_aggregate_marks_obtained'][$i] : $endinfo['post_aggregate_marks_obtained']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Aggregate Maximum Marks</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['post_gra_aggregate_max_marks']) ? $endinfo['post_gra_aggregate_max_marks'][$i] : $endinfo['post_gra_aggregate_max_marks']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Final Percentage</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['post_gra_percentage']) ? $endinfo['post_gra_percentage'][$i] : $endinfo['post_gra_percentage']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Class/Grade</label>
                    <div class="col-sm-6">
                      <?php echo is_array($endinfo['post_gra_class']) ? $endinfo['post_gra_class'][$i] : $endinfo['post_gra_class']; ?>
                    </div>
                  </div>

                  <hr>

                <?php } ?>





                <!-- <b>Education Qualification III: Additional Qualifications/Certifications </b></br>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Qualification </label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['cer_qua_name']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name of the Subject </label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['cer_gra_sub']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">College/ Institution </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_college_name']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">University </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_university']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Period </label>
                  <div class="col-sm-5">From: <?php echo $this->session->userdata['enduserinfo']['cer_from_date']; ?> - To: <?php echo $this->session->userdata['enduserinfo']['cer_to_date']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Aggregate Marks Obtained </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_marks_obtained']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Aggregate Maximum Marks </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_aggregate_max_marks']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Final Percentage </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_percentage']; ?></div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Class/Grade </label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['cer_class']; ?> </div>
                </div> -->




                <div class="box-title box-header"><strong>CAIIB</strong> </div>
                <br />
                <!-- <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">CAIIB</label>
              <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['ess_subject']; ?> </div>
            </div> -->
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Year of Passing</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['year_of_passing']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Membership Number</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['membership_number']; ?> </div>
                </div>
                <?php
                $endinfo = $this->session->userdata['enduserinfo'];

                // count total desirable entries
                $total = isset($endinfo['course_code']) && is_array($endinfo['course_code'])
                  ? count($endinfo['course_code'])
                  : 1;
                ?>

                <div class="box-title box-header"><strong>DESIRABLE</strong></div>
                <br />

                <?php
                for ($i = 0; $i < $total; $i++) {
                ?>
                  <b>Desirable Qualification <?php echo $i + 1; ?></b><br><br>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Name Of Course</label>
                    <div class="col-sm-6">
                      <?php
                      $selected_course = isset($endinfo['course_code'][$i]) ? $endinfo['course_code'][$i] : '';

                      if (!empty($course_list) && is_array($course_list)) {

                        foreach ($course_list as $row1) {

                          if (isset($row1['course_code']) && $selected_course == $row1['course_code']) {
                            echo $row1['course_name'];
                          }
                        }
                      } else {
                        echo "—"; // fallback if course_list is not an array
                      }

                      ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Specialisation</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['name_subject_of_course'])
                        ? $endinfo['name_subject_of_course'][$i]
                        : $endinfo['name_subject_of_course']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">College/Institution</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['college_name'])
                        ? $endinfo['college_name'][$i]
                        : $endinfo['college_name']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">University</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['university'])
                        ? $endinfo['university'][$i]
                        : $endinfo['university']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Period</label>
                    <div class="col-sm-5">
                      From:
                      <?php echo is_array($endinfo['from_date'])
                        ? $endinfo['from_date'][$i]
                        : $endinfo['from_date']; ?>
                      -
                      To:
                      <?php echo is_array($endinfo['to_date'])
                        ? $endinfo['to_date'][$i]
                        : $endinfo['to_date']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Aggregate Marks Obtained</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['aggregate_marks_obtained'])
                        ? $endinfo['aggregate_marks_obtained'][$i]
                        : $endinfo['aggregate_marks_obtained']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Aggregate Maximum Marks</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['aggregate_max_marks'])
                        ? $endinfo['aggregate_max_marks'][$i]
                        : $endinfo['aggregate_max_marks']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Percentage</label>
                    <div class="col-sm-5">
                      <?php echo is_array($endinfo['percentage'])
                        ? $endinfo['percentage'][$i]
                        : $endinfo['percentage']; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label">Class/Grade</label>
                    <div class="col-sm-6">
                      <?php echo is_array($endinfo['class'])
                        ? $endinfo['class'][$i]
                        : $endinfo['class']; ?>
                    </div>
                  </div>

                  <hr>
                <?php
                }
                ?>


                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">EMPLOYMENT HISTORY</h3>
                  </div>
                </div>
                <?php
                $organization = $this->session->userdata['enduserinfo']['organization'];
                $designation = $this->session->userdata['enduserinfo']['designation'];
                $responsibilities = $this->session->userdata['enduserinfo']['responsibilities'];
                $job_from_date = $this->session->userdata['enduserinfo']['job_from_date'];
                $job_to_date = $this->session->userdata['enduserinfo']['job_to_date'];

                foreach ($organization as $job_key => $job_val) {
                  $organization_val = $job_val;
                  $designation_val = $designation[$job_key];
                  $responsibilities_val = $responsibilities[$job_key];
                  $job_from_date_val = $job_from_date[$job_key];
                  $job_to_date_val = $job_to_date[$job_key];
                ?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Name of the Organisation/Employeer/Bank</label>
                    <div class="col-sm-5"><?php echo $organization_val; ?></div>
                  </div>

                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Period </label>
                    <div class="col-sm-5">From: <?php echo $job_from_date_val; ?> - To: <?php echo $job_to_date_val; ?></div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Last Designation/Last Post Held </label>
                    <div class="col-sm-5"><?php echo $designation_val; ?></div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Responsibilities/Nature of Duties Performed </label>
                    <div class="col-sm-5"><?php echo $responsibilities_val; ?></div>
                  </div>

                <?php
                }
                ?>

                <div class="box-title box-header"><strong>Whether In Service or not?</strong> </div>
                <br />
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Whether In Service?</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['whether_in_service']; ?> </div>
                </div>
                <?php if ($this->session->userdata['enduserinfo']['whether_in_service'] == 'yes') { ?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Name of the Present Organization</label>
                    <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['name_of_present_organization']; ?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Period</label>
                    <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['service_from_date']; ?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Communication Address of the Organisation</label>
                    <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['comm_address_of_org']; ?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Designation/Post Held</label>
                    <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['curr_designation']; ?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Any Other Details</label>
                    <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['any_other_details']; ?> </div>
                  </div>

                <?php } else { ?>
                  <div class="box-title box-header"><strong>If Not In Service</strong> </div>
                  <br />
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Date of Superannuation/VRS/Resignation etc</label>
                    <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['vrs_register_date']; ?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Reason for Resignation/Leaving</label>
                    <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['reason_of_resign']; ?> </div>
                  </div>
                <?php } ?>
                <div class="box-title box-header"><strong>Experience as Faculty</strong> </div>
                <br />
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Experience as Faculty</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['exp_in_bank']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Period</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['exp_faculty_from_date']; ?> - <?php echo $this->session->userdata['enduserinfo']['exp_faculty_to_date']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Subjects Handled</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['subject_handled']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Area of Specialisation</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['exp_in_functional_area']; ?> </div>
                </div>

                <!-- <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Other Details Such as Exemplary Performance</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['exeplary_details']; ?> </div>
                </div> -->
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Published Articles/Books</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['publication_of_books']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Membership of Professional Associations</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['professional_ass']; ?> </div>
                </div>


                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Languages, Extracurricular, Activities, Achievements, Hobbies</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Languages Known I</label>
                  <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known']; ?> :
                    <?php
                    $languages_option_arr = $this->session->userdata['enduserinfo']['languages_option'];
                    echo $languages_option_arr;
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Languages Known II</label>
                  <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known1']; ?> :
                    <?php
                    $languages_option_arr = $this->session->userdata['enduserinfo']['languages_option1'];
                    echo $languages_option_arr;
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Languages Known III</label>
                  <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known2']; ?> :
                    <?php
                    $languages_option_arr = $this->session->userdata['enduserinfo']['languages_option2'];
                    echo $languages_option_arr;
                    ?>
                  </div>
                </div>



                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Extracurricular Activities</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['extracurricular']; ?></div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Outstanding Achievements / Awards (if any)</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['achievements']; ?> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Hobbies</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['hobbies']; ?></div>
                </div>

                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Professional REFERENCE I</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refname_one']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Complete Address</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refaddressline_one']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Organisation (If employed)</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['reforganisation_one']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Designation</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refdesignation_one']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email Id</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['refemail_one']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile Number </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refmobile_one']; ?></div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Professional REFERENCE II</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refname_two']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Complete Address</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refaddressline_two']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Organisation (If employed)</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['reforganisation_two']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Designation</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refdesignation_two']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email Id</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['refemail_two']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile Number</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refmobile_two']; ?></div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Other Information</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Any other information that the candidate would like to add</label>
                  <div class="col-sm-6" style="word-wrap: break-word;">
                    <?php
                    $comment = $this->session->userdata['enduserinfo']['comment'];
                    echo wordwrap($comment, 75, "<br>\n");

                    ?>
                  </div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Declaration</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Declaration 1&nbsp;</label>
                  <div class="col-sm-8" align="justify"> <strong><?php echo $this->session->userdata['enduserinfo']['declaration2']; ?></strong> &nbsp;
                    &nbsp;I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Declaration 2&nbsp;</label>
                  <div class="col-sm-8" align="justify"> <strong><?php echo $this->session->userdata['enduserinfo']['declaration3']; ?></strong> &nbsp;
                    &nbsp; I hereby agree that any legal proceedings in respect of any matter or claims or disputes arising out of the application or out of the said advertisement can be instituted by me at Mumbai only and shall have sole and exclusive jurisdiction to try any cause / dispute. I undertake to abide by all the terms and conditions of the advertisement given by the Indian Institute of Banking & Finance.</div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Declaration 3&nbsp;</label>
                  <div class="col-sm-8" align="justify"> <strong><?php echo $this->session->userdata['enduserinfo']['declaration3']; ?></strong> &nbsp;
                    &nbsp; I further certify that no disciplinary / vigilance proceedings are either pending or contemplated against me and that there are no legal cases pending against me. There have been no major / minor penalty.</div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">UPLOAD</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedphoto']; ?>" height="100" width="100"></label>
                  <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedsignaturephoto']; ?>" height="100" width="100"></label>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"> Photo</label>
                  <label for="roleid" class="col-sm-3 control-label"> Signature</label>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">PLACE AND DATE</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Place</label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['place']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Date</label>
                  <div class="col-sm-6">
                    <div class="col-sm-5 date"> <?php echo $this->session->userdata['enduserinfo']['submit_date']; ?> </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box box-info">
              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-3">
                  <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit Application">
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
  </form>
</div>
</div>