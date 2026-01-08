<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/sidebar'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Ordinary Membership Registration Admin Edit Page
    </h1>
    <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();
                      ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());
                                          ?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
  </section>
  <form class="form-horizontal" name="usersAddForm" id="usersEditForm" method="post" enctype="multipart/form-data">
    <input type="hidden" name="regid" id="regid" value="<?php echo $regData['regid']; ?>">
    <input type="hidden" name="regtype" id="regtype" value="<?php echo $regData['registrationtype']; ?>" />
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <?php //echo validation_errors(); 
              ?>
              <?php if ($this->session->flashdata('error') != '') { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                  <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php }
              if ($this->session->flashdata('success') != '') { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                  <?php echo $this->session->flashdata('success'); ?>
                </div>
              <?php }
              if (validation_errors() != '') { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                  <?php echo validation_errors(); ?>
                </div>
              <?php }
              ?>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name *</label>
                <div class="col-sm-2">
                  <?php //echo $regData['namesub'];
                  ?>
                  <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                    <option value="MR." <?php if ($regData['namesub'] == 'MR.') {
                                          echo "selected='selected'";
                                        } ?>>MR.</option>
                    <option value="MRS." <?php if ($regData['namesub'] == 'MRS.') {
                                            echo "selected='selected'";
                                          } ?>>MRS.</option>
                    <option value="MS." <?php if ($regData['namesub'] == 'MS.') {
                                          echo "selected='selected'";
                                        } ?>>MS.</option>
                    <option value="DR." <?php if ($regData['namesub'] == 'DR.') {
                                          echo "selected='selected'";
                                        } ?>>DR.</option>
                    <option value="PROF." <?php if ($regData['namesub'] == 'PROF.') {
                                            echo "selected='selected'";
                                          } ?>>PROF.</option>
                  </select>
                  <input type="hidden" name="sel_namesub_hidd" id="sel_namesub_hidd" value="<?php echo $regData['namesub']; ?>" />
                  <input type="hidden" name="excode" id="excode" value="<?php echo $regData['excode']; ?>" />
                </div>

                <div class="col-sm-3">
                  <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo $regData['firstname']; ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()">
                  <input type="hidden" name="firstname_hidd" id="firstname_hidd" value="<?php echo $regData['firstname']; ?>" />
                </div>

              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" value="<?php echo $regData['middlename']; ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()">
                  <input type="hidden" name="middlename_hidd" id="middlename_hidd" value="<?php echo $regData['middlename']; ?>" />
                </div><!--(Max 30 Characters) -->
              </div>


              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $regData['lastname']; ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()">
                  <input type="hidden" name="lastname_hidd" id="lastname_hidd" value="<?php echo $regData['lastname']; ?>" />
                </div><!--(Max 30 Characters) -->
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Full Name</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="nameoncard" name="nameoncard" placeholder="Full Name" value="<?php if (!isset($regData['nameoncard']) || (isset($regData['nameoncard']) && $regData['nameoncard'] == "")) {
                                                                                                                              echo $regData['firstname'];
                                                                                                                              if ($regData['middlename'] != "") {
                                                                                                                                echo " " . $regData['middlename'];
                                                                                                                              }
                                                                                                                              if ($regData['lastname'] != "") {
                                                                                                                                echo " " . $regData['lastname'];
                                                                                                                              }
                                                                                                                            } else {
                                                                                                                              echo $regData['nameoncard'];
                                                                                                                            } ?>" readonly disabled>
                </div><!--(Max 30 Characters) -->
              </div>

            </div>

          </div> <!-- Basic Details box closed-->

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>
            </div>
            <h4>Office/Residential Address for communication (Pl do not repeat the name of the Applicant, Only Address to be typed)</h4>
            <!--<div class="box-header with-border">
              <h6 class="box-title">Office/Residential Address for communication (Start with Bank Name if office address is given)</h6>
            </div>-->


            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1 *</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" value="<?php echo $regData['address1']; ?>" data-parsley-maxlength="30" required>
                  <input type="hidden" name="addressline1_hidd" id="addressline1_hidd" value="<?php echo $regData['address1']; ?>" />
                </div>
                (Max 30 Characters)


              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2" value="<?php echo $regData['address2']; ?>" data-parsley-maxlength="30">
                  <input type="hidden" name="" id="addressline2_hidd" value="<?php echo $regData['address2']; ?>">
                  <span class="error"><?php //echo form_error('addressline2');
                                      ?></span>
                </div>
                (Max 30 Characters)


              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3" value="<?php echo $regData['address3']; ?>" data-parsley-maxlength="30">
                  <input type="hidden" name="" id="addressline3_hidd" value="<?php echo $regData['address3']; ?>">
                  <span class="error"><?php //echo form_error('addressline3');
                                      ?></span>
                </div>
                (Max 30 Characters)


              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4" value="<?php echo $regData['address4']; ?>" data-parsley-maxlength="30">
                  <input type="hidden" name="" id="addressline4_hidd" value="<?php echo $regData['address4']; ?>">
                  <span class="error"><?php //echo form_error('addressline4');
                                      ?></span>
                </div>
                (Max 30 Characters)


              </div>


              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District *</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo $regData['district']; ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30">
                  <input type="hidden" name="" id="district_hidd" value="<?php echo $regData['district']; ?>">
                  <span class="error"><?php //echo form_error('district');
                                      ?></span>
                </div>
                (Max 30 Characters)
              </div>


              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City *</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $regData['city']; ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z- ]+$/" data-parsley-maxlength="30">
                  <input type="hidden" name="" id="city_hidd" value="<?php echo $regData['city']; ?>">
                  <span class="error"><?php //echo form_error('city');
                                      ?></span>
                </div>
                (Max 30 Characters)
              </div>


              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State *</label>
                <div class="col-sm-2">
                  <select class="form-control" id="state" name="state">
                    <option value="">Select</option>
                    <?php if (count($states) > 0) {
                      foreach ($states as $row1) {   ?>
                        <option value="<?php echo $row1['state_code']; ?>"
                          <?php if ($regData['state'] == $row1['state_code']) {
                            echo  'selected="selected"';
                          } ?>><?php echo $row1['state_name']; ?></option>
                    <?php }
                    } ?>
                  </select>
                  <input type="hidden" name="" id="state_hidd" value="<?php echo $regData['state']; ?>">


                </div>(Max 6 digits)
                <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode *</label>

                <div class="col-sm-2">
                  <input class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo $regData['pincode']; ?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-admincheckpin data-parsley-type="number" autocomplete="off" type="text" data-parsley-trigger-after-failure="focusout">


                  <input type="hidden" name="" id="pincode_hidd" value="<?php echo $regData['pincode']; ?>">
                  <span class="error"><?php //echo form_error('pincode');
                                      ?></span>
                </div>

              </div>

              <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>
                	<div class="col-sm-2">
                      <input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo $regData['dateofbirth']; ?>">
                       <input type="hidden" name="" id="dob_hidd" value="<?php echo $regData['dateofbirth']; ?>">
                      <span class="error"><?php //echo form_error('dob');
                                          ?></span>
                    </div>
                </div>-->

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>
                <div class="col-sm-5 example">
                  <?php if ($regData['dateofbirth'] != "0000-00-00" && $regData['dateofbirth'] != "") { ?>
                    <input type="hidden" id="dob1" name="dob" required value="<?php echo date('Y-m-d', strtotime($regData['dateofbirth'])); ?>">
                    <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value="<?php echo date('Y-m-d', strtotime($regData['dateofbirth'])); ?>" />
                  <?php } else { ?>
                    <input type="hidden" id="dob1" name="dob" required value="">
                    <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value="" />
                  <?php } ?>
                  <?php
                  $min_year = date('Y', strtotime("- 18 year"));
                  $max_year = date('Y', strtotime("- 80 year"));
                  ?>
                  <input type="hidden" id="doj1" name="doj1" required value="">
                  <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                  <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                  <span id="dob_error" class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender *</label>
                <div class="col-sm-2">
                  <input type="radio" class="minimal cls_gender" id="female" name="gender" required value="female"
                    <?php if ($regData['gender'] == 'female') {
                      echo  'checked="checked"';
                    } ?>>
                  Female
                  <input type="radio" class="minimal cls_gender" id="male" name="gender" required value="male" <?php if ($regData['gender'] == 'male') {
                                                                                                                  echo  'checked="checked"';
                                                                                                                } ?>>
                  Male
                  <span class="error"><?php //echo form_error('gender');
                                      ?></span>
                </div>
              </div>
              <input type="hidden" name="" id="gender_hidd" value="<?php echo $regData['gender']; ?>">

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification *</label>
                <div class="col-sm-5">
                  <input type="radio" class="minimal" id="U" name="optedu" value="U" <?php if ($regData['qualification'] == 'U') {
                                                                                        echo  'checked="checked"';
                                                                                      } ?> onclick="changedu(this.value)" <?php echo set_radio('optedu', 'U'); ?>>
                  Under Graduate
                  <input type="radio" class="minimal" id="G" name="optedu" value="G" <?php if ($regData['qualification'] == 'G') {
                                                                                        echo  'checked="checked"';
                                                                                      } ?>
                    onclick="changedu(this.value)" <?php echo set_radio('optedu', 'G'); ?>>
                  Graduate
                  <input type="radio" class="minimal" id="P" name="optedu" value="P" <?php if ($regData['qualification'] == 'P') {
                                                                                        echo  'checked="checked"';
                                                                                      } ?>
                    onclick="changedu(this.value)" <?php echo set_radio('optedu', 'P'); ?>>
                  Post Graduate
                  <input type="hidden" name="optedu_hidd" id="optedu_hidd" value="<?php echo $regData['qualification']; ?>" />
                </div>
              </div>
              <input type="hidden" name="" id="optedu_hidd" value="<?php echo $regData['qualification']; ?>">

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify *</label>
                <div class="col-sm-5" <?php if ($regData['specify_qualification'] != '') {
                                        echo 'style="display:none"';
                                      } else {
                                        echo 'style="display:block"';
                                      } ?> id="edu">
                  <select id="eduqual" name="eduqual" class="form-control" <?php if ($regData['qualification'] == '') {
                                                                              echo 'required';
                                                                            } ?>>
                    <option value="" selected="selected">--Select--</option>
                  </select>
                </div>


                <div class="col-sm-5" <?php if ($regData['qualification'] == 'U') {
                                        echo 'style="display:block"';
                                      } else {
                                        echo 'style="display:none"';
                                      } ?> id="UG">
                  <select class="form-control" id="eduqual1" name="eduqual1" <?php if ($regData['qualification'] == 'U') {
                                                                                echo 'required';
                                                                              } ?>>
                    <option value="">--Select--</option>
                    <?php if (count($undergraduate)) {
                      foreach ($undergraduate as $row1) {   ?>
                        <option value="<?php echo $row1['qid']; ?>" <?php if ($regData['specify_qualification'] == $row1['qid']) {
                                                                      echo  'selected="selected"';
                                                                    } ?>><?php echo $row1['name']; ?></option>
                    <?php }
                    } ?>
                  </select>
                  <input type="hidden" name="eduqual1_hidd" id="eduqual1_hidd" value="" />
                </div>

                <div class="col-sm-5" <?php if ($regData['qualification'] == 'G') {
                                        echo 'style="display:block"';
                                      } else {
                                        echo 'style="display:none"';
                                      } ?> id="GR">
                  <select class="form-control" id="eduqual2" name="eduqual2" <?php if ($regData['qualification'] == 'G') {
                                                                                echo 'required';
                                                                              } ?>>
                    <option value="">--Select--</option>
                    <?php if (count($graduate)) {
                      foreach ($graduate as $row2) {   ?>
                        <option value="<?php echo $row2['qid']; ?>" <?php if ($regData['specify_qualification'] == $row2['qid']) {
                                                                      echo  'selected="selected"';
                                                                    } ?>><?php echo $row2['name']; ?></option>
                    <?php }
                    } ?>
                  </select>
                  <input type="hidden" name="eduqual2_hidd" id="eduqual2_hidd" value="" />
                </div>



                <div class="col-sm-5" <?php if ($regData['qualification'] == 'P') {
                                        echo 'style="display:block"';
                                      } else {
                                        echo 'style="display:none"';
                                      } ?>id="PG">
                  <select class="form-control" id="eduqual3" name="eduqual3" <?php if ($regData['qualification'] == 'P') {
                                                                                echo 'required';
                                                                              } ?>>
                    <option value="">--Select--</option>
                    <?php if (count($postgraduate)) {
                      foreach ($postgraduate as $row3) {   ?>
                        <option value="<?php echo $row3['qid']; ?>" <?php if ($regData['specify_qualification'] == $row3['qid']) {
                                                                      echo  'selected="selected"';
                                                                    } ?>><?php echo $row3['name']; ?></option>
                    <?php }
                    } ?>
                  </select>

                  <input type="hidden" name="eduqual3_hidd" id="eduqual3_hidd" value="" />
                </div>
              </div>
              <input type="hidden" name="" id="qualification_hidd" value="<?php echo $regData['specify_qualification']; ?>">

              <!-- Start: OLD BCBF Extra fields added by Anil on 30 Sep 2024 -->
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name of Bank where working as BC</label>
                <div class="col-sm-5">
                  <select id="name_of_bank_bc" onchange="check_bank_bc_id_no();" name="name_of_bank_bc" class="form-control">
                    <option value="">-- Select --</option>
                    <?php if (count($old_bcbf_institute_data)) {
                      foreach ($old_bcbf_institute_data as $res) {   ?>
                        <option <?php echo ($res['institute_id'] == $regData['name_of_bank_bc'] ? 'selected' : ''); ?> value="<?php echo $res['institute_id']; ?>" <?php echo set_select('name_of_bank_bc', $res['institute_id']); ?>> <?php echo $res['institute_name']; ?>
                        </option>
                    <?php }
                    }
                    ?>
                  </select>
                  <input type="hidden" name="" id="name_of_bank_bc_hidd" value="<?php echo $regData['name_of_bank_bc']; ?>">
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of commencement of operations/joining as BC <span style="color:#F00">*</span></label>
                <!-- <input type="hidden" id="dob1" name="dob" required value="<?php //echo $user_info[0]['dateofbirth']; 
                                                                                ?>"> -->
                <div class="col-sm-4 doj">
                  <div class="col-sm-2 example" style="width: 100%;padding-left: 0px;">

                    <!-- <input type="hidden" id="doj1" name="date_of_commenc_bc" value="<?php echo $regData['date_of_commenc_bc']; ?>"> -->
                    <?php if ($regData['date_of_commenc_bc'] != "0000-00-00" && $regData['date_of_commenc_bc'] != "") { ?>
                      <input type="hidden" id="doj12" name="date_of_commenc_bc" required value="<?php echo date('Y-m-d', strtotime($regData['date_of_commenc_bc'])); ?>">
                      <input type="hidden" name="doj12_hidd" id="doj12_hidd" value="<?php echo date('Y-m-d', strtotime($regData['date_of_commenc_bc'])); ?>" />
                    <?php } else { ?>
                      <input type="hidden" id="doj12" name="date_of_commenc_bc" value="">
                      <input type="hidden" name="doj12_hidd" id="doj12_hidd" value="" />
                    <?php } ?>
                  </div>
                  <span id="doj_error" class="error"></span>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Bank BC ID No <span style="color:#F00"><?php echo ($regData['ippb_emp_id'] != "" ? '*' : ''); ?> </span></label>
                <div class="col-sm-5">
                  <input <?php //echo ( $regData['ippb_emp_id'] != "" ? 'required':''); 
                          ?> type="text" class="form-control" id="ippb_emp_id" name="ippb_emp_id" placeholder="Bank BC ID No" onchange="check_bank_bc_id_no();" value="<?php echo $regData['ippb_emp_id']; ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                  <input type="hidden" name="" id="ippb_emp_id_hidd" value="<?php echo $regData['ippb_emp_id']; ?>">
                  <span id="ippb_emp_id_error" class="error"></span>
                </div>
              </div>
              <?php if ($regData['bank_bc_id_card'] != "") { ?>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Bank BC ID Card</label>
                  <div class="col-sm-5 ">
                    <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo base_url('uploads/empidproof/' . $regData['bank_bc_id_card'] . '?' . time()); ?>" height="100" width="100"></label>
                    <div id="error_dob"></div>
                  </div>
                </div>
              <?php } ?>
              <!-- End: OLD BCBF Extra fields added by Anil on 30 Sep 2024 -->

              <?php //if(in_array($regData['excode'], array(1009))){ 
              ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00"><?php echo ($regData['excode'] == 1009 ? '*' : ''); ?></span></label>
                <div class="col-sm-5">
                  <input <?php //echo ($regData['excode'] == 1009 ? 'required' : ''); 
                          ?> type="text" class="form-control" id="bank_emp_id" name="bank_emp_id" placeholder="Bank Employee Id" value="<?php echo $regData['bank_emp_id']; ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                  <span class="error"></span>
                </div>
              </div>
              <?php //} 
              ?>


              <div class="form-group"> <!--priyanka d disabled forippb_emp_id >>12-july-23 -->
                <label for="roleid" class="col-sm-3 control-label">Email *</label>
                <div class="col-sm-5">
                  <input <?php if ($regData['excode'] != '997') echo ' disabled="disabled"'; ?> class="form-control" id="email" name="email" placeholder="Email" required data-parsley-type="email" value="<?php echo $regData['email']; ?>" data-parsley-maxlength="45" data-parsley-editemailcheckadmin autocomplete="off" type="text">


                  <input type="hidden" name="" id="email_hidd" value="<?php echo $regData['email']; ?>">
                  (Enter valid and correct email ID to receive communication)
                  <span class="error"><?php //echo form_error('email');
                                      ?></span>
                </div>
              </div>


              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone </label>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-6 control-label labelleft">STD Code</label>
                  <input type="text" class="form-control w50" id="stdcode" name="stdcode" placeholder="STD Code" value="<?php echo $regData['stdcode']; ?>">
                  <input type="hidden" name="" id="stdcode_hidd" value="<?php echo $regData['stdcode']; ?>">
                  <span class="error"><?php //echo form_error('stdcode');
                                      ?></span>
                </div>
                <div class="col-sm-3">
                  <label for="roleid" class="col-sm-6 control-label labelleft">Phone No</label>
                  <input type="text" class="form-control w50" id="phone" name="phone" placeholder="Phone No" data-parsley-minlength="7"
                    data-parsley-type="number" data-parsley-maxlength="12" value="<?php echo $regData['office_phone']; ?>" data-parsley-trigger-after-failure="focusout">
                  <input type="hidden" name="" id="phone_hidd" value="<?php echo $regData['office_phone']; ?>">
                  <span class="error"><?php //echo form_error('phone');
                                      ?></span>
                </div>
              </div>



              <div class="form-group"> <!--priyanka d disabled forippb_emp_id >>12-july-23 -->
                <label for="roleid" class="col-sm-3 control-label">Mobile *</label>
                <div class="col-sm-5">
                  <input <?php if ($regData['excode'] != '997') echo ' disabled="disabled"'; ?> type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number" required data-parsley-minlength="10" data-parsley-maxlength="12" value="<?php echo $regData['mobile']; ?>" data-parsley-editmobilecheckadmin data-parsley-trigger-after-failure="focusout">
                  <input type="hidden" name="" id="mobile_hidd" value="<?php echo $regData['mobile']; ?>">
                  <span class="error"><?php //echo form_error('mobile');
                                      ?></span>
                </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number </label>
                <div class="col-sm-5">
                  <input type="text" class="form-control pull-right" id="aadhar_card" name="aadhar_card" placeholder="Aadhar Card Number" maxlength="12" data-parsley-maxlength="12" value="<?php echo $regData['aadhar_card']; ?>">
                  <input type="hidden" name="aadhar_card_hidd" id="aadhar_card_hidd" value="<?php echo $regData['aadhar_card']; ?>" />
                </div>
              </div>


              <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo base_url(); ?><?php echo get_img_name($regData['regnumber'], 'p'); ?><?php echo '?' . time(); ?>" height="100" width="100"></label>
                <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo base_url(); ?><?php echo get_img_name($regData['regnumber'], 's'); ?><?php echo '?' . time(); ?>" height="100" width="100"></label>
                <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo base_url(); ?><?php echo get_img_name($regData['regnumber'], 'pr'); ?><?php echo '?' . time(); ?>" height="100" width="100"></label>
                <!-- START: Added for fedai Employment Proof & Declaration Form by Anil S 2024-10-09-->
                <?php
                $this->db->select('mem_photo,mem_sign,mem_proof,employee_proof,mem_declaration');
                $kyc_info = $this->master_model->getRecords('member_kyc', array('regnumber' => $regData['regnumber']));
                ?>
                <?php if (in_array($regData['excode'], array(1009))) { ?>
                  <label for="roleid" class="col-sm-2 text-center">
                    <?php
                    if (is_file(get_img_name($regData['regnumber'], 'empr'))) { ?>
                      <img src="<?php echo base_url(); ?><?php echo get_img_name($regData['regnumber'], 'empr'); ?><?php echo '?' . time(); ?>" height="100" width="100">
                      <?php
                    } else if (count($kyc_info) > 0) {
                      if ($kyc_info[0]['employee_proof'] == 1) { ?>
                        <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                      <?php
                      } else { ?>
                        <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                      <?php
                      }
                    } else { ?>
                      <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                    <?php
                    } ?>
                  </label>

                  <label for="roleid" class="col-sm-2 text-center">
                    <?php
                    if (is_file(get_img_name($regData['regnumber'], 'declaration'))) { ?>
                      <img src="<?php echo base_url(); ?><?php echo get_img_name($regData['regnumber'], 'declaration'); ?><?php echo '?' . time(); ?>" height="100" width="100">
                      <?php
                    } else if (count($kyc_info) > 0) {
                      if ($kyc_info[0]['mem_declaration'] == 1) { ?>
                        <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                      <?php
                      } else { ?>
                        <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                      <?php
                      }
                    } else { ?>
                      <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                    <?php
                    } ?>
                  </label>

                <?php } ?>
                <!-- END: Added for fedai Employment Proof & Declaration Form by Anil S 2024-10-09-->
              </div>
              
              <?php /* START : DELETE BUTTON FOR IMAGE RESET FUNCTIONALITY CODE START POOJA MANE 27-01-2025*/ ?>
              <div class="form-group">
                <div><label for="roleid" class="col-sm-2 control-label">Uploaded Photo<br>
                    <label class="control-label"><a style="text-align: right;" href="<?php echo base_url(); ?>admin/Report/deleteimages/<?php echo base64_encode($regData['regid']); ?>/<?php echo base64_encode($regData['regnumber']); ?>/<?php echo "scannedphoto" ?>">Delete Photograph </a></label></label>
                </div>
                <div><label for="roleid" class="col-sm-2 control-label">Uploaded Signature<br>
                    <label class="control-label"><a style="text-align: right;" href="<?php echo base_url(); ?>admin/Report/deleteimages/<?php echo base64_encode($regData['regid']); ?>/<?php echo base64_encode($regData['regnumber']); ?>/<?php echo "scannedsignaturephoto" ?>">Delete Signature </a></label></label>
                </div>
                <div><label for="roleid" class="col-sm-2 control-label">Uploaded ID Proof<br>
                    <label class="control-label"><a style="text-align: right;" href="<?php echo base_url(); ?>admin/Report/deleteimages/<?php echo base64_encode($regData['regid']); ?>/<?php echo base64_encode($regData['regnumber']); ?>/<?php echo "idproofphoto" ?>">Delete ID Proof </a></label></label>
                </div>
                <!-- START: Added for fedai Employment Proof & Declaration Form by Anil S 2024-10-09-->
                <?php if (in_array($regData['excode'], array(1009))) { ?>
                  <div><label for="roleid" class="col-sm-2 control-label">Uploaded Employment Proof<br>
                      <label class="control-label"><a style="text-align: right;" href="<?php echo base_url(); ?>admin/Report/deleteimages/<?php echo base64_encode($regData['regid']); ?>/<?php echo base64_encode($regData['regnumber']); ?>/<?php echo "empidproofphoto" ?>">Delete Employment Proof </a></label></label>
                  </div>
                  <div><label for="roleid" class="col-sm-2 control-label">Uploaded Declaration Form<br>
                      <label class="control-label"><a style="text-align: right;" href="<?php echo base_url(); ?>admin/Report/deleteimages/<?php echo base64_encode($regData['regid']); ?>/<?php echo base64_encode($regData['regnumber']); ?>/<?php echo "declaration" ?>">Delete Declaration Form </a></label></label>
                  </div>
                <?php } ?>
                <!-- END: Added for fedai Employment Proof & Declaration Form by Anil S 2024-10-09-->
                <?php
                //fedai Employment Proof & Declaration Form by Anil S 2024-10-09
                if (in_array($regData['excode'], array(1009))) {
                  //if (!is_file(get_img_name($regData['regnumber'], 'declaration')) ||!is_file(get_img_name($regData['regnumber'], 'empr')) || !is_file(get_img_name($regData['regnumber'], 'pr')) || !is_file(get_img_name($regData['regnumber'], 's')) || !is_file(get_img_name($regData['regnumber'], 'p'))) 
                  { ?>
                    <label for="roleid" class="col-sm-2 text-center"><a class="btn btn-warning " href="<?php echo base_url(); ?>admin/Report/editimages/<?php echo base64_encode($regData['regid']); ?>/<?php echo base64_encode($regData['regnumber']); ?>">Edit Images</a></label>
                  <?php
                  }
                } else {
                  ?>
                  <label for="roleid" class="col-sm-2 text-center"><a class="btn btn-warning " href="<?php echo base_url(); ?>admin/Report/editimages/<?php echo base64_encode($regData['regid']); ?>/<?php echo base64_encode($regData['regnumber']); ?>">Edit Images</a></label>
                <?php
                }
                ?>

                <label for="roleid" class="col-sm-2 text-center"><a class="btn btn-warning " href="<?php echo base_url();?>admin/Report/deleteimages/<?php echo base64_encode($regData['regid']);?>/<?php echo base64_encode($regData['regnumber']);?>/<?php echo "all"?>">Delete all Images</a></label>

              </div>
              <?php /* END : DELETE BUTTON FOR IMAGE RESET FUNCTIONALITY CODE START POOJA MANE 27-01-2025*/ ?>

              <div class="form-group">

              </div>



              <!-- <div class="form-group">
                     <label for="roleid" class="col-sm-3 control-label">Select Id Proof *</label>
                  	  <div class="col-sm-5">
                              <?php if (count($idtype_master)) {
                                foreach ($idtype_master as $idrow) {   ?>
                              <input name="idproof" value="<?php echo $idrow['id']; ?>" type="radio" class="minimal"  <?php if ($regData['idproof'] == $idrow['id']) {
                                                                                                                        echo  'checked="checked"';
                                                                                                                      } ?>><?php echo $idrow['name']; ?><br>
                              <?php }
                              } ?>
                    <span class="error"><?php //echo form_error('idproof');
                                        ?></span>
                  </div>
              </div>-->

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Select Id Proof *</label>
                <div class="col-sm-5">
                  <input name="idproof" value="1" type="radio" class="minimal" <?php if ($regData['idproof'] == '1') {
                                                                                  echo "checked='checked'";
                                                                                } ?>>Aadhaar id<br>

                  <input name="idproof" value="3" type="radio" class="minimal" <?php if ($regData['idproof'] == '3') {
                                                                                  echo "checked='checked'";
                                                                                } ?>>Election Voters card<br>
                  <input name="idproof" value="2" type="radio" class="minimal" <?php if ($regData['idproof'] == '2') {
                                                                                  echo "checked='checked'";
                                                                                } ?>>Driving License<br>
                  <input name="idproof" value="4" type="radio" class="minimal" <?php if ($regData['idproof'] == '4') {
                                                                                  echo "checked='checked'";
                                                                                } ?>>Employer's card<br>
                  <input name="idproof" value="5" type="radio" class="minimal" <?php if ($regData['idproof'] == '5') {
                                                                                  echo "checked='checked'";
                                                                                } ?>>Pan card<br>
                  <input name="idproof" value="6" type="radio" class="minimal" <?php if ($regData['idproof'] == '6') {
                                                                                  echo "checked='checked'";
                                                                                } ?>>Passport
                  <?php if ($regData['registrationtype'] == 'DB' || $regData['registrationtype'] == 'NM') { ?>
                    <input name="idproof" value="7" type="radio" class="minimal" <?php if ($regData['idproof'] == '7') {
                                                                                    echo "checked='checked'";
                                                                                  } ?> style=" visibility:hidden;">
                  <?php } ?>
                  <input type="hidden" name="idproof_hidd" id="idproof_hidd" value="<?php echo $regData['idproof']; ?>" />
                </div>
              </div>



              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">ID No.*</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control pull-right" id="idNo" name="idNo" placeholder="ID No." required value="<?php echo $regData['idNo']; ?>" maxlength="25" data-parsley-maxlength="25">
                  <input type="hidden" name="idNo_hidd" id="idNo_hidd" value="<?php echo $regData['idNo']; ?>" />
                </div>
              </div>


              <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your id proof *</label>
                	<div class="col-sm-5">
                    <img src="<?php echo base_url(); ?>uploads/idproof/<?php echo $regData['idproofphoto']; ?>" height="100" width="100">
                        <input  type="file" class="form-control" name="idproofphoto" id="idproofphoto" required>
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');
                                          ?></span>
                    </div>
                </div>-->


              <!--<div class="form-group">
                <label for="roleid" class="col-sm-10 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
            <div class="col-sm-2">
               <input value="Y" name="optnletter" id="optnletter" checked="" type="radio" <?php if ($regData['optnletter'] == 'Y') {
                                                                                            echo  'checked="checked"';
                                                                                          } ?>  >Yes
                <input value="N" name="optnletter" id="optnletter" type="radio"  <?php if ($regData['optnletter'] == 'N') {
                                                                                    echo  'checked="checked"';
                                                                                  } ?>>No
           	   <input type="hidden" name="" id="optnletter_hidd" value="<?php echo $regData['optnletter']; ?>">
              <span class="error"><?php //echo form_error('optnletter');
                                  ?></span>
    		        </div>
		   </div>-->
              <?php if ($regData['optnletter'] == '') {
                $optnletter = 'Y';
              } else {
                $optnletter = $regData['optnletter'];
              } ?>
              <input type="hidden" name="optnletter" id="optnletter" value="<?php echo $optnletter; ?>">
              <input type="hidden" name="optnletter" id="optnletter_hidd" value="<?php echo $optnletter; ?>">

              <div class="box-footer">
                <div class="col-sm-7 col-xs-offset-5">
                  <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" onclick="return checkEdit_nm_new();">&nbsp;
                  <a href="<?php echo base_url(); ?>admin/Report/datewise" class="btn btn-default">Back</a>
                  <!-- <a href="<?php echo base_url(); ?>admin/MainController" class="btn btn-default pull-right">Back</a> -->
                </div>
              </div>
            </div>



          </div>
        </div>
      </div>



    </section>
  </form>
