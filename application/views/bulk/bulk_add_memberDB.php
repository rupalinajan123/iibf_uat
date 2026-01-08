<!-- custom style for datepicker dropdowns -->

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



  .example {

    width: 33%;

    min-width: 370px;

    /* padding: 15px;*/

    display: inline-block;

    box-sizing: border-box;

    /*text-align: center;*/

  }



  .example select {

    padding: 10px;

    background: #ffffff;

    border: 1px solid #CCCCCC;

    border-radius: 3px;

    margin: 0 3px;

  }



  .example select.invalid {

    color: #E9403C;

  }



  .mandatory-field,

  .required-spn {

    color: #F00;

  }



  .box-title-hd {

    color: #3c8dbc;

    font-size: 16px;

    margin: 0;

  }
</style>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1 class="register"> Examination Application(Registration) for Non-Member<br />

    </h1>

    <span style="color:#F00"></span>

    <!--<ol class="breadcrumb">

        <li><a href="<?php //echo base_url();
                      ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());
                                          ?></a></li>

        <li class="active">Manage Users</li>

      </ol>-->

  </section>

  <section class="content">

    <div class="row">

      <div class="col-md-12">

        <div class="box box-info">

          <!-- form start -->



          <?php //echo validation_errors(); 
          ?>

          <?php if ($this->session->flashdata('error') != '') { ?>

            <div class="alert alert-danger alert-dismissible" id="error_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->

              <?php echo $this->session->flashdata('error'); ?>

            </div>

          <?php }
          if ($this->session->flashdata('success') != '') { ?>

            <div class="alert alert-success alert-dismissible" id="success_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->

              <?php echo $this->session->flashdata('success'); ?>

            </div>

          <?php }

          if (validation_errors() != '') { ?>

            <div class="alert alert-danger alert-dismissible" id="error_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->

              <?php echo validation_errors(); ?>

            </div>

          <?php }

          ?>



          <div class="box-header with-border">



            <h3 class="box-title">Get Details</h3>

          </div>



          <div class="box-body">

            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>bulk/BulkApplyDB/add_member/">

              <div class="form-group">

                <label class="col-sm-3 control-label">Membership No :</label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" name="regnumber" placeholder="Registration no" value="" />

                </div>

                <button name="getdata">Get Details</button>

              </div>

              <!--  <div class="col-sm-12" align="center"> <span style="color:#F00; font-size:14px;">Please insert your 'Membership No.' and click on 'Get Details' button. </span> </div>-->

            </form>

          </div>

        </div>

        <?php if (!empty($mem_info)) {

          print_r($mem_info); ?>

        <?php } ?>



        <form class="form-horizontal" name="nonmemAddForm" id="nonmemAddForm" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>bulk/BulkApplyDB/comApplication_reg/">



          <!-- Horizontal Form -->

          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">Basic Details</h3>

            </div>

            <!-- /.box-header -->

            <?php



            $ex_prd = '';

            if (isset($this->session->userdata['exmCrdPrd']['exam_prd'])) {

              $ex_prd = $this->session->userdata['exmCrdPrd']['exam_prd'];
            }

            $discount_flg = 'Y';

            ?>





            <div class="box-body">

              <input type="hidden" name="discount_flag" id="discount_flag" value="<?php echo $discount_flg ?>" />

              <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type']; ?>">

              <input type="hidden" id="exname" name="exname" value=" <?php echo $examinfo[0]['description']; ?>">



              <input id="examcode" name="examcode" type="hidden" value="<?php echo $this->session->userdata('examcode'); ?>">

              <input type="hidden" id="excd" name="excd" value="<?php echo base64_encode($this->session->userdata('examcode')); ?>">

              <input id="eprid" name="eprid" type="hidden" value="<?php echo $ex_prd; ?>">

              <input id="exmonth" name="exmonth" type="hidden" value="<?php echo $examinfo[0]['exam_month']; ?>">

              <input type='hidden' name='free_paid_flag' id='free_paid_flag' value="P">



              <!--<div class="form-group">

                                <label for="roleid" class="col-sm-3 control-label">Registration no</label>

                                <div class="col-sm-5">

                                    <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="Registration no"  value="<?php echo set_value('reg_no'); ?>" />



                                    <span class="error"><?php //echo form_error('reg_no');
                                                        ?></span>

                                </div>(Only for re-exam)<button name="get_details" class="dra-get-memdetails">Get Details</button>

            </div>-->

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">First Name <span style="color:#f00">*</span></label>

                <div class="col-sm-2">

                  <select name="sel_namesub" id="sel_namesub" class="form-control" required>

                    <option value="">Select</option>

                    <option value="Mr." <?php echo set_select('sel_namesub', 'Mr.'); ?>>Mr.</option>

                    <option value="Mrs." <?php echo set_select('sel_namesub', 'Mrs.'); ?>>Mrs.</option>

                    <option value="Ms." <?php echo set_select('sel_namesub', 'Ms.'); ?>>Ms.</option>

                    <option value="Dr." <?php echo set_select('sel_namesub', 'Dr.'); ?>>Dr.</option>

                    <option value="Prof." <?php echo set_select('sel_namesub', 'Prof.'); ?>>Prof.</option>

                  </select>

                  <span class="error" id="tiitle_error">

                    <?php //echo form_error('firstname');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)

                <div class="col-sm-3">

                  <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo set_value('firstname'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z- ]+$/" data-parsley-maxlength="30">

                  <span class="error">

                    <?php //echo form_error('firstname');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" value="<?php echo set_value('middlename'); ?>" data-parsley-pattern="/^[a-zA-Z- ]+$/" data-parsley-maxlength="30">

                  <span class="error">

                    <?php //echo form_error('middlename');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Last Name</label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo set_value('lastname'); ?>" data-parsley-pattern="/^[a-zA-Z- ]+$/" data-parsley-maxlength="30">

                  <span class="error">

                    <?php //echo form_error('lastname');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)
              </div>

            </div>

          </div>

          <!-- Basic Details box closed-->

          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">Contact Details</h3>

            </div>

            <!--<div class="box-header with-border nobg"></div>-->

            <h6 class="box-title-hd">Office/Residential Address for communication (Pl do not repeat the name of the Applicant, Only Address to be typed)</h6>



            <div class="box-body">

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Address line1<span style="color:#f00">*</span></label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo set_value('addressline1'); ?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">

                  <span class="error">

                    <?php //echo form_error('addressline1');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Address line2</label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2" value="<?php echo set_value('addressline2'); ?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">

                  <span class="error">

                    <?php //echo form_error('addressline2');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Address line3</label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3" value="<?php echo set_value('addressline3'); ?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">

                  <span class="error">

                    <?php //echo form_error('addressline3');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Address line4</label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4" value="<?php echo set_value('addressline4'); ?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">

                  <span class="error">

                    <?php //echo form_error('addressline4');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">District<span style="color:#f00">*</span></label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30">

                  <span class="error">

                    <?php //echo form_error('district');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">City<span style="color:#f00">*</span></label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30">

                  <span class="error">

                    <?php //echo form_error('city');
                    ?>

                  </span>
                </div>

                (Max 30 Characters)

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">State<span style="color:#f00">*</span></label>

                <div class="col-sm-3">

                  <select class="form-control" id="state" name="state" required>

                    <option value="">Select</option>

                    <?php if (count($states) > 0) {

                      foreach ($states as $row1) {   ?>

                        <option value="<?php echo $row1['state_code']; ?>" <?php echo set_select('state', $row1['state_code']); ?>>

                          <?php echo $row1['state_name']; ?>

                        </option>

                    <?php }
                    } ?>

                  </select>

                  <input hidden="statepincode" id="statepincode" value="">

                </div>

                <label for="roleid" class="col-sm-1 control-label">Pincode/Zipcode<span style="color:#f00">*</span></label>

                <div class="col-sm-3">

                  <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode'); ?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-nonmemcheckpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout"> (Max 6 digits) <span class="error">

                    <?php //echo form_error('pincode');
                    ?>

                  </span>
                </div>

              </div>



              <!--<div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>

                    <div class="col-sm-2">

                      <input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob'); ?>" >

                      <span class="error"><?php //echo form_error('dob');
                                          ?></span>

                    </div>

                </div>-->



              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Date of Birth <span style="color:#f00">*</span></label>

                <div class="col-sm-4 example">

                  <input type="hidden" id="dob1" name="dob" required>

                  <input type="hidden" id="doj1" name="doj" value="">

                  <?php

                  $min_year = date('Y', strtotime("- 18 year"));

                  $max_year = date('Y', strtotime("- 60 year"));

                  ?>

                  <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">

                  <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">

                  <span id="dob_error" class="error"></span>
                </div>



                <!--<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob'); ?>" >-->

                <span class="error">

                  <?php //echo form_error('dob');
                  ?>

                </span>
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Gender<span style="color:#f00">*</span></label>

                <div class="col-sm-3">

                  <input type="radio" class="minimal cls_gender" id="female" checked="checked" name="gender" required value="female" <?php echo set_radio('gender', 'female'); ?>> Female

                  <input type="radio" class="minimal cls_gender" id="male" name="gender" required value="male" <?php echo set_radio('gender', 'male'); ?>> Male <span class="error">

                    <?php //echo form_error('gender');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Qualification <span style="color:#f00">*</span></label>

                <div class="col-sm-6">

                  <input type="radio" class="minimal" id="U" name="optedu" value="U" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'U'); ?>> Under Graduate

                  <input type="radio" class="minimal" id="G" name="optedu" value="G" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'G'); ?>> Graduate

                  <input type="radio" class="minimal" id="P" name="optedu" value="P" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'P'); ?>> Post Graduate <span class="error">

                    <?php //echo form_error('optedu');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Please specify <span style="color:#f00">*</span></label>

                <div class="col-sm-5" <?php if (set_value('eduqual1') || set_value('eduqual2') || set_value('eduqual3')) {
                                        echo 'style="display:none"';
                                      } else {
                                        echo 'style="display:block"';
                                      } ?> id="edu">

                  <select id="eduqual" name="eduqual" class="form-control" <?php if (!set_value('eduqual1') && !set_value('eduqual2') && !set_value('eduqual3')) {
                                                                              echo 'required';
                                                                            } ?>>

                    <option value="" selected="selected">--Select--</option>

                  </select>

                </div>

                <div class="col-sm-5" <?php if (set_value('optedu') == 'U') {
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

                        <option value="<?php echo $row1['qid']; ?>" <?php echo set_select('eduqual1', $row1['qid']); ?>>

                          <?php echo $row1['name']; ?>

                        </option>

                    <?php }
                    } ?>

                  </select>

                  <span class="error">

                    <?php //echo form_error('eduqual1');
                    ?>

                  </span>
                </div>

                <div class="col-sm-5" <?php if (set_value('optedu') == 'G') {
                                        echo 'style="display:block"';
                                      } else {
                                        echo 'style="display:none"';
                                      } ?> id="GR">

                  <select class="form-control" id="eduqual2" name="eduqual2" <?php if (set_value('optedu') == 'G') {
                                                                                echo 'required';
                                                                              } ?>>

                    <option value="">--Select--</option>

                    <?php if (count($graduate)) {

                      foreach ($graduate as $row2) {   ?>

                        <option value="<?php echo $row2['qid']; ?>" <?php echo set_select('eduqual2', $row2['qid']); ?>>

                          <?php echo $row2['name']; ?>

                        </option>

                    <?php }
                    } ?>

                  </select>

                  <span class="error">

                    <?php //echo form_error('eduqual2');
                    ?>

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

                      foreach ($postgraduate as $row3) {   ?>

                        <option value="<?php echo $row3['qid']; ?>" <?php echo set_select('eduqual3', $row3['qid']); ?>>

                          <?php echo $row3['name']; ?>

                        </option>

                    <?php }
                    } ?>

                  </select>

                  <span class="error">

                    <?php //echo form_error('eduqual3');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#f00">*</span></label>

                <div class="col-sm-6 email">

                  <input type="text" class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo set_value('email'); ?>" required data-parsley-nonmememailcheck data-parsley-trigger-after-failure="null" /> (Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail) <span class="error">

                    <?php //echo form_error('email');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Phone </label>

                <div class="col-sm-4">

                  <label for="roleid" class="col-sm-4 control-label" style="padding-left:0; text-align:left; padding-right:10px;">STD Code</label>

                  <input type="text" class="form-control" id="stdcode" name="stdcode" placeholder="STD Code" data-parsley-type="number" data-parsley-maxlength="4" value="<?php echo set_value('stdcode'); ?>" style="width:55%;" data-parsley-trigger-after-failure="focusout">

                  <span class="error">

                    <?php //echo form_error('stdcode');
                    ?>

                  </span>
                </div>

                <div class="col-sm-4">

                  <label for="roleid" class="col-sm-4 control-label" style="padding-left:0; text-align:left; padding-right:10px;">Phone No</label>

                  <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone No" data-parsley-minlength="7" data-parsley-type="number" data-parsley-maxlength="12" value="<?php echo set_value('phone'); ?>" style="width:65%;" data-parsley-trigger-after-failure="focusout">

                  <span class="error">

                    <?php //echo form_error('phone');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Mobile<span style="color:#f00">*</span></label>

                <div class="col-sm-5">

                  <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('mobile'); ?>" data-parsley-dbfmobilecheck required data-parsley-trigger-after-failure="null">

                  <span class="error">

                    <?php //echo form_error('mobile');
                    ?>

                  </span>
                </div>

              </div>

              <?php if (
                $this->session->userdata('examcode') == 101

                && $this->session->userdata('examcode') == 1010

                && $this->session->userdata('examcode') == 10100

                && $this->session->userdata('examcode') == 101000

                && $this->session->userdata('examcode') == 1010000

                && $this->session->userdata('examcode') == 10100000

                && $this->session->userdata('examcode') == 996
              ) { ?>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number </label>

                  <div class="col-sm-5">

                    <input type="text" class="form-control " id="aadhar_card" name="aadhar_card" placeholder="Aadhar Card Number" data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" value="<?php echo set_value('aadhar_card'); ?>" data-parsley-trigger-after-failure="focusout">

                    <!--(Max 25 Characters)-->

                    <span class="error">

                      <?php //echo form_error('idNo');
                      ?>

                    </span>
                  </div>

                </div>

              <?php

              } else { ?>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number

                    <!--<span style="color:#f00">*</span>--></label>

                  <div class="col-sm-5">

                    <?php /*?>

                                                            <input type="text" class="form-control " id="aadhar_card" name="aadhar_card" placeholder="Aadhar Card Number" data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" required value="<?php echo set_value('aadhar_card');?>" data-parsley-trigger-after-failure="focusout">

                                                            <!--(Max 25 Characters)-->

                                                            <span class="error"><?php //echo form_error('idNo');?></span>

                                                            <?php */ ?>

                    <input type="text" class="form-control " id="aadhar_card" name="aadhar_card" placeholder="Aadhar Card Number" data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" value="<?php echo set_value('aadhar_card'); ?>" data-parsley-trigger-after-failure="focusout">

                    <!--(Max 25 Characters)-->

                    <span class="error">

                      <?php //echo form_error('idNo');
                      ?>

                    </span>
                  </div>

                </div>

              <?php

              } ?>

              <?php $is_flag = 0; ?>



              <?php if (count($bulk_branch_master) > 0) {

                $is_flag = 1; ?>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Bank Branch</label>

                  <div class="col-sm-4">

                    <select class="form-control" id="bank_branch" name="bank_branch">

                      <option value="">Select</option>

                      <?php if (count($bulk_branch_master) > 0) {

                        foreach ($bulk_branch_master as $row1) {   ?>

                          <option value="<?php echo $row1['id']; ?>" <?php echo set_select('bank_branch', $row1['id']); ?>>

                            <?php echo $row1['bname']; ?>

                          </option>

                      <?php }
                      } ?>

                    </select>

                  </div>

                </div>

              <?php } else { ?>

                <input type="hidden" name="bank_branch" value="">

              <?php } ?>



              <?php if (count($bulk_designation_master) > 0) {

                $is_flag = 1; ?>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Bank Designation</label>

                  <div class="col-sm-4" style="display:block">

                    <select id="bank_designation" name="bank_designation" class="form-control">

                      <option value="">Select</option>

                      <?php if (count($bulk_designation_master)) {

                        foreach ($bulk_designation_master as $designation_row) {   ?>

                          <option value="<?php echo $designation_row['id']; ?>" <?php echo  set_select('bank_designation', $designation_row['id']); ?>><?php echo $designation_row['dname']; ?></option>

                      <?php }
                      } ?>

                    </select>

                    <span class="error">

                      <?php //echo form_error('designation');
                      ?>

                    </span>

                  </div>

                </div>

              <?php } else { ?>

                <input type="hidden" name="bank_designation" value="">

              <?php } ?>



              <?php if (count($bulk_payment_scale_master) > 0) {

                $is_flag = 1; ?>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Pay Scale</label>

                  <div class="col-sm-4">

                    <select class="form-control" id="bank_scale" name="bank_scale">

                      <option value="">Select</option>

                      <?php if (count($bulk_payment_scale_master) > 0) {

                        foreach ($bulk_payment_scale_master as $row1) {   ?>

                          <option value="<?php echo $row1['id']; ?>" <?php echo set_select('bank_scale', $row1['id']); ?>>

                            <?php echo $row1['pay_scale']; ?>

                          </option>

                      <?php }
                      } ?>

                    </select>

                  </div>

                </div>

              <?php } else { ?>

                <input type="hidden" name="bank_scale" value="">

              <?php } ?>



              <?php if (count($bulk_zone_master) > 0) {

                $is_flag = 1; ?>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Bank Zone</label>

                  <div class="col-sm-4">

                    <select class="form-control" id="bank_zone" name="bank_zone">

                      <option value="">Select</option>

                      <?php if (count($bulk_zone_master) > 0) {

                        foreach ($bulk_zone_master as $row1) {   ?>

                          <option value="<?php echo $row1['zone_id']; ?>" <?php echo set_select('bank_zone', $row1['zone_id']); ?>>

                            <?php echo $row1['zone_code']; ?>

                          </option>

                      <?php }
                      } ?>

                    </select>

                  </div>

                </div>

              <?php } else { ?>

                <input type="hidden" name="bank_zone" value="">

              <?php } ?>



              <?php /* if($is_flag == 1){?> <?php } else { ?>

                                        <input type="hidden" name="bank_emp_id" value="">

                                        <?php } */ ?>



              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Bank Employee Id<span style="color:#f00">*</span></label>

                <div class="col-sm-5">

                  <input type="text" class="form-control" id="bank_emp_id" name="bank_emp_id" placeholder="Employee Id" value="<?php echo set_value('bank_emp_id'); ?>" data-parsley-maxlength="20" required>

                  <span class="error">

                    <?php //echo form_error('city');
                    ?>

                  </span>

                </div>

              </div>



              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#f00">**</span></label>

                <div class="col-sm-5">

                  <input type="file" class="" name="scannedphoto" id="scannedphoto" required>

                  <input type="hidden" id="hiddenphoto" name="hiddenphoto">

                  <div id="error_photo"></div>

                  <br>

                  <div id="error_photo_size"></div>

                  <span class="photo_text" style="display:none;"></span> <span class="error">

                    <?php //echo form_error('scannedphoto');
                    ?>

                  </span>
                </div>

                <img id="image_upload_scanphoto_preview" height="100" width="100" />
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen<span style="color:#f00">**</span></label>

                <div class="col-sm-5">

                  <input type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto" required>

                  <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">

                  <div id="error_signature"></div>

                  <br>

                  <div id="error_signature_size"></div>

                  <span class="signature_text" style="display:none;"></span> <span class="error">

                    <?php //echo form_error('scannedsignaturephoto');
                    ?>

                  </span>
                </div>

                <img id="image_upload_sign_preview" height="100" width="100" />
              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Select Id Proof <span style="color:#f00">*</span></label>

                <div class="col-sm-9">

                  <?php if (count($idtype_master) > 0) {

                    $i = 1;

                    foreach ($idtype_master as $idrow) { ?>

                      <input name="idproof" value="<?php echo $idrow['id']; ?>" type="radio" class="minimal" <?php if (set_value('idproof')) {
                                                                                                              echo set_radio('idproof', $idrow['id'], TRUE);
                                                                                                            } else {
                                                                                                              if ($i == 1) {
                                                                                                                echo 'checked="checked"';
                                                                                                              }
                                                                                                            } ?>>

                      <?php echo $idrow['name']; ?>

                      <br>

                  <?php

                      $i++;
                    }
                  } ?>

                  <span class="error">

                    <?php //echo form_error('idproof');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">ID No. <span style="color:#f00">*</span></label>

                <div class="col-sm-5">

                  <input type="text" class="form-control " id="idNo" name="idNo" placeholder="ID No." required value="<?php echo set_value('idNo'); ?>" data-parsley-pattern="/^[a-zA-Z0-9][a-zA-Z0-9 ]+$/" data-parsley-maxlength="25">

                  <!--(Max 25 Characters)-->

                  <span class="error">

                    <?php //echo form_error('idNo');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Upload your id proof <span style="color:#f00">**</span></label>

                <div class="col-sm-5">

                  <input type="file" class="" name="idproofphoto" id="idproofphoto" required>

                  <input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">

                  <div id="error_dob"></div>

                  <br>

                  <div id="error_dob_size"></div>

                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">

                    <?php //echo form_error('idproofphoto');
                    ?>

                  </span>
                </div>

                <img id="image_upload_idproof_preview" height="100" width="100" />
              </div>

              <input type="hidden" name="optnletter" value="N">

              <!--<div class="form-group">

                <label for="roleid" class="col-sm-9 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>

                    <div class="col-sm-2">



                       <input value="Y" name="optnletter" id="optnletter" checked="" type="radio"  <?php echo set_radio('optnletter', 'Y'); ?>>Yes

                        <input value="N" name="optnletter" id="optnletter" type="radio"  <?php echo set_radio('optnletter', 'N'); ?>>No

                      <span class="error"><?php //echo form_error('optnletter');
                                          ?></span>

                    </div>

                </div>-->



              <div class="form-group">

                <label for="roleid" class="col-sm-1 control-label"> Note</label>

                <div class="col-sm-9"> 1. Pl ensure all images are clear, visible and readable after uploading, if not do not submit and upload fresh set of images.</br>

                  2. Images format should be in JPG 8bit and size should be minimum 8KB and maximum 20KB.</br>

                  3. Image Dimension of Photograph should be 100(Width) * 120(Height) Pixel only</br>

                  4. Image Dimension of Signature should be 140(Width) * 60(Height) Pixel only</br>

                  5. Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. Size should be minimum 8KB and maximum 25KB.</br>

                </div>

              </div>

            </div>

          </div>

          <?php



          $ex_prd = '';

          if (isset($this->session->userdata['exmCrdPrd']['exam_prd'])) {

            $ex_prd = $this->session->userdata['exmCrdPrd']['exam_prd'];
          }

          ?>

          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">Exam Details:</h3>

            </div>

            <input type='hidden' id="hdnExamCode" maxlength="20" size="20" name="hdnExamCode" value="<?php echo $this->session->userdata('examcode'); ?>" />

            <input type='hidden' name='exid' id='exid' value="<?php echo $this->session->userdata('examcode'); ?>">

            <!--  <input type='hidden' name='mtype' id='mtype' value="<?php //echo $this->input->get('Mtype');
                                                                      ?>">-->

            <input type='hidden' name='mtype' id='mtype' value="DB">

            <input type='hidden' name='memtype' id='memtype' value="<?php echo base64_decode($this->input->get('Mtype')); ?>">

            <input id="eprid" name="eprid" type="hidden" value="<?php echo $ex_prd; ?>">

            <input type="hidden" value="" name="rrsub" id="rrsub" />



            <input id="excd" name="excd" type="hidden" value="<?php echo base64_encode($this->session->userdata('examcode')); ?>">

            <input id="exname" name="exname" type="hidden" value=" <?php echo $examinfo[0]['description']; ?>">

            <input id="fee" name="fee" type="hidden" value="">

            <input id="education_type" name="education_type" type="hidden" value="">

            <?php $grp_code = 'B1_1'; ?>

            <input id="grp_code" name="grp_code" type="hidden" value="<?php echo trim($grp_code); ?>">

            <div class="box-body">

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>

                <div class="col-sm-5 ">

                  <?php echo $examinfo[0]['description']; ?>

                  <div id="error_dob"></div>

                  <br>

                  <div id="error_dob_size"></div>

                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">

                    <?php //echo form_error('idproofphoto');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>

                <div class="col-sm-5 " id="html_fee_id">

                  <div style="color:#F00">select center first</div>

                  <?php //echo $examinfo[0]['fee_amount'];
                  ?>

                  <div id="error_dob"></div>

                  <br>

                  <div id="error_dob_size"></div>

                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">

                    <?php //echo form_error('idproofphoto');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>

                <div class="col-sm-5 ">

                  <?php



                  //$month = date('Y')."-".substr($examinfo[0]['exam_month'],4)."-".date('d');

                  $month = date('Y') . "-" . substr($examinfo[0]['exam_month'], 4);

                  echo date('F', strtotime($month)) . "-" . substr($examinfo[0]['exam_month'], 0, -2);

                  ?>

                  <div id="error_dob"></div>

                  <br>

                  <div id="error_dob_size"></div>

                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">

                    <?php //echo form_error('idproofphoto');
                    ?>

                  </span>
                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Medium <span style="color:#f00">*</span></label>

                <div class="col-sm-3">

                  <select name="medium" id="medium" class="form-control" required>

                    <option value="">Select</option>

                    <?php if (count($medium) > 0) {

                      foreach ($medium as $mrow) { ?>

                        <option value="<?php echo $mrow['medium_code'] ?>" <?php echo set_select('medium', $mrow['medium_code']); ?>>

                          <?php echo $mrow['medium_description'] ?>

                        </option>

                    <?php }
                    } ?>

                  </select>

                </div>

              </div>

              <div class="form-group">



                <label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>

                <div class="col-sm-2">

                  <select name="selCenterName" id="selCenterName" class="form-control" required onchange="valCentre(this.value);" style="width:250px">

                    <option value="">Select</option>

                    <?php if (count($center) > 0) {



                      foreach ($center as $crow) { ?>

                        <option value="<?php echo $crow['center_code'] ?>" class=<?php echo $crow['exammode']; ?>><?php echo $crow['center_name'] ?></option>

                    <?php }
                    } ?>

                  </select>

                </div>

              </div>



              <?php

              $this->db->where('exam_code', $examinfo['0']['exam_code']);

              $sql = $this->master_model->getRecords('exam_master', '', 'elearning_flag');

              if ($sql[0]['elearning_flag'] == 'Y') {

              ?>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label>

                  <div class="col-sm-3">
                  <div style="display:none;">
                    <input type="radio" name="elearning_flag" id="elearning_flag_Y" value="Y">
                    <input type="radio" name="elearning_flag" id="elearning_flag_N" value="N" checked="checked">
                  </div>
                    <input type="radio" name="elearning_flag" id="subject_elearning_flag_Y" value="Y">

                    YES

                    <input type="radio" name="elearning_flag" id="subject_elearning_flag_N" value="N" checked="checked">

                    NO
                  </div>

                </div>
                <?php foreach($compulsory_subjects as $el_subject){?>
                 
                   <div class="form-group show_el_subject" >
                    <label for="roleid" class="col-sm-3 control-label"><?php echo $el_subject['subject_description']?><span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                       	<input type="checkbox" name="el_subject[<?php echo $el_subject['subject_code']?>]" value="Y" checked="checked" class="el_sub_prop" />
                        </div>
                 </div>
                 <?php }
                 ?>

              <?php } else { ?>

                <input type="hidden" name="elearning_flag" id="elearning_flag_Y" value="N">

                <input type="hidden" name="elearning_flag" id="elearning_flag_N" value="N">

              <?php } ?>



              <?php

               

              if (
                count($compulsory_subjects) > 0 && $this->session->userdata('examcode') != 101

                && $this->session->userdata('examcode') != 1010

                && $this->session->userdata('examcode') != 10100

                && $this->session->userdata('examcode') != 101000

                && $this->session->userdata('examcode') != 1010000

                && $this->session->userdata('examcode') != 10100000

                && $this->session->userdata('examcode') != 996
              ) {

                $i = 1;

                ?>
             <div class="form-group" >
                <label for="roleid" class="col-sm-3 control-label"><strong>Eligible Subject</strong></label>
                <div class="col-sm-1">&nbsp;</div>
                <div class="col-sm-3"><strong>Exam Date</strong></div>
              </div>
             <?php
             
                foreach ($compulsory_subjects as $subject) { ?>

                <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams')))  { ?>
                            <div class="form-group" >
                        <label for="roleid" class="col-sm-3 control-label"><?php echo $subject['subject_description']?><span style="color:#F00">*</span></label>
                        <div class="col-sm-1">&nbsp;</div>
                        <div class="col-sm-3">
                                        
                                        <?php echo $subject['exam_date']?>
                                        </div>
                      </div>	
							  <?php } ?>
                  <div class="form-group" <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) echo'NOT IN WORK style="display:none;"'?>>

                    <label for="roleid" class="col-sm-3 control-label"><?php echo $subject['subject_description'] ?><span style="color:#F00">*</span></label>

                    <div class="col-sm-2">

                      <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>

                      <select name="venue[<?php echo $subject['subject_code'] ?>]" id="venue_<?php echo $i; ?>" class="form-control venue_cls" required onchange="venue(this.value,'date_<?php echo $i; ?>','time_<?php echo $i; ?>','<?php echo $subject['subject_code'] ?>','seat_capacity_<?php echo $i; ?>');" attr-data='<?php echo $subject['subject_code'] ?>'>

                        <option value="">Select</option>

                      </select>

                    </div>



                    <div class="col-sm-2">

                      <label for="roleid" class="col-sm-3 control-label">Date<span style="color:#F00">*</span></label>

                      <select name="date[<?php echo $subject['subject_code'] ?>]" id="date_<?php echo $i; ?>" class="form-control date_cls" required onchange="date(this.value,'venue_<?php echo $i; ?>','time_<?php echo $i; ?>');">

                        <option value="">Select</option>

                      </select>

                    </div>



                    <div class="col-sm-2">

                      <label for="roleid" class="col-sm-3 control-label">Time<span style="color:#F00">*</span></label>

                      <select name="time[<?php echo $subject['subject_code'] ?>]" id="time_<?php echo $i; ?>" class="form-control time_cls" required onchange="time(this.value,'venue_<?php echo $i; ?>','date_<?php echo $i; ?>','seat_capacity_<?php echo $i; ?>');">

                        <option value="">Select</option>

                      </select>

                    </div>





                    <label for="roleid" class="col-sm-0 control-label">Seat(s) Available<span style="color:#F00">*</span></label>

                    <div id="seat_capacity_<?php echo $i; ?>">

                      -

                    </div>

                  </div>





              <?php

                  $i++;
                }
              } ?>



              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Centre Code *</label>

                <div class="col-sm-2">

                  <input type="text" name="txtCenterCode" id="txtCenterCode" class="form-control pull-right" readonly="readonly">

                </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Exam Mode *</label>

                <!--<div class="col-sm-2">

                      <input type="radio" class="minimal " id="optsex1"   name="optmode" value="ON" <?php //echo set_radio('optmode', 'ON'); 
                                                                                                    ?>>

                     Online

                   <input type="radio" class="minimal" id="optsex2"   name="optmode"  checked="checked"  value="OF" <?php //echo set_radio('optmode', 'OF'); 
                                                                                                                    ?>>

                     Offline

                    </div>-->



                <div name="optmode1" id="optmode1" style="display: none;">Exam will be in ONLINE mode only, Read Important Instructions on the website.</div>

                <div name="optmode2" id="optmode2" style="display: none;">Exam will be in OFFLINE mode only, Read Important Instructions on the website.</div>

                <input id="optmode" name="optmode" value="" type="hidden">

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Scribe required?</label>

                <div class="col-sm-3">

                  <input type="checkbox" name="scribe_flag" id="scribe_flag" value="Y">

                </div>

                <div class="col-sm-12">

                  <img src="<?php echo base_url() ?>assets/images/bullet2.gif"> The candidate should send a separate application along with the DECLARATION as given in the Scribe Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.

                  <br />



                  <span class="error"><?php //echo form_error('gender');
                                      ?></span>

                </div>

              </div>





            </div>

          </div>





          <div class="box box-info">

            <div class="box-header with-border header_blue">

              <h3 class="box-title">Declaration:</h3>

            </div>

            <div class="box-body">

              <div class="form-group">

                <label for="roleid" class="col-sm-2 control-label"> </label>

                <div class="col-sm-12">

                  <ol>

                    <li> I declare that I have submitted my Aadhar Card Number and Proof of my Identity : Driving License/ID Card issued by Employer / Pan Card / Passport as specified above.. </li>

                    <!--<li>I hereby declare that all the information given in this application is true, complete and correct. I understand that in the event of any information being found false or incorrect subsequent to allotment of membership, my membership is liable to be cancelled / terminated.

        </li>-->



                    <li>I hereby declare that all the information given in this application is true, complete and correct. I understand that in the event of any information being found false or incorrect subsequent to allotment of registration No, my registration No is liable to be cancelled / terminated. </li>

                    <li> I further declare that I have not at any time been a member of the Institute/applied earlier for membership of the Institute. </li>

                    <!--<li> I hereby agree, if admitted, to be bound by the Memorandum and Articles of Association of the Institute. I am aware that, if admitted as an Ordinary Member, as per the provisions of the Articles of Association of the Institute. I shall be liable, in the event of the Institute begin wound up, to contribute towards its liabilities a sum not exceeding Rs. 1725/-

        </li>-->

                    <li> I confirm having read and understood the rules and regulations of the Institute and I hereby agree to abide by the same. In case I am desirous of Instituting any legal proceedings against the Institute I hereby agree that such legal proceedings shall be instituted only in courts at Mumbai, New Delhi, Kolkata and Chennai in whose Jurisdiction Zonal office/s of the Institute is situated and my application thereto pertains and not in any other court.</li>

                  </ol>

                </div>

              </div>

            </div>

          </div>

          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">

                <input name="declaration1" value="1" type="checkbox" required="required" <?php if (set_value('declaration1')) {

                                                                                            echo set_radio('declaration1', '1');
                                                                                          } ?>>

                &nbsp; I Accept
              </h3>

            </div>

            <!--<div class="box-body">

                                        <div class="form-group">

                                            <label for="roleid" class="col-sm-3 control-label">Security Code *</label>

                                            <div class="col-sm-2">

                                                <input type="text" name="code" id="code" required class="form-control">

                                                <span class="error" id="non_mem_captchaid" style="color:#B94A48;"></span> </div>

                                            <div class="col-sm-3">

                                                <div id="captcha_img">

                                                    <?php echo $image; ?>

                                                </div>

                                                <span class="error">

                <?php //echo form_error('code');
                ?>

                </span> </div>

                                            <div class="col-sm-2"> <a href="javascript:void(0);" id="new_captcha" class="forget">Change Image</a> <span class="error">

                <?php //echo form_error('code');
                ?>

                </span> </div>

                                        </div>

                                    </div>-->

            <div class="box-footer">

              <div class="col-sm-3 col-sm-offset-4"> <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return non_mem_checkform();" name="btnPreviewSubmit" id="btnPreviewSubmit">Preview and Submit</a>

                <button type="reset" class="btn btn-default pull-right" name="btnReset" id="btnReset">Reset</button>

                <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->



              </div>

            </div>

          </div>



          <div class="modal fade" id="confirm" role="dialog">

            <div class="modal-dialog" role="document">

              <div class="modal-content">

                <div class="modal-header">

                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                  <h4 class="modal-title"></h4>

                </div>

                <div class="modal-body">

                  <p style="color:#F00"> <strong>VERY IMPORTANT</strong>

                    <br> I confirm that the Photo, Signature & Id proof images uploaded belongs to me and they are clear and readable.

                    <br />

                    <br /> We find that Aadhaar Number is not mentioned in your membership account. You are requested to enter Aadhaar number in your membership account immediately. Aadhaar number can be updated to your existing membership account through edit profile option by entering your membership number and profile password.

                    <br /> In case, if you do not have Aadhaar number, request you to obtain it on or before 31st march 2018 . and update the Aadhaar number in your membership profile.
                  </p>

                </div>

                <div class="modal-footer">

                  <!-- <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="non_mem_preview();">Confirm</button>-->

                  <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm">

                </div>

              </div>

              <!-- /.modal-content -->

            </div>

            <!-- /.modal-dialog -->

          </div>



        </form>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">



  <div class="modal-dialog">



    <div class="modal-content">



      <div class="modal-header">



        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>



        <center><strong>
            <h4 class="modal-title" id="myModalLabel" style="color:#F00"><b>Important Notice</b></h4>
          </strong></center>



      </div>



      <div class="modal-body">
        <img src="<?php echo base_url() ?>assets/images/bullet2.gif">
        The candidate should send a scan copy of the DECLARATION as given in the Annexure-I duly completed and to email iibfwzmem@iibf.org.in. Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.<br /><br />
        <p style="color:#F00">Click here to download the declaration form <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_Rev.pdf" download target="_blank">Scribe_Guideliness_Rev.pdf</a></p>
      </div>
      <div class="modal-footer">



        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>







      </div>



    </div>



  </div>



