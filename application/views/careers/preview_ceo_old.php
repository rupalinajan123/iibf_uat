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
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" enctype="multipart/form-data"
    action="<?php echo base_url() ?>Careers/addmember/">
    <input type="hidden" id="position_id" name="position_id" value="5">
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
                <label for="roleid" class="col-sm-4 control-label">First Name</label>
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
                <label for="roleid" class="col-sm-4 control-label">Marital Status</label>
                <div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['marital_status']; ?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Father Name</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['father_husband_name']; ?></div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Spouse's Name</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['spouse_name']; ?></div>
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
                <label for="roleid" class="col-sm-4 control-label">Email Id</label>
                <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['email']; ?> </div>
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
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Languages Known 1</label>
                <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known']; ?> :
                  <?php
                  $languages_option_arr = $this->session->userdata['enduserinfo']['languages_option'];
                  echo $languages_option_arr;
                  ?>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Languages Known 2</label>
                <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known1']; ?> :
                  <?php
                  $languages_option_arr = $this->session->userdata['enduserinfo']['languages_option1'];
                  echo $languages_option_arr;
                  ?>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Languages Known 3</label>
                <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known2']; ?> :
                  <?php
                  $languages_option_arr = $this->session->userdata['enduserinfo']['languages_option2'];
                  echo $languages_option_arr;
                  ?>
                </div>
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
                <label for="roleid" class="col-sm-4 control-label">Address line 1 </label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline1']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line 2</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline2']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line 3</label>
                <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline3']; ?></div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line 4</label>
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
                <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode </label>
                <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['pincode']; ?> </div>
              </div>
              <!-- <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Contact Number</label>
                <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['contact_number']; ?> </div>
              </div> -->
              <div class="box-header with-border">
                <h3 class="box-title">PERMANENT ADDRESS</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line 1 </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline1_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line 2</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline2_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line 3</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline3_pr']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line 4</label>
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
                  <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode </label>
                  <div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['pincode_pr']; ?> </div>
                </div>
                <!-- <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Contact Number</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['contact_number_pr']; ?> </div>
                </div> -->
                <!------------------------------| Education Qualification |--------------------------->
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">EDUCATIONAL QUALIFICATION</h3>
                  </div>
                </div>
                <div class="box-title box-header"><strong>ESSENTIAL</strong> </div>
                <br />
                <div class="box-title"><strong>Educational Qualification I - Post Graduation</strong> </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Qualification</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['post_qua_name']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Post Graduation Subject</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['post_gra_sub']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">College/Institution </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['post_gra_college_name']; ?> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">University</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['post_gra_university']; ?> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Period </label>
                  <div class="col-sm-5">From: <?php echo $this->session->userdata['enduserinfo']['post_gra_from_date']; ?> - To: <?php echo $this->session->userdata['enduserinfo']['post_gra_to_date']; ?></div>
                </div>

                <!-- <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Aggregate Marks Obtained </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['post_aggregate_marks_obtained']; ?> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Aggregate Maximum Marks </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['post_gra_aggregate_max_marks']; ?> </div>
                </div> -->

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Final Percentage </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['post_gra_percentage']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Class </label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['post_gra_class']; ?> </div>
                </div>

                <!-- <div class="box-title"><strong>Educational Qualification II: Additional Qualifications/Certification</strong> </div>
                <?php

                $i = 0;
                $experience_as_principal =  $experience_as_faculty = $experience_as_principal_val = $experience_as_faculty_val = '';
                $organization = $this->session->userdata['enduserinfo']['organization'];
                $designation = $this->session->userdata['enduserinfo']['designation'];
                $responsibilities = $this->session->userdata['enduserinfo']['responsibilities'];
                $job_from_date = $this->session->userdata['enduserinfo']['job_from_date'];
                $job_to_date = $this->session->userdata['enduserinfo']['job_to_date'];
                $experience_as_principal = $this->session->userdata['enduserinfo']['experience_as_principal'];
                $experience_as_faculty = $this->session->userdata['enduserinfo']['experience_as_faculty'];

                foreach ($organization as $job_key => $job_val) {
                  $organization_val = $job_val;
                  $designation_val = $designation[$job_key];
                  $responsibilities_val = $responsibilities[$job_key];
                  $job_from_date_val = $job_from_date[$job_key];
                  $job_to_date_val = $job_to_date[$job_key];

                  if (isset($experience_as_principal[$job_key])) {
                    $experience_as_principal_val = $experience_as_principal[$job_key];
                  }
                  if (isset($experience_as_faculty[$job_key])) {
                    $experience_as_faculty_val = $experience_as_faculty[$job_key];
                  }

                ?>

                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Qualification</label>
                    <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['cer_qua_name']; ?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Name of the Subject</label>
                    <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_gra_sub']; ?></div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">College/Institution </label>
                    <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_college_name']; ?> </div>
                  </div>

                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">University</label>
                    <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_university']; ?> </div>
                  </div>

                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Period </label>
                    <div class="col-sm-5">From: <?php echo $this->session->userdata['enduserinfo']['cer_from_date']; ?> - To: <?php echo $this->session->userdata['enduserinfo']['cer_to_date']; ?></div>
                  </div>

                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Aggregate Marks Obtained </label>
                    <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_marks_obtained']; ?> </div>
                  </div>

                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Aggregate Maximum Marks </label>
                    <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_aggregate_max_marks']; ?> </div>
                  </div>

                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Final Percentage </label>
                    <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['cer_percentage']; ?></div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Class </label>
                    <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['cer_class']; ?> </div>
                  </div>

                <?php

                  $i++;
                }

                ?> -->

                <div class="box-title"><strong>EDUCATIONAL QUALIFICATION II: ADDITIONAL QUALIFICATIONS / CERTIFICATION</strong> </div>

                <?php
                $i = 0;

                // Fetch arrays (like Employment History)
                $qualification_arr             = isset($this->session->userdata['enduserinfo']['qualification_arr']) ? $this->session->userdata['enduserinfo']['qualification_arr'] : [];
                //echo'<pre>';print_r($qualification_arr);
                // Loop through and display qualification details
                if (!empty($qualification_arr) && is_array($qualification_arr)) {
                  foreach ($qualification_arr as $key => $val) {
                    $qua_name_val            = isset($qualification_arr[$key]['cer_qua_name']) ? $qualification_arr[$key]['cer_qua_name'] : '';
                    $gra_sub_val             = isset($qualification_arr[$key]['cer_gra_sub']) ? $qualification_arr[$key]['cer_gra_sub'] : '';
                    $college_name_val        = isset($qualification_arr[$key]['cer_college_name']) ? $qualification_arr[$key]['cer_college_name'] : '';
                    $university_val          = isset($qualification_arr[$key]['cer_university']) ? $qualification_arr[$key]['cer_university'] : '';
                    $from_date_val           = isset($qualification_arr[$key]['cer_from_date']) ? $qualification_arr[$key]['cer_from_date'] : '';
                    $to_date_val             = isset($qualification_arr[$key]['cer_to_date']) ? $qualification_arr[$key]['cer_to_date'] : '';
                    // $marks_obtained_val      = isset($cer_marks_obtained[$key]) ? $cer_marks_obtained[$key] : '';
                    // $aggregate_max_marks_val = isset($cer_aggregate_max_marks[$key]) ? $cer_aggregate_max_marks[$key] : '';
                    $percentage_val          = isset($qualification_arr[$key]['cer_percentage']) ? $qualification_arr[$key]['cer_percentage'] : '';
                    $class_val               = isset($qualification_arr[$key]['cer_class']) ? $qualification_arr[$key]['cer_class'] : '';
                ?>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Qualification</label>
                      <div class="col-sm-5"><?php echo $qua_name_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Name of the Subject</label>
                      <div class="col-sm-5"><?php echo $gra_sub_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">College / Institution</label>
                      <div class="col-sm-5"><?php echo $college_name_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">University</label>
                      <div class="col-sm-5"><?php echo $university_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Period</label>
                      <div class="col-sm-5">From: <?php echo $from_date_val; ?> - To: <?php echo $to_date_val; ?></div>
                    </div>

                    <!-- <div class="form-group">
                      <label class="col-sm-4 control-label">Aggregate Marks Obtained</label>
                      <div class="col-sm-5"><?php echo $marks_obtained_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Aggregate Maximum Marks</label>
                      <div class="col-sm-5"><?php echo $aggregate_max_marks_val; ?></div>
                    </div> -->

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Final Percentage</label>
                      <div class="col-sm-5"><?php echo $percentage_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Class</label>
                      <div class="col-sm-5"><?php echo $class_val; ?></div>
                    </div>

                    <hr>

                <?php
                    $i++;
                  }
                } else {
                  // echo '<div class="form-group"><div class="col-sm-12 text-center text-muted">No additional qualifications found.</div></div>';
                }
                ?>





                <div class="box-title box-header"><strong>CAIIB</strong> </div>
                <br />
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">CAIIB</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['ess_subject']; ?> </div>
                </div>
                <!-- <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Year of Passing</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['year_of_passing']; ?> </div>
                </div> -->
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Membership Number</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['membership_number']; ?> </div>
                </div>
                <div class="box-title box-header"><strong>PH D (IN BANKING OR FINANCE)</strong> </div>
                <br />
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name of the Course</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['phd_course']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">University</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['phd_university']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Period </label>
                  <div class="col-sm-5">From: <?php echo $this->session->userdata['enduserinfo']['phd_from_date'];
                                              ?> - To: <?php echo $this->session->userdata['enduserinfo']['phd_to_date'];
                                                        ?></div>
                </div>
                <div class="box-title box-header"><strong>DESIRABLE</strong> </div>
                <br />
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name Of Course </label>
                  <div class="col-sm-6" style="word-wrap: break-word;">
                    <?php
                    $course_code = $this->session->userdata['enduserinfo']['course_code'];
                    if (count($careers_course_mst) > 0) {
                      foreach ($careers_course_mst as $row1) {
                        if ($course_code == $row1['course_code']) {
                          echo  $row1['course_name'];
                        }
                      }
                    }

                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Specialization </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['name_subject_of_course']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">College Name and Address </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['college_name']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">University </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['university']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Period </label>
                  <div class="col-sm-5">From: <?php echo $this->session->userdata['enduserinfo']['des_from_date']; ?> - To: <?php echo $this->session->userdata['enduserinfo']['des_to_date']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Grade/Percentage </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['percentage']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Class </label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['class']; ?> </div>
                </div>
                <div class="box-title box-header"><strong>PUBLICATION</strong> </div>
                <br />
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Publication of Books</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['publication_of_books']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Publication of articles (give latest, not more than ten)</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['publication_of_articles']; ?> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Area of Specialization</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['area_of_specialization']; ?> </div>
                </div>

                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">EMPLOYMENT HISTORY (from Recent employment to Oldest employment) - Last 7 positions held with role & responsibilities in detail</h3>
                  </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Whether currently in service</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo ($this->session->userdata['enduserinfo']['whether_in_service']); ?> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name of the Present Organisation</label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo strtoupper($this->session->userdata['enduserinfo']['name_of_present_organization']); ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Period </label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo strtoupper($this->session->userdata['enduserinfo']['service_from_date']); ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Communication Address of the Organisation </label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo strtoupper($this->session->userdata['enduserinfo']['comm_address_of_org']); ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Designation/Post Held </label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo strtoupper($this->session->userdata['enduserinfo']['curr_designation']); ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Any Other Details </label>
                  <div class="col-sm-5" style="word-wrap: break-word;"> <?php echo strtoupper($this->session->userdata['enduserinfo']['any_other_details']); ?> </div>
                </div>
                <?php

                $i = 0;
                $experience_as_principal =  $experience_as_faculty = $experience_as_principal_val = $experience_as_faculty_val = '';
                $organization = $this->session->userdata['enduserinfo']['organization'];
                $designation = $this->session->userdata['enduserinfo']['designation'];
                $responsibilities = $this->session->userdata['enduserinfo']['responsibilities'];
                $job_from_date = $this->session->userdata['enduserinfo']['job_from_date'];
                $job_to_date = $this->session->userdata['enduserinfo']['job_to_date'];
                $experience_as_principal = $this->session->userdata['enduserinfo']['experience_as_principal'];
                $experience_as_faculty = $this->session->userdata['enduserinfo']['experience_as_faculty'];

                foreach ($organization as $job_key => $job_val) {
                  $organization_val = $job_val;
                  $designation_val = $designation[$job_key];
                  $responsibilities_val = $responsibilities[$job_key];
                  $job_from_date_val = $job_from_date[$job_key];
                  $job_to_date_val = $job_to_date[$job_key];

                  if (isset($experience_as_principal[$job_key])) {
                    $experience_as_principal_val = $experience_as_principal[$job_key];
                  }
                  if (isset($experience_as_faculty[$job_key])) {
                    $experience_as_faculty_val = $experience_as_faculty[$job_key];
                  }

                ?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Name of the Organisation </label>
                    <div class="col-sm-5"><?php echo $organization_val; ?></div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Designation </label>
                    <div class="col-sm-5"><?php echo $designation_val; ?></div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Responsibilities </label>
                    <div class="col-sm-5"><?php echo $responsibilities_val; ?></div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Period </label>
                    <div class="col-sm-5">From: <?php echo $job_from_date_val; ?> - To: <?php echo $job_to_date_val; ?></div>
                  </div>
                <?php

                  $i++;
                }

                ?>

                <!-- <?php

                      $i = 0;
                      $experience_as_principal =  $experience_as_faculty = $experience_as_principal_val = $experience_as_faculty_val = '';

                      $experience_as_principal = $this->session->userdata['enduserinfo']['experience_as_principal'];
                      $experience_as_faculty = $this->session->userdata['enduserinfo']['experience_as_faculty'];

                      foreach ($organization as $job_key => $job_val) {


                        if (isset($experience_as_principal[$job_key])) {
                          $experience_as_principal_val = $experience_as_principal[$job_key];
                        }
                        if (isset($experience_as_faculty[$job_key])) {
                          $experience_as_faculty_val = $experience_as_faculty[$job_key];
                        }


                        if ($i == 0) { ?> -->

                <!-- <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Experience as Principal / Director of a banking staff training college / centre/ management institution </label>
                      <div class="col-sm-5"><?php echo $experience_as_principal_val; ?></div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Experience as Faculty , Professor, Lecturer</label>
                      <div class="col-sm-5"><?php echo $experience_as_faculty_val; ?></div>
                    </div>
                <?php }
                        $i++;
                      }


                ?> -->


                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">OTHER ORGANISATION</h3>
                  </div>
                </div>

                <?php
                $i = 0;

                // Fetch arrays (like Employment History)
                $org_organization             = isset($this->session->userdata['enduserinfo']['organization_arr']) ? $this->session->userdata['enduserinfo']['organization_arr'] : [];

                // Loop through and display qualification details
                if (!empty($org_organization) && is_array($org_organization)) {
                  foreach ($org_organization as $key => $val) {
                    $othr_val            = isset($org_organization[$key]['org_organization']) ? $org_organization[$key]['org_organization'] : '';
                    $othr_from_date_val           = isset($org_organization[$key]['org_from_date']) ? $org_organization[$key]['org_from_date'] : '';
                    $othr_to_date_val             = isset($org_organization[$key]['org_to_date']) ? $org_organization[$key]['org_to_date'] : '';
                    $othr_desg_val      = isset($org_organization[$key]['org_designation']) ? $org_organization[$key]['org_designation'] : '';
                    $othr_resp_val = isset($org_organization[$key]['org_responsibilities']) ? $org_organization[$key]['org_responsibilities'] : '';
                ?>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Name of the Organisation</label>
                      <div class="col-sm-5"><?php echo $othr_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Period</label>
                      <div class="col-sm-5">From: <?php echo $othr_from_date_val; ?> - To: <?php echo $othr_to_date_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Designation</label>
                      <div class="col-sm-5"><?php echo $othr_desg_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Responsibilities</label>
                      <div class="col-sm-5"><?php echo $othr_resp_val; ?></div>
                    </div>

                    <hr>

                <?php
                    $i++;
                  }
                } else {
                  //echo '<div class="form-group"><div class="col-sm-12 text-center text-muted">No additional qualifications found.</div></div>';
                }
                ?>



                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">EXPERIENCE AS PRINCIPAL / DIRECTOR OF A BANKING STAFF TRAINING COLLEGE / CENTRE / MANAGEMENT INSTITUTION</h3>
                  </div>
                </div>

                <?php
                $i = 0;

                // Fetch arrays (like Employment History)
                $exp_org_organization             = isset($this->session->userdata['enduserinfo']['exp_org_organization']) ? $this->session->userdata['enduserinfo']['exp_org_organization'] : [];
                $exp_job_from_date            = isset($this->session->userdata['enduserinfo']['exp_job_from_date']) ? $this->session->userdata['enduserinfo']['exp_job_from_date'] : [];
                $exp_job_to_date              = isset($this->session->userdata['enduserinfo']['exp_job_to_date']) ? $this->session->userdata['enduserinfo']['exp_job_to_date'] : [];
                $exp_designation       = isset($this->session->userdata['enduserinfo']['exp_designation']) ? $this->session->userdata['enduserinfo']['exp_designation'] : [];
                $exp_responsibilities  = isset($this->session->userdata['enduserinfo']['exp_responsibilities']) ? $this->session->userdata['enduserinfo']['exp_responsibilities'] : [];

                // Loop through and display qualification details
                if (!empty($exp_org_organization) && is_array($exp_org_organization)) {
                  foreach ($exp_org_organization as $key => $val) {
                    $bank_val            = isset($exp_org_organization[$key]) ? $exp_org_organization[$key] : '';
                    $bank_from_date_val           = isset($exp_job_from_date[$key]) ? $exp_job_from_date[$key] : '';
                    $bank_to_date_val             = isset($exp_job_to_date[$key]) ? $exp_job_to_date[$key] : '';
                    $bank_desg_val      = isset($exp_designation[$key]) ? $exp_designation[$key] : '';
                    $bank_resp_val = isset($exp_responsibilities[$key]) ? $exp_responsibilities[$key] : '';
                ?>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Name of the Organisation</label>
                      <div class="col-sm-5"><?php echo $bank_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Period</label>
                      <div class="col-sm-5">From: <?php echo $bank_from_date_val; ?> - To: <?php echo $bank_from_date_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Designation</label>
                      <div class="col-sm-5"><?php echo $bank_desg_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Responsibilities</label>
                      <div class="col-sm-5"><?php echo $bank_resp_val; ?></div>
                    </div>

                    <hr>

                <?php
                    $i++;
                  }
                } else {
                  echo '<div class="form-group"><div class="col-sm-12 text-center text-muted">No additional qualifications found.</div></div>';
                }
                ?>


                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">EXPERIENCE AS FACULTY</h3>
                  </div>
                </div>

                <?php
                $i = 0;

                // Fetch arrays (like Employment History)
                $exp_in_bank             = isset($this->session->userdata['enduserinfo']['exp_in_bank']) ? $this->session->userdata['enduserinfo']['exp_in_bank'] : [];
                // $cer_gra_sub              = isset($this->session->userdata['enduserinfo']['cer_gra_sub']) ? $this->session->userdata['enduserinfo']['cer_gra_sub'] : [];
                // $cer_college_name         = isset($this->session->userdata['enduserinfo']['cer_college_name']) ? $this->session->userdata['enduserinfo']['cer_college_name'] : [];
                // $cer_university           = isset($this->session->userdata['enduserinfo']['cer_university']) ? $this->session->userdata['enduserinfo']['cer_university'] : [];
                $exp_faculty_from_date            = isset($this->session->userdata['enduserinfo']['exp_faculty_from_date']) ? $this->session->userdata['enduserinfo']['exp_faculty_from_date'] : [];
                $exp_faculty_to_date              = isset($this->session->userdata['enduserinfo']['exp_faculty_to_date']) ? $this->session->userdata['enduserinfo']['exp_faculty_to_date'] : [];
                $subject_handled       = isset($this->session->userdata['enduserinfo']['subject_handled']) ? $this->session->userdata['enduserinfo']['subject_handled'] : [];
                $exp_in_functional_area  = isset($this->session->userdata['enduserinfo']['exp_in_functional_area']) ? $this->session->userdata['enduserinfo']['exp_in_functional_area'] : [];
                $professional_ass           = isset($this->session->userdata['enduserinfo']['professional_ass']) ? $this->session->userdata['enduserinfo']['professional_ass'] : [];
                // $cer_class                = isset($this->session->userdata['enduserinfo']['cer_class']) ? $this->session->userdata['enduserinfo']['cer_class'] : [];

                // Loop through and display qualification details
                if (!empty($exp_in_bank) && is_array($exp_in_bank)) {
                  foreach ($exp_in_bank as $key => $val) {
                    $exp_val            = isset($exp_in_bank[$key]) ? $exp_in_bank[$key] : '';
                    // $gra_sub_val             = isset($cer_gra_sub[$key]) ? $cer_gra_sub[$key] : '';
                    // $college_name_val        = isset($cer_college_name[$key]) ? $cer_college_name[$key] : '';
                    // $university_val          = isset($cer_university[$key]) ? $cer_university[$key] : '';
                    $exp_from_date_val           = isset($exp_faculty_from_date[$key]) ? $exp_faculty_from_date[$key] : '';
                    $exp_to_date_val             = isset($exp_faculty_to_date[$key]) ? $exp_faculty_to_date[$key] : '';
                    $exp_sub_val      = isset($subject_handled[$key]) ? $subject_handled[$key] : '';
                    $exp_fun_area_val = isset($exp_in_functional_area[$key]) ? $exp_in_functional_area[$key] : '';
                    $exp_prof_val          = isset($professional_ass[$key]) ? $professional_ass[$key] : '';
                    // $class_val               = isset($cer_class[$key]) ? $cer_class[$key] : '';
                ?>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Experience as Faculty</label>
                      <div class="col-sm-5"><?php echo $exp_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Period</label>
                      <div class="col-sm-5">From: <?php echo $exp_from_date_val; ?> - To: <?php echo $exp_to_date_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Subjects Handled</label>
                      <div class="col-sm-5"><?php echo $exp_sub_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Area of Specialisation</label>
                      <div class="col-sm-5"><?php echo $exp_fun_area_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Membership of Professional Associations</label>
                      <div class="col-sm-5"><?php echo $exp_prof_val; ?></div>
                    </div>

                    <!-- <div class="form-group">
                      <label class="col-sm-4 control-label">Aggregate Marks Obtained</label>
                      <div class="col-sm-5"><?php echo $marks_obtained_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Aggregate Maximum Marks</label>
                      <div class="col-sm-5"><?php echo $aggregate_max_marks_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Final Percentage</label>
                      <div class="col-sm-5"><?php echo $percentage_val; ?></div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Class</label>
                      <div class="col-sm-5"><?php echo $class_val; ?></div>
                    </div> -->

                    <hr>

                <?php
                    $i++;
                  }
                } else {
                  echo '<div class="form-group"><div class="col-sm-12 text-center text-muted">No additional qualifications found.</div></div>';
                }
                ?>





                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">PROFESSIONAL REFERENCE I</h3>
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
                  <label for="roleid" class="col-sm-4 control-label">Organisation</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['reforganisation_one']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Designation </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refdesignation_one']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email Id</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['refemail_one']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refmobile_one']; ?></div>
                </div>

                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">PROFESSIONAL REFERENCE 2</h3>
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
                  <label for="roleid" class="col-sm-4 control-label">Organisation</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['reforganisation_two']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Designation </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refdesignation_two']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email Id</label>
                  <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['refemail_two']; ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile </label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refmobile_two']; ?></div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">OTHER INFORMATION</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">1. Earliest date of Joining if Selected

                  </label>
                  <div class="col-sm-6" style="word-wrap: break-word;">
                    <?php
                    $earliest_date_of_joining = $this->session->userdata['enduserinfo']['earliest_date_of_joining'];
                    echo wordwrap($earliest_date_of_joining, 75, "<br>\n");

                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">2. Why do you consider yourself suitable of the post of CEO of this Institute</label>
                  <div class="col-sm-6" style="word-wrap: break-word;">
                    <?php
                    $suitable_of_the_post_of_CEO = $this->session->userdata['enduserinfo']['suitable_of_the_post_of_CEO'];
                    echo wordwrap($suitable_of_the_post_of_CEO, 75, "<br>\n");

                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">3. Any other information that the candidate would like to add</label>
                  <div class="col-sm-6" style="word-wrap: break-word;">
                    <?php
                    $comment = $this->session->userdata['enduserinfo']['comment'];
                    echo wordwrap($comment, 75, "<br>\n");

                    ?>
                  </div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">DECLARATION</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Declaration&nbsp;</label>
                  <div class="col-sm-8" align="justify"> <strong><?php echo $this->session->userdata['enduserinfo']['declaration2']; ?></strong> &nbsp;
                    &nbsp;I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criterias according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">UPLOAD</h3>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedphoto']; ?>" height="100" width="100"></label>
                  <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedsignaturephoto']; ?>" height="100" width="100"></label>
                  <!--<label for="roleid" class="col-sm-3 control-label"><a href="<?php //echo $this->
                                                                                  //session->userdata['enduserinfo']['uploadcv_path'];
                                                                                  ?>" target="_blank"><img src="<?php //echo base_url() 
                                                                                                                ?>/uploads/uploadcv/resume.png" height="100" width="100">Download</a>
              </label>
              -->
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Uploaded Photo</label>
                  <label for="roleid" class="col-sm-3 control-label">Uploaded Signature</label>
                  <!--<label for="roleid" class="col-sm-3 control-label">uploaded Resume/CV</label>-->
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Place & Date</h3>
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