</div>
<!--<link href="<?php echo base_url(); ?>assets/admin/dist/css/styles.css" rel="stylesheet"> -->

<script>
  var site_url = "<?php echo base_url(); ?>";
</script>
<script src="<?php echo base_url() ?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url() ?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url() ?>assets/js/parsley.min.js"></script>
<!--<script src="<?php echo base_url() ?>js/js-validation.js"></script>-->
<script src="<?php echo base_url(); ?>js/validation.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script type="text/javascript">
  <!--var flag=$('#usersAddForm').parsley('validate');
  -->

</script>
<script>
  //START : AUTO FETCH FIRST, MIDDLE & LAST NAME FOR DISPLAY UNDER FULL NAME FIELD
  ///ADDED ON 12-07-2023 BY SM
  function createfullname() {
    firstname = $.trim($("#firstname").val()).toUpperCase();
    middlename = ' ' + $.trim($("#middlename").val()).toUpperCase();
    lastname = ' ' + $.trim($("#lastname").val()).toUpperCase();
    if ($.trim(firstname) != "" || $.trim(middlename) != "" || $.trim(lastname) != "") {
      $("#nameoncard").val(firstname + middlename + lastname);
    } else {
      $("#nameoncard").val("")
    }
  } //END : AUTO FETCH FIRST, MIDDLE & LAST NAME FOR DISPLAY UNDER FULL NAME FIELD

  $(document).ready(function() {
    //$('#usersAddForm').parsley('validate');

    $('#dob1').change();

    var edu = '<?php echo $regData['qualification']; ?>';
    var qualification = '<?php echo $regData['specify_qualification']; ?>';
    if (edu == 'U') {
      $('#eduqual1').val(qualification);
      $('#eduqual1_hidd').val(qualification);
      /*$('#eduqual1').attr('required','required');
      $('#eduqual2').removeAttr('required');
      $('#eduqual3').removeAttr('required');
      $('#eduqual').removeAttr('required');*/
    } else if (edu == 'G') {
      $('#eduqual2').val(qualification);
      $('#eduqual2_hidd').val(qualification);
      /*$('#eduqual1').removeAttr('required');
      $('#eduqual2').attr('required','required');
      $('#eduqual3').removeAttr('required');
      $('#eduqual').removeAttr('required');*/
    } else if (edu == 'P') {
      $('#eduqual3').val(qualification);
      $('#eduqual3_hidd').val(qualification);
      /*$('#eduqual1').removeAttr('required');
      $('#eduqual2').removeAttr('required');
      $('#eduqual3').attr('required','required');
      $('#eduqual').removeAttr('required');*/
    }


    changedu(edu);


    /*$('#datepicker,#doj').datepicker({
       autoclose: true,
	   endDate: '+0d',
	   format: 'yyyy-mm-dd'
     });*/

    $(function() {
      $("#dob1").dateDropdowns({
        submitFieldName: 'dob1',
        minAge: 0,
        maxAge: 79
      });
      // Set all hidden fields to type text for the demo
      //$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
    });
  });

  function checkEdit_nm_new() {
    //if($('#sel_namesub').val() == $('#sel_namesub_hidd').val())
    var flag = true;
    var gender = $('input[name=gender]:checked').val();
    var optedu = $('input[name=optedu]:checked').val();
    var idproof = $('input[name=idproof]:checked').val();
    //var optnletter = $('input[name=optnletter]:checked').val();
    var optnletter = $('#optnletter').val();

    var edu = '<?php echo $regData['qualification']; ?>';
    var qualification = '<?php echo $regData['specify_qualification']; ?>';
    qual_query = '';
    if (edu == 'U') {
      if ($('#eduqual1').val() == $('#eduqual1_hidd').val())
        var qual_query = true;
      else
        var qual_query = false;
    } else if (edu == 'G') {
      if ($('#eduqual2').val() == $('#eduqual2_hidd').val())
        var qual_query = true;
      else
        var qual_query = false;
    } else if (edu == 'P') {
      if ($('#eduqual3').val() == $('#eduqual3_hidd').val())
        var qual_query = true;
      else
        var qual_query = false;
    } else {
      var qual_query = true;
    }

    //       


    if ($('#sel_namesub').val().trim() == $('#sel_namesub_hidd').val().trim() && $('#firstname').val().trim() == $('#firstname_hidd').val().trim() && $('#middlename').val().trim() == $('#middlename_hidd').val().trim() && $('#lastname').val().trim() == $('#lastname_hidd').val().trim() && $('#addressline1').val().trim() == $('#addressline1_hidd').val().trim() && $('#addressline2').val().trim() == $('#addressline2_hidd').val().trim() && $('#addressline3').val().trim() == $('#addressline3_hidd').val().trim() && $('#addressline4').val().trim() == $('#addressline4_hidd').val().trim() && $('#district').val().trim() == $('#district_hidd').val().trim() && $('#city').val().trim() == $('#city_hidd').val().trim() && $('#state').val().trim() == $('#state_hidd').val().trim() &&
      $('#dob1').val().trim() == $('#datepicker_hidd').val().trim() && $('#pincode').val().trim() == $('#pincode_hidd').val().trim() && gender == $('#gender_hidd').val().trim() && optedu == $('#optedu_hidd').val().trim() && $('#email').val().trim() == $('#email_hidd').val().trim() && $('#stdcode').val().trim() == $('#stdcode_hidd').val().trim() && $('#phone').val().trim() == $('#phone_hidd').val().trim() && $('#mobile').val().trim() == $('#mobile_hidd').val().trim() && idproof == $('#idproof_hidd').val().trim() && $('#idNo').val().trim() == $('#idNo_hidd').val().trim() && $('#aadhar_card').val().trim() == $('#aadhar_card_hidd').val().trim() && optnletter == $('#optnletter_hidd').val().trim() && $('#name_of_bank_bc').val().trim() == $('#name_of_bank_bc_hidd').val().trim() && $('#doj12').val() == $('#doj12_hidd').val() && $('#ippb_emp_id').val().trim() == $('#ippb_emp_id_hidd').val().trim() && qual_query) {
      alert("Please Change atleast One Value");
      return false;
    } else {
      var flag = $('#usersEditForm').parsley().validate();
      var dob = $('#dob1').val();
      var err_msg = $("#dob_error").html();
      if (dob == '') {
        $("#dob_error").html('Please select Date Of Birth');
        $(".day").focus();
        flag = false;
      }
      if (err_msg != '')
        flag = false;

      //alert(flag);return false;
      if (flag) {
        return true; //$('#usersAddForm').submit();
      } else {
        return false;
      }
    }
  }

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
    var UGid = document.getElementById('UG');
    var GRid = document.getElementById('GR');
    var PGid = document.getElementById('PG');
    var EDUid = document.getElementById('edu');

    if (dval == 'U') {
      /*$('#eduqual1').attr('required','required');
      $('#eduqual2').removeAttr('required');
      $('#eduqual3').removeAttr('required');
      $('#eduqual').removeAttr('required');*/

      if (UGid != null) {
        //	alert('UG');
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
      /*$('#eduqual1').removeAttr('required');
      $('#eduqual2').attr('required','required');
      $('#eduqual3').removeAttr('required');
      $('#eduqual').removeAttr('required');*/

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
      /*$('#eduqual1').removeAttr('required');
      $('#eduqual2').removeAttr('required');
      $('#eduqual3').attr('required','required');
      $('#eduqual').removeAttr('required');*/

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
      document.getElementById('UG').style.display = "none";
      document.getElementById('GR').style.display = "none";
      document.getElementById('PG').style.display = "none";
      document.getElementById('edu').style.display = "block";

      $('#eduqual1').removeAttr('required');
      $('#eduqual2').removeAttr('required');
      $('#eduqual3').removeAttr('required');
      $('#eduqual').removeAttr('required');
    }
  }