</div>

<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>

<!--<script src="<?php //echo base_url();
                  ?>js/validation.js"></script>-->

<script type="text/javascript">
  <!--var flag=$('#usersAddForm').parsley('validate');
  -->

</script>

<script>
  $(document).ready(function() {

    $('#scribe_flag').on('change', function(e) {

      if (e.target.checked) {

        $('#myModal').modal();

      }

    });

  });
</script>

<script>
  $(document).ready(function() {

    var el_subject_cnt = 0; //$('.show_el_subject :input[type="checkbox"]:checked').length;
    var datastring_1 = 'subject_cnt=' + el_subject_cnt;
    //$('#elearning_flag_Y').prop('checked', true);
    $.ajax({
      url: site_url + 'bulk/BulkApplyDB/set_dbf_elsub_cnt/',
      data: datastring_1,
      type: 'POST',
      async: false,
      success: function(data) {}
    });

    $("#elearning_flag_Y").click(function() {

      var cCode = document.getElementById('txtCenterCode').value;

      var examType = document.getElementById('extype').value;

      var examCode = document.getElementById('examcode').value;

      var temp = document.getElementById("selCenterName").selectedIndex;

      var selected_month = document.getElementById("selCenterName").options[temp].className;

      var eprid = document.getElementById('eprid').value;

      var excd = document.getElementById('excd').value;

      var grp_code = document.getElementById('grp_code').value;

      var extype = document.getElementById('extype').value;

      var mtype = document.getElementById('mtype').value;

      var discount_flag = document.getElementById('discount_flag').value;

      var free_paid_flag = document.getElementById('free_paid_flag').value;

      if (document.getElementById('elearning_flag_Y').checked) {

        var Eval = document.getElementById('elearning_flag_Y').value;

      }



      if (document.getElementById('elearning_flag_N').checked) {

        var Eval = document.getElementById('elearning_flag_N').value;

      }

      var el_subject_cnt = $('.show_el_subject :input[type="checkbox"]:checked').length; //priyanka D >> DBFMOUPAYMENTCHANGE >> changed 4 to dynamic
      var datastring_1 = 'subject_cnt=' + el_subject_cnt;
      //$('#elearning_flag_Y').prop('checked', true);
      $.ajax({
        url: site_url + 'bulk/BulkApplyDB/set_dbf_elsub_cnt/',
        data: datastring_1,
        type: 'POST',
        async: false,
        success: function(data) {}
      });

      if (cCode != '') {

        var datastring = 'centerCode=' + cCode + '&eprid=' + eprid + '&excd=' + excd + '&grp_code=' + grp_code + '&mtype=' + mtype + '&elearning_flag=' + Eval + '&discount_flag=' + discount_flag + '&free_paid_flag=' + free_paid_flag;

        $.ajax({

          url: site_url + 'Bulk_fee/getFee/',

          data: datastring,

          type: 'POST',

          async: false,

          success: function(data) {

            if (data)

            {

              document.getElementById('fee').value = data;

              document.getElementById('html_fee_id').innerHTML = data;

              //response = true;

            }

          }

        });

      }

    });



    $("#elearning_flag_N").click(function() {

      var cCode = document.getElementById('txtCenterCode').value;

      var examType = document.getElementById('extype').value;

      var examCode = document.getElementById('examcode').value;

      var temp = document.getElementById("selCenterName").selectedIndex;

      var selected_month = document.getElementById("selCenterName").options[temp].className;

      var eprid = document.getElementById('eprid').value;

      var excd = document.getElementById('excd').value;

      var grp_code = document.getElementById('grp_code').value;

      var extype = document.getElementById('extype').value;

      var mtype = document.getElementById('mtype').value;

      var discount_flag = document.getElementById('discount_flag').value;

      var free_paid_flag = document.getElementById('free_paid_flag').value;

      if (document.getElementById('elearning_flag_Y').checked) {

        var Eval = document.getElementById('elearning_flag_Y').value;

      }



      if (document.getElementById('elearning_flag_N').checked) {

        var Eval = document.getElementById('elearning_flag_N').value;

      }

      var el_subject_cnt = 0; //$('.show_el_subject :input[type="checkbox"]:checked').length;
      var datastring_1 = 'subject_cnt=' + el_subject_cnt;
      //$('#elearning_flag_Y').prop('checked', true);
      $.ajax({
        url: site_url + 'bulk/BulkApplyDB/set_dbf_elsub_cnt/',
        data: datastring_1,
        type: 'POST',
        async: false,
        success: function(data) {}
      });

      if (cCode != '') {

        var datastring = 'centerCode=' + cCode + '&eprid=' + eprid + '&excd=' + excd + '&grp_code=' + grp_code + '&mtype=' + mtype + '&elearning_flag=' + Eval + '&discount_flag=' + discount_flag + '&free_paid_flag=' + free_paid_flag;

        $.ajax({

          url: site_url + 'Bulk_fee/getFee/',

          data: datastring,

          type: 'POST',

          async: false,

          success: function(data) {

            if (data)

            {

              document.getElementById('fee').value = data;

              document.getElementById('html_fee_id').innerHTML = data;

              //response = true;

            }

          }

        });

      }

    });

  })
</script>

<script>
  $(document).ready(function() {

    var cCode = $('#selCenterName').val();

    if (cCode != '') {

      document.getElementById('txtCenterCode').value = cCode;

      var examType = document.getElementById('extype').value;

      var examCode = document.getElementById('examcode').value;

      var temp = document.getElementById("selCenterName").selectedIndex;

      selected_month = document.getElementById("selCenterName").options[temp].className;

      if (selected_month == 'ON') {

        if (document.getElementById("optmode1")) {

          document.getElementById("optmode1").style.display = "block";

          document.getElementById('optmode').value = 'ON';

        }



        if (document.getElementById("optmode2")) {

          document.getElementById("optmode2").style.display = "none";

        }



      } else if (selected_month == 'OF') {

        if (document.getElementById("optmode2")) {

          document.getElementById("optmode2").style.display = "block";

          document.getElementById('optmode').value = 'OF';

        }

        if (document.getElementById("optmode1")) {

          document.getElementById("optmode1").style.display = "none";

        }

      } else {

        if (document.getElementById("optmode1")) {

          document.getElementById("optmode1").style.display = "none";

        }

        if (document.getElementById("optmode2")) {

          document.getElementById("optmode2").style.display = "none";

        }

      }



    }

    //var dtable = $('.dataTables-example').DataTable();



    //$(".DTTT_button_print")).hide();

    /*$('#datepicker,#doj').datepicker({

        autoclose: true

    });*/



    $(function() {

      $("#dob1").dateDropdowns({

        submitFieldName: 'dob1',

        minAge: 0,

        maxAge: 59

      });

      // Set all hidden fields to type text for the demo

      //$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');

    });



    $("#dob1").change(function() {

      var sel_dob = $("#dob1").val();

      if (sel_dob != '') {

        var dob_arr = sel_dob.split('-');

        if (dob_arr.length == 3) {

          chkage(dob_arr[2], dob_arr[1], dob_arr[0]);

        } else {

          alert('Select valid date');

        }

      }

    });



    $("body").on("contextmenu", function(e) {

      return false;

    });



    $('#male').prop("checked", true);



    /*$('#eduqual1').show();

    $('#UG').show();

    $('#eduqual').hide();

    $('#edu').hide();*/



    var selEducation = $("#education_type").val();

    if (selEducation != '') {

      changedu(selEducation);

    }







  });



  function editUser(id, roleid, Name, Username, Email) {

    $('#id').val(id);

    $('#roleid').val(roleid);

    $('#name').val(Name);

    $('#username').val(Username);

    $('#emailid').val(Email);

    $('#btnSubmit').val('Update');

    $('#roleid').focus();

    $('#password').removeAttr('required');

    $('#confirmPassword').removeAttr('required');



  }



  function changedu(dval) {



    $("#education_type").val(dval);

    var UGid = document.getElementById('UG');

    var GRid = document.getElementById('GR');

    var PGid = document.getElementById('PG');

    var EDUid = document.getElementById('edu');



    if (dval == 'U') {

      $('#eduqual1').attr('required', 'required');

      $('#eduqual2').removeAttr('required');

      $('#eduqual3').removeAttr('required');

      $('#eduqual').removeAttr('required');



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

    }

  }