</script>

<script>
  $(function() {
    $('#new_captcha').click(function(event) {
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: site_url + 'Register/generatecaptchaajax/',
        success: function(res) {
          if (res != '') {
            $('#captcha_img').html(res);
          }
        }
      });
    });

    $("#datepicker,#doj").keypress(function(event) {
      event.preventDefault();
    });
  });


  $(function() {
    $("#doj12").dateDropdowns({
      submitFieldName: 'doj12',
      minAge: 0,
      maxAge: 59
    });
  });

  $(document).ready(function() {
    $("#doj12").change(function() {
      var sel_doj = $("#doj12").val();
      if (sel_doj != '') {
        var doj_arr = sel_doj.split('-');
        if (doj_arr.length == 3) {
          CompareMaxDate(doj_arr[2], doj_arr[1], doj_arr[0]);
        } else {
          alert('Select valid date');
        }
      }
    });
  })

  function CompareMaxDate(day, month, year) {
    var exam_date_exist = $("#exam_date_exist").val();
    var check_start_date = "2023-07-01";
    var check_start_date = new Date(check_start_date);
    var check_end_date = "2024-03-31";
    var check_end_date = new Date(check_end_date);
    check_end_date.setDate(check_end_date.getDate() + 1);
    //alert(exam_date_exist);
    var flag = 0;
    if (day != '' && month != '' && year != '') {
      /*var today = new Date();
      var dd = today.getDate(); 
      var mm = today.getMonth(); 
      var yyyy = today.getFullYear();*/

      var dd = "31";
      var mm = "02";
      var yyyy = "2024";

      if (dd < 10) {
        dd = '0' + dd
      }
      if (mm < 10) {
        mm = '0' + mm
      }
      var today = new Date(yyyy, mm, dd);

      var jday = day;
      var jmnth = month;
      var jyear = year;
      var jdate = new Date(jyear, jmnth - 1, jday);

      var sel_dob = $("#dob1").val();
      var dobYear = 0;
      if (sel_dob != '') {
        var dob_arr = sel_dob.split('-');
        if (dob_arr.length == 3) {
          dobYear = dob_arr[0];
        }
      }
      var minjoinyear = parseInt(dobYear) + parseInt(18);
      //console.log(jdate +'>'+ today);

      var examDate = new Date(exam_date_exist);
      var formattedExamDate = formatDateJs(examDate);
      // Add 9 months
      var ninemonthDate = new Date(jdate);
      ninemonthDate.setMonth(ninemonthDate.getMonth() + 9);
      //alert(ninemonthDate);
      var beforeninemonthDate = new Date(exam_date_exist);
      beforeninemonthDate.setMonth(beforeninemonthDate.getMonth() - 9);
      jdate.setDate(jdate.getDate() + 1);

      /*if( jdate > today )
      {
        $("#doj_error").html('Date of joining should not be greater than 31-March-2024');
        flag = 0;
        return false;
      }
      else if( jdate < beforeninemonthDate ) // && jdate > examDate 
      {
        //console.log(jdate +'<'+ beforeninemonthDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Commencement of operations / joining as BC to be within 9 months from the date of examination.');
        //$("#doj_error").html('Please select your Date of Joining within 9 months (270 days) from the date of examination.<br> Your Examination Date is '+formattedExamDate+', your Date of Joining should be on or after '+formattedbeforeNineMonthDate+'.');
        flag = 0;
        return false;
      }*/
      if (jdate < check_start_date) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.');
        flag = 0;
        return false;
      } else if (jdate > check_end_date) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.');
        flag = 0;
        return false;
      } else if (jdate > examDate) // && jdate > examDate 
      {
        //console.log(jdate +'>'+ examDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.');
        //$("#doj_error").html('Commencement of operations / joining as BC to be within 9 months from the date of examination.');
        flag = 0;
        return false;
      } else {
        $("#doj_error").html('');
        flag = 1;
      }

      if (jyear != '' && jyear < minjoinyear) {
        //alert("Please select Proper Year of Joining");
        $("#doj_error").html("Please select Proper Year of Joining");
        $("#doj_error").focus();
        flag = 0;
        return false;
      } else {
        $("#doj_error").html('');
        flag = 1;
      }
    } else {
      $("#doj_error").html('Please select valid date');
      $("#doj_error").focus();
      flag = 0;
    }
    if (flag == 1)
      return true;
    else
      return false;
  }

  function formatDateJs(date) {
    var day = date.getDate();
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    var month = monthNames[date.getMonth()];
    var year = date.getFullYear();

    // Add leading zero to the day if it's less than 10
    day = day < 10 ? '0' + day : day;

    return day + '-' + month + '-' + year;
  }

  function check_bank_bc_id_no() {
    var name_of_bank_bc = $("#name_of_bank_bc").val();
    var ippb_emp_id = $("#ippb_emp_id").val();
    var regnumber = '<?php echo $regData['regnumber']; ?>';
    var datastring = 'name_of_bank_bc=' + name_of_bank_bc + '&ippb_emp_id=' + ippb_emp_id + '&regnumber=' + regnumber;
    $.ajax({
      url: site_url + 'Bcbfexam/check_bank_bc_id_no/',
      data: datastring,
      type: 'POST',
      async: false,
      success: function(data) {
        if (data != "") {
          $("#ippb_emp_id_error").html(data);
          $("#ippb_emp_id_error").focus();
          return false;
        } else {
          $("#ippb_emp_id_error").html(data);
        }
      }
    });
  }
</script>
<style>
  .labelleft {
    text-align: left !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    font-weight: normal;
  }

  .w50 {
    width: 50% !important;
  }
</style>
<?php $this->load->view('admin/includes/footer'); ?>