</script>

<script>
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



    $('#new_captcha').click(function(event) {

      event.preventDefault();

      $.ajax({

        type: 'POST',

        url: site_url + 'bulk/BulkApplyDB/generatecaptchaajax/',

        success: function(res) {

          if (res != '') {

            $('#captcha_img').html(res);

          }

        }

      });

    });

    //$("#datepicker,#doj").keypress(function(event) {event.preventDefault();});



    if ($('#hiddenphoto').val() != '') {

      $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());

    }

    if ($('#hiddenscansignature').val() != '') {

      $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());

    }

    if ($('#hiddenidproofphoto').val() != '') {

      $('#image_upload_idproof_preview').attr('src', $('#hiddenidproofphoto').val());

    }



  });

//Priyanka D >> DBFMOUPAYMENTCHANGE >> 10-july
  //priyanka d - 08-feb-23 >> changeFeeFromElarningY >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated
	if($("#subject_elearning_flag_Y").length > 0 && $('#subject_elearning_flag_Y').is(':checked') && getCookie('sotredPreivousValues')!=1) {
		changeFeeFromElarningY();
	}
	$("#subject_elearning_flag_Y").click(function(){
		changeFeeFromElarningY();
	});
	function changeFeeFromElarningY(){
		$(".loading").show();
		$(".show_el_subject").show();
		$(".el_sub_prop").prop('checked', true);
		
		var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
		var datastring_1='subject_cnt='+el_subject_cnt;
		
		$.ajax({
				url:site_url+'bulk/BulkApplyDB/set_dbf_elsub_cnt/',
				data: datastring_1,
				type:'POST',
				async: false,
				success: function(data) {
				}
			});
		
		
		var cCode =  document.getElementById('txtCenterCode').value;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var selected_month = document.getElementById("selCenterName").options[temp].className;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		var grp_code = document.getElementById('grp_code').value;
		var extype= document.getElementById('extype').value;
		var mtype= document.getElementById('mtype').value;

    var discount_flag = document.getElementById('discount_flag').value;

    var free_paid_flag = document.getElementById('free_paid_flag').value;
		var Eval = 'N'; 
		
		if(document.getElementById('elearning_flag_Y').checked){
			var Eval = document.getElementById('elearning_flag_Y').value;
		}

		if(document.getElementById('elearning_flag_N').checked){
			var Eval = document.getElementById('elearning_flag_N').value;
		}
		
		//var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;;
		
		var datastring = 'centerCode=' + cCode + '&eprid=' + eprid + '&excd=' + excd + '&grp_code=' + grp_code + '&mtype=' + mtype + '&elearning_flag=' + Eval + '&discount_flag=' + discount_flag + '&free_paid_flag=' + free_paid_flag;

		$.ajax({
				url:site_url+'Bulk_fee/getFee/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				if(data){
					document.getElementById('fee').value = data ;
					document.getElementById('html_fee_id').innerHTML =data;
				}
			}
		});
		
		$(".loading").hide();
	}
	//priyanka d - 08-feb-23 >> changeFeeFromElarningN >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated
	if($("#subject_elearning_flag_N").length > 0 && $('#subject_elearning_flag_N').is(':checked') && getCookie('sotredPreivousValues')!=1) {
		changeFeeFromElarningN();
	}
	$("#subject_elearning_flag_N").click(function(){
		changeFeeFromElarningN();
	});
	function changeFeeFromElarningN(){ 
		$(".loading").show();
		$(".show_el_subject").hide();
		$(".el_sub_prop").prop('checked', false);
		
		var el_subject_cnt = 0;
		
		var datastring_1='subject_cnt='+el_subject_cnt;
		
		$.ajax({
				url:site_url+'bulk/BulkApplyDB/set_dbf_elsub_cnt/',
				data: datastring_1,
				type:'POST',
				async: false,
				success: function(data) {
				}
			});
		
		
		var cCode =  document.getElementById('txtCenterCode').value;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var selected_month = document.getElementById("selCenterName").options[temp].className;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		var grp_code = document.getElementById('grp_code').value;
		var extype= document.getElementById('extype').value;
		var mtype= document.getElementById('mtype').value;

    var discount_flag = document.getElementById('discount_flag').value;

    var free_paid_flag = document.getElementById('free_paid_flag').value;

		var Eval = 'N'; 
		
		if(document.getElementById('elearning_flag_Y').checked){
			var Eval = document.getElementById('elearning_flag_Y').value;
		}

		if(document.getElementById('elearning_flag_N').checked){
			var Eval = document.getElementById('elearning_flag_N').value;
		}
		
		//var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;;
		var datastring = 'centerCode=' + cCode + '&eprid=' + eprid + '&excd=' + excd + '&grp_code=' + grp_code + '&mtype=' + mtype + '&elearning_flag=' + Eval + '&discount_flag=' + discount_flag + '&free_paid_flag=' + free_paid_flag;
		

		$.ajax({
				url:site_url+'Bulk_fee/getFee/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				if(data){
					document.getElementById('fee').value = data ;
					document.getElementById('html_fee_id').innerHTML =data;
				}
			}
		});
		$(".loading").hide();
	}
	
function setCookie(cname, cvalue, exdays) {
	const d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	let expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

function getCookie(cname) {
	let name = cname + "=";
	let ca = document.cookie.split(';');
	for(let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
		c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
		return c.substring(name.length, c.length);
		}
	}
	return "";
	}
  //priyanka d - 08-feb-23 >> el_sub_prop >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated
	if(getCookie('sotredPreivousValues')!=1)
		el_sub_prop();

	$(".el_sub_prop").click(function(){
		el_sub_prop();
	});

	function el_sub_prop() {
		$(".loading").show();
		var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
		var datastring_1='subject_cnt='+el_subject_cnt;
		
		$.ajax({
				url:site_url+'bulk/BulkApplyDB/set_dbf_elsub_cnt/',
				data: datastring_1,
				type:'POST',
				async: false,
				success: function(data) {
				}
			});
		
		
		var cCode =  document.getElementById('txtCenterCode').value;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var selected_month = document.getElementById("selCenterName").options[temp].className;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		var grp_code = document.getElementById('grp_code').value;
		var extype= document.getElementById('extype').value;
		var mtype= document.getElementById('mtype').value;

    var discount_flag = document.getElementById('discount_flag').value;

    var free_paid_flag = document.getElementById('free_paid_flag').value;
    
		var Eval = 'N'; 
		
		if(document.getElementById('elearning_flag_Y').checked){
			var Eval = document.getElementById('elearning_flag_Y').value;
		}

		if(document.getElementById('elearning_flag_N').checked){
			var Eval = document.getElementById('elearning_flag_N').value;
		}
		
		//var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;;
		var datastring = 'centerCode=' + cCode + '&eprid=' + eprid + '&excd=' + excd + '&grp_code=' + grp_code + '&mtype=' + mtype + '&elearning_flag=' + Eval + '&discount_flag=' + discount_flag + '&free_paid_flag=' + free_paid_flag;
		

		$.ajax({
				url:site_url+'Bulk_fee/getFee/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				if(data){
					document.getElementById('fee').value = data ;
					document.getElementById('html_fee_id').innerHTML =data;
				}
			}
		});
		$(".loading").hide();
	}

  //priyanka D >> end DBFMOUPAYMENTCHANGE >>10-july
</script>