<?php
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<style>
    .date {
        width: 43% !important;
    }

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

    .box-body ul li {
        float: left;
    }
</style>
<div id="confirmBox">
    <div class="message" style="color:#F00"> <strong>VERY IMPORTANT</strong> I confirm that the Photo, Signature images uploaded belongs to me and they are clear and readable.</div>
    <span class="button yes">Confirm</span> <span class="button no">Cancel</span>
</div>
<form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" enctype="multipart/form-data">
    <input type="hidden" id="position_id" name="position_id" value="5">
    <div class="container">
        <section class="content-header">
            <h1 class="register">Application for the post of Chief Executive Officer</h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <h4> </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">BASIC DETAILS</h3>
                        </div>
                        <!-- form start -->
                        <?php //echo validation_errors(); 
                        ?>
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
                        <?php }
                        if (validation_errors() != '') { ?>
                            <div class="alert alert-danger alert-dismissible" id="error_id">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php }
                        if ($var_errors != '') { ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $var_errors; ?>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="box-body">
                            <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
                            <!-- <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Application for the post of <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <select name="position_selection" id="position_selection" class="form-control" required>
                                        <option value="">Select</option>

                                        <option value="Chief Executive Officer" <?php echo  set_select('position_selection', 'Chief Executive Officer'); ?>>Chief Executive Officer</option>
                                    </select>
                                    <span class="error" id="position_selection_error"></span>
                                </div>

                            </div> -->
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Title <span style="color:#F00">*</span></label>
                                <div class="col-sm-2">
                                    <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="Mr." <?php echo  set_select('sel_namesub', 'Mr.'); ?>>Mr.</option>
                                        <option value="Mrs." <?php echo  set_select('sel_namesub', 'Mrs.'); ?>>Mrs.</option>
                                        <option value="Ms." <?php echo  set_select('sel_namesub', 'Ms.'); ?>>Ms.</option>
                                        <option value="Dr." <?php echo  set_select('sel_namesub', 'Dr.'); ?>>Dr.</option>
                                        <option value="Prof." <?php echo  set_select('sel_namesub', 'Prof.'); ?>>Prof.</option>
                                    </select>
                                    <span class="error" id="tiitle_error"></span>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">First Name<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required
                                        value="<?php echo set_value('firstname'); ?>" data-parsley-pattern="/^[a-zA-Z]+$/" data-parsley-maxlength="30" maxlength="30" data-parsley-trigger="change keyup">
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Middle Name</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" value="<?php echo set_value('middlename'); ?>" data-parsley-pattern="/^[a-zA-Z]+$/" data-parsley-maxlength="30" maxlength="30" data-parsley-trigger="change keyup">
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Last Name <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo set_value('lastname'); ?>" required data-parsley-pattern="/^[a-zA-Z]+$/" data-parsley-maxlength="30" maxlength="30" data-parsley-trigger="change keyup">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>

                            <div class="form-group">
                                <label for="marital_status" class="col-sm-4 control-label">Marital Status<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <select name="marital_status" id="marital_status" class="form-control" required>
                                        <option value="">-Select-</option>
                                        <option value="Single" <?php echo  set_select('marital_status', 'Single'); ?>>Single</option>
                                        <option value="Married" <?php echo  set_select('marital_status', 'Married'); ?>>Married</option>
                                        <option value="Widowed" <?php echo  set_select('marital_status', 'Widowed'); ?>>Widowed</option>
                                        <option value="Divorced" <?php echo  set_select('marital_status', 'Divorced'); ?>>Divorced</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="spouse_name_group">
                                <label for="spouse_name" class="col-sm-4 control-label">Spouse's Name <span style="color:#F00" id="spouse_required_star">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="spouse_name" name="spouse_name" placeholder="Spouse's Name" value="<?php echo set_value('spouse_name'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required>
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Father's Name <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="father_husband_name" name="father_husband_name" placeholder="Father's Name" value="<?php echo set_value('father_husband_name'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required>
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Mother's Name <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Mother's Name" value="<?php echo set_value('mother_name'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required>
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Date of Birth <span style="color:#F00">*</span></label>
                                <div class="col-sm-2 example">
                                    <input type="hidden" id="head_pdc_nz_dob" name="dob1" required data-parsley-trigger="change keyup" value="<?php echo $dob; ?>">
                                    <?php
                                    $min_year = date('Y', strtotime("- 54 year"));
                                    $max_year = date('Y', strtotime("- 60 year"));
                                    ?>
                                    (Age should not be less than 55 years and should not exceed 62 years as on 31.01.2026)
                                    <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                                    <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                                    <span id="head_pdc_nz_dob_error" class="error"></span>
                                </div>
                                <!--<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob'); ?>" >-->
                                <span class="error"></span>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Gender <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="radio" class="minimal gender " id="female" name="gender" required value="female" <?php echo set_radio('gender', 'female'); ?>>
                                    Female
                                    <input type="radio" class="minimal gender " id="male" name="gender" required value="male" <?php echo set_radio('gender', 'male'); ?>>
                                    Male <span class="error"></span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Email Id<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email Id" data-parsley-type="email" value="<?php echo set_value('email'); ?>" data-parsley-maxlength="45" required data-parsley-emailcheck data-parsley-trigger-after-failure="focusout">
                                    <span class="error"> </span>
                                </div>
                            </div>

                            <!-- <div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Marital Status<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<select name="marital_status" id="marital_status" class="form-control" required>
										<option value="">-Select-</option>
										<option value="Single" <?php echo  set_select('marital_status', 'Single'); ?>>Single</option>
										<option value="Married" <?php echo  set_select('marital_status', 'Married'); ?>>Married</option>
										<option value="Widowed" style="display: none;" <?php echo  set_select('marital_status', 'Widowed'); ?>>Widowed</option>
										<option value="Divorced" <?php echo  set_select('marital_status', 'Divorced'); ?>>Divorced</option>
									</select>
								</div>
							</div> -->
                            <!-- <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Are you a person with Physically Disability? <span style="color:#F00">*</span></label>
                                <div class="col-sm-3">
                                    <input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_no" checked="checked" id="no" name="physical_disbaility" required value="no" <?php echo set_radio('physical_disbaility', 'no'); ?>>
                                    No
                                    <input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_yes" id="yes" name="physical_disbaility" required value="yes" <?php echo set_radio('physical_disbaility', 'yes'); ?>>
                                    Yes <span class="error"></span>
                                </div>
                            </div> -->

                            <!-- <div class="form-group" id="display_physical_disbaility_desc" style="display: none;">
                                <label for="roleid" class="col-sm-4 control-label">Type of Disability <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="physical_disbaility_desc" name="physical_disbaility_desc" placeholder="Type of Disability" data-parsley-minlength="2" data-parsley-maxlength="100" value="<?php echo set_value('physical_disbaility_desc'); ?>" data-parsley-mobilecheck data-parsley-trigger-after-failure="focusout">
                                    <span class="error"></span>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile Number" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('mobile'); ?>" required data-parsley-mobilecheck data-parsley-trigger="change keyup">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Alternate Mobile Number</label>
                                <div class="col-sm-5">
                                    <input type="tel" class="form-control" id="alternate_mobile" name="alternate_mobile" placeholder="Alternate Mobile Number" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('alternate_mobile'); ?>" data-parsley-trigger="change keyup">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">PAN Number<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input required type="tel" class="form-control" id="pan_no" name="pan_no" placeholder="PAN Number" data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-pannocheck value="<?php echo set_value('pan_no'); ?>" data-parsley-pattern="/^[A-Z-0-9/ ]+$/" data-parsley-trigger="change keyup">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Aadhar Card Number<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input required type="text" class="form-control" id="aadhar_card_no" name="aadhar_card_no" placeholder="Aadhar Card Number"
                                        data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12"
                                        value="<?php echo set_value('aadhar_card_no'); ?>" data-parsley-trigger="change keyup">
                                    <span class="error"> </span>
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
                                <label for="roleid" class="col-sm-4 control-label">Address Line 1 <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address Line 1" value="<?php echo set_value('addressline1'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Address Line 2 <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address Line 2" value="<?php echo set_value('addressline2'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Address Line 3</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address Line 3" value="<?php echo set_value('addressline3'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Address Line 4</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address Line 4" value="<?php echo set_value('addressline4'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">District <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                                <div class="col-sm-3">
                                    <select class="form-control" id="state" name="state" required onchange="javascript:checksate(this.value)" data-parsley-trigger="change">
                                        <option value="">Select</option>
                                        <?php if (count($states) > 0) {
                                            foreach ($states as $row1) {   ?>
                                                <option value="<?php echo $row1['state_code']; ?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name']; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <input hidden="statepincode" id="statepincode" value="">
                                </div>
                                <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode'); ?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin data-parsley-type="number" data-parsley-trigger="change keyup">
                                    <span style="display: inline-block;width: 100%;">(Max 6 digits)</span> <span class="error"> </span>
                                </div>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label for="roleid" class="col-sm-4 control-label">Contact Number</label>
                                <div class="col-sm-5">
                                    <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="Contact No" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="12" value="<?php echo set_value('contact_number'); ?>" data-parsley-trigger-after-failure="focusout">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <!-- Permanat Address -->
                            <div class="box-header with-border">
                                <h3 class="box-title">PERMANENT ADDRESS</h3>
                            </div>

                            <div class="box-header with-border nobg">
                                <h6 class="box-title-hd">
                                    <input type="checkbox" name="same_as_above" onclick="sameAsAbove(this.form)">
                                    <em>Same as Communication address</em>
                                </h6>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Address line 1 <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="addressline1_pr" name="addressline1_pr" placeholder="Address line 1" value="<?php echo set_value('addressline1_pr'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Address line 2 <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="addressline2_pr" name="addressline2_pr" placeholder="Address line 2" value="<?php echo set_value('addressline2_pr'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Address line 3</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="addressline3_pr" name="addressline3_pr" placeholder="Address line 3" value="<?php echo set_value('addressline3_pr'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Address line 4</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="addressline4_pr" name="addressline4_pr" placeholder="Address line 4" value="<?php echo set_value('addressline4_pr'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">District <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="district_pr" name="district_pr" placeholder="District" required value="<?php echo set_value('district_pr'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="city_pr" name="city_pr" placeholder="City" required value="<?php echo set_value('city_pr'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                                <div class="col-sm-3">
                                    <select class="form-control" id="state_pr" name="state_pr" required onchange="javascript:checksate(this.value)" data-parsley-trigger="change keyup">
                                        <option value="">Select</option>
                                        <?php if (count($states) > 0) {
                                            foreach ($states as $row1) {   ?>
                                                <option value="<?php echo $row1['state_code']; ?>" <?php echo  set_select('state_pr', $row1['state_code']); ?>><?php echo $row1['state_name']; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <input hidden="statepincode_pr" id="statepincode_pr" value="">
                                </div>
                                <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="pincode_pr" name="pincode_pr" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode_pr'); ?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin_pr data-parsley-type="number" data-parsley-trigger="change keyup">
                                    <span style="display: inline-block;width: 100%;">(Max 6 digits)</span> <span class="error"> </span>
                                </div>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label for="roleid" class="col-sm-4 control-label">Contact Number</label>
                                <div class="col-sm-5">
                                    <input type="tel" class="form-control" id="contact_number_pr" name="contact_number_pr" placeholder="Contact No" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="12" value="<?php echo set_value('contact_number_pr'); ?>" data-parsley-trigger-after-failure="focusout">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!------------------------------| Educational Qualification |--------------------------->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">EDUCATIONAL QUALIFICATION</h3>
                                </div>
                            </div>

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">ESSENTIAL</h3>
                                </div>
                            </div>
                            <br />
                            <!-- <b>Educational Qualification I - Academic (Graduation Onward)*</b>
                            <br />
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Graduation<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="ess_course_name" required name="ess_course_name" placeholder="Graduation" value="<?php echo set_value('ess_course_name'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Graduation Subject <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="ess_pg_stream_subject" required name="ess_pg_stream_subject" placeholder="Graduation Subject" value="<?php echo set_value('ess_pg_stream_subject'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">College/Institution<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="ess_college_name" name="ess_college_name" placeholder="College/Institution" value="<?php echo set_value('ess_college_name'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">University <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="ess_university" name="ess_university" placeholder="University" value="<?php echo set_value('ess_university'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Period<span style="color:#F00">*</span></label>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 date">
                                        <input type="text" class="form-control" id="ess_from_date" name="ess_from_date" placeholder="From Date" required value="<?php echo set_value('ess_from_date'); ?>" readonly>
                                    </div>
                                    <div class="col-sm-3 date">
                                        <input type="text" class="form-control" id="ess_to_date" name="ess_to_date" placeholder="To Date" required value="<?php echo set_value('ess_to_date'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group"><?php //Percentage  * 
                                                    ?>
                                <label class="col-sm-4 control-label">Percentage Obtained <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" id="ess_percentage" name="ess_percentage" placeholder="Percentage Obtained" value="<?php echo set_value('ess_percentage'); ?>" data-parsley-maxlength="20" step="0.01" data-parsley-type="number" required max="100">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Class/Grade <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="ess_class" name="ess_class" placeholder="Class/Grade" value="<?php echo set_value('ess_class'); ?>" data-parsley-maxlength="50" maxlength="50" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>


                                </div>
                            </div> -->


                            <b>Educational Qualification I - Post Graduation * </b>
                            <br />
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Qualification<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="post_qua_name" name="post_qua_name" required placeholder="Qualification" value="<?php echo set_value('post_qua_name'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Post Graduation Subject <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="post_gra_sub" required name="post_gra_sub" placeholder="Post Graduation Subject" value="<?php echo set_value('post_gra_sub'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">College/Institution <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="post_gra_college_name" name="post_gra_college_name" placeholder="College/Institution" value="<?php echo set_value('post_gra_college_name'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">University <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="post_gra_university" name="post_gra_university" placeholder="University" value="<?php echo set_value('post_gra_university'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    <span class="error"></span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Period<span style="color:#F00">*</span></label>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 date">
                                        <input type="text" class="form-control from_date" id="post_gra_from_date" name="post_gra_from_date" placeholder="From Date" required value="<?php echo set_value('post_gra_from_date'); ?>" readonly>
                                    </div>
                                    <div class="col-sm-3 date">
                                        <input type="text" class="form-control to_date" id="post_gra_to_date" name="post_gra_to_date" placeholder="To Date" required value="<?php echo set_value('post_gra_to_date'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="form-group"><?php //Aggregate Marks Obtained * 
                                                    ?>
                                <label class="col-sm-4 control-label">Aggregate Marks Obtained <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" id="post_aggregate_marks_obtained" name="post_aggregate_marks_obtained" placeholder="Aggregate Marks Obtained" value="<?php echo set_value('post_aggregate_marks_obtained'); ?>" step="0.01" maxlength="20" data-parsley-maxlength="20" step="0.01" data-parsley-type="number" required>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="form-group"><?php //Aggregate Maximum Marks * 
                                                    ?>
                                <label class="col-sm-4 control-label">Aggregate Maximum Marks <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" id="post_gra_aggregate_max_marks" name="post_gra_aggregate_max_marks" placeholder="Aggregate Maximum Marks" value="<?php echo set_value('post_gra_aggregate_max_marks'); ?>" data-parsley-maxlength="20" step="0.01" data-parsley-type="number" required>
                                    <span class="error"></span>
                                </div>
                            </div> -->

                            <div class="form-group"><?php //Percentage  * 
                                                    ?>
                                <label class="col-sm-4 control-label">Percentage Obtained <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" id="post_gra_percentage" name="post_gra_percentage" placeholder="Percentage Obtained" value="<?php echo set_value('post_gra_percentage'); ?>" data-parsley-maxlength="20" step="0.01" data-parsley-type="number" required max="100">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Class/Grade <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="post_gra_class" name="post_gra_class" placeholder="Class/Grade" value="<?php echo set_value('post_gra_class'); ?>" data-parsley-maxlength="50" maxlength="50" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                </div>
                            </div>


                            <hr class="co-md-12">
                            <b>Educational Qualification II: Additional Qualifications/Certification</b>
                            <br />
                            <!-- <input type="hidden" name="eduqual_add" id="eduqual_add" value="1"> -->
                            <div id="eduqual_dynamic_field">
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Qualification </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="cer_qua_name" name="cer_qua_name[]" placeholder="Qualification" value="<?php echo set_value('cer_qua_name'); ?>" data-parsley-maxlength="50" maxlength="50" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Name of the Subject </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="cer_gra_sub" name="cer_gra_sub[]" placeholder="Name of the Subject" value="<?php echo set_value('cer_gra_sub'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                        <span class="error"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">College/Institution </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="cer_college_name" name="cer_college_name[]" placeholder="College/Institution" value="<?php echo set_value('cer_college_name'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                        <span class="error"></span>
                                    </div>
                                    (Max 30 Characters)
                                </div>
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">University</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="cer_university" name="cer_university[]" placeholder="University" value="<?php echo set_value('cer_university'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                        <span class="error"></span>
                                    </div>
                                    (Max 30 Characters)
                                </div>
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Period</label>
                                    <div class="col-sm-6">
                                        <div class="col-sm-3 date">
                                            <input type="text" class="form-control qualification-from-date" id="cer_from_date" name="cer_from_date[]" placeholder="From Date" value="<?php echo set_value('cer_from_date'); ?>" readonly>
                                        </div>
                                        <div class="col-sm-3 date">
                                            <input type="text" class="form-control qualification-to-date" id="cer_to_date" name="cer_to_date[]" placeholder="To Date" value="<?php echo set_value('cer_to_date'); ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="form-group"><?php //Aggregate Marks Obtained * 
                                                        ?>
                                    <label class="col-sm-4 control-label">Aggregate Marks Obtained </label>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control" id="cer_marks_obtained" name="cer_marks_obtained[]" placeholder="Aggregate Marks Obtained" value="<?php echo set_value('cer_marks_obtained'); ?>" step="0.01" maxlength="20" data-parsley-maxlength="20" step="0.01" data-parsley-type="number">
                                        <span class="error"></span>
                                    </div>
                                </div>

                                <div class="form-group"><?php //Aggregate Maximum Marks * 
                                                        ?>
                                    <label class="col-sm-4 control-label">Aggregate Maximum Marks </label>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control" id="cer_aggregate_max_marks" name="cer_aggregate_max_marks[]" placeholder="Aggregate Maximum Marks" value="<?php echo set_value('cer_aggregate_max_marks'); ?>" data-parsley-maxlength="20" step="0.01" data-parsley-type="number">
                                        <span class="error"></span>
                                    </div>
                                </div> -->

                                <div class="form-group"><?php //Percentage  * 
                                                        ?>
                                    <label class="col-sm-4 control-label">Percentage Obtained </label>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control" id="cer_percentage" name="cer_percentage[]" placeholder="Percentage Obtained" value="<?php echo set_value('cer_percentage'); ?>" data-parsley-maxlength="20" step="0.01" data-parsley-type="number" max="100">
                                        <span class="error"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Class/Grade </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="cer_class" name="cer_class[]" placeholder="Class/Grade" value="<?php echo set_value('cer_class'); ?>" data-parsley-maxlength="50" maxlength="50" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                    </div>
                                </div>
                                <hr class="co-md-12">
                            </div>
                            <button type="button" name="eduqual_add" id="eduqual_add" class="btn btn-success">Add Qualification</button>
                            <input type="hidden" name="eduqual_add" id="eduqual_add" value="1">
                            <!-- <div class="box-title box-header"><strong>Area of Specialization</strong> </div>
                            <br />


                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Area of Specialization<span style="color:#F00">*</span></label>
                                <div class="col-sm-6">
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="area_of_specialization" name="area_of_specialization" placeholder="Area of Specialization" required value="<?php echo set_value('area_of_specialization'); ?>">
                                    </div>
                                </div>
                            </div> -->


                            <!-- <div class="box-title box-header"><strong>CAIIB</strong> </div>
                            <br />
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">CAIIB <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <select name="ess_subject" id="ess_subject" class="form-control" required>

                                        <option value="CAIIB" <?php echo  set_select('ess_subject', 'CAIIB'); ?>>CAIIB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Year of passing<span style="color:#F00">*</span></label>
                                <div class="col-sm-6">
                                    <div class="col-sm-5 date">
                                        <input type="text" class="form-control" id="year_of_passing" name="year_of_passing" placeholder="Year of passing" required value="<?php echo set_value('year_of_passing'); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Membership Number<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="membership_number" name="membership_number" placeholder="Membership Number" value="<?php echo set_value('membership_number'); ?>" data-parsley-maxlength="20" maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                </div>
                            </div> -->

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">CAIIB</h3>
                                </div>
                            </div>

                            <br />

                            <!-- CAIIB Yes/No Radio -->
                            <div class="form-group">
                                <label for="caiib" class="col-sm-4 control-label">CAIIB <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <label style="margin-right:10px;">
                                        <input type="radio" name="ess_subject" id="caiib_yes" value="Yes" required
                                            <?php echo set_radio('ess_subject', 'Yes'); ?>> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="ess_subject" id="caiib_no" value="No"
                                            <?php echo set_radio('ess_subject', 'No'); ?>> No
                                    </label>
                                </div>
                            </div>

                            <!-- Hidden Section: Only visible when CAIIB = Yes -->
                            <div id="caiib_details" style="display:none;">
                                <!-- <div class="form-group">
                                    <label for="year_of_passing" class="col-sm-4 control-label">Year of Passing <span style="color:#F00">*</span></label>
                                    <div class="col-sm-7">
                                        <div class="col-sm-5 date">
                                            <input type="text" class="form-control" id="year_of_passing" name="year_of_passing" placeholder="Year of Passing"
                                                value="<?php echo set_value('year_of_passing'); ?>" style="background-color: #fff;" readonly>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="form-group">
                                    <label for="membership_number" class="col-sm-4 control-label">Membership Number <span style="color:#F00">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="membership_number" name="membership_number" placeholder="Membership Number"
                                            value="<?php echo set_value('membership_number'); ?>" data-parsley-maxlength="20" maxlength="20"
                                            data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    </div>
                                </div>
                            </div>

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Ph.D (IN BANKING OR FINANCE)</h3>
                                </div>
                            </div>
                            <br />

                            <!-- <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Ph D (in Banking or Finance)</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="ph_d" name="ph_d" placeholder="Ph D (in Banking or Finance)" value="<?php echo set_value('ph_d'); ?>" data-parsley-maxlength="40" maxlength="40" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">

                                </div>(Max 40 Characters)
                            </div> -->
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Name of Research Topic</label>

                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="phd_course" name="phd_course" placeholder="Name of Research Topic" value="<?php echo set_value('phd_course'); ?>" data-parsley-maxlength="40" maxlength="40" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">

                                </div>(Max 40 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">University</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="phd_university" name="phd_university" placeholder="University" value="<?php echo set_value('phd_university'); ?>" data-parsley-maxlength="40" maxlength="40" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                </div>(Max 40 Characters)
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Period</label>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 date">
                                        <input type="text" class="form-control from_date" id="phd_from_date"
                                            name="phd_from_date" placeholder="From Date" value="<?php echo set_value('phd_from_date'); ?>" readonly>
                                    </div>
                                    <div class="col-sm-3 date">
                                        <input type="text" class="form-control to_date" id="phd_to_date" name="phd_to_date" placeholder="To Date" value="<?php echo set_value('phd_to_date'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <!--<div id="dynamic_field">-->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">DESIRABLE</h3>
                                </div>
                            </div>    
                            <br />
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Name of course</label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="course_code" name="course_code">
                                        <option value="">Select</option>
                                        <?php if (count($careers_course_mst) > 0) {
                                            foreach ($careers_course_mst as $row1) {   ?>
                                                <option value="<?php echo $row1['course_code']; ?>" <?php echo  set_select('course_code', $row1['course_code']); ?>><?php echo $row1['course_name']; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Specialization</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="name_subject_of_course" name="name_subject_of_course" placeholder="Specialization" value="<?php echo set_value('name_subject_of_course'); ?>" data-parsley-maxlength="200" maxlength="200" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">College Name and Address</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="college_name" name="college_name" placeholder="College Name and Address" value="<?php echo set_value('college_name'); ?>" data-parsley-maxlength="200" maxlength="200" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"></span>
                                </div>
                                (Max 200 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">University</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="university" name="university" placeholder="University" value="<?php echo set_value('university'); ?>" data-parsley-maxlength="200" maxlength="200" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"></span>
                                </div>
                                (Max 200 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Period</label>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 date">
                                        <input type="text" class="form-control ph_from_date" id="des_from_date"
                                            name="des_from_date" placeholder="From Date" value="<?php echo set_value('des_from_date'); ?>" readonly>
                                    </div>
                                    <div class="col-sm-3 date">
                                        <input type="text" class="form-control ph_to_date" id="des_to_date" name="des_to_date" placeholder="To Date" value="<?php echo set_value('des_to_date'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="form-group"><?php //Aggregate Marks Obtained * 
                                                            ?>
                                <label class="col-sm-4 control-label">Aggregate Marks Obtained</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" id="aggregate_marks_obtained" name="aggregate_marks_obtained" placeholder="Aggregate Marks Obtained" value="<?php echo set_value('aggregate_marks_obtained'); ?>" data-parsley-maxlength="20" step="0.01" data-parsley-type="number">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="form-group"><?php //Aggregate Maximum Marks * 
                                                    ?>
                                <label class="col-sm-4 control-label">Aggregate Maximum Marks</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" id="aggregate_max_marks" name="aggregate_max_marks" placeholder="Aggregate Maximum Marks" value="<?php echo set_value('aggregate_max_marks'); ?>" data-parsley-maxlength="20" step="0.01" data-parsley-type="number">
                                    <span class="error"></span>
                                </div>
                            </div> -->

                            <div class="form-group"><?php //Percentage  * 
                                                    ?>
                                <label class="col-sm-4 control-label">Percentage Obtained</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" id="percentage" name="percentage" step="0.01" placeholder="Percentage Obtained" value="<?php echo set_value('percentage'); ?>" data-parsley-maxlength="20" step="0.01" data-parsley-type="number" max="100">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <?php /*<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Grade/Percentage</label>
								<div class="col-sm-5">
								<input type="text" class="form-control" id="grade_marks" name="grade_marks" placeholder="Grade/Percentage"  value="<?php echo set_value('grade_marks');?>"  data-parsley-maxlength="20" maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9 , + - / ]+$/">
								<span class="error"></span> </div>
							</div> */ ?>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Class</label>
                                <div class="col-sm-5">
                                    <select name="class" id="class" class="form-control">
                                        <option value="">-Select-</option>
                                        <option value="First Class" <?php echo  set_select('class', 'First Class'); ?>>First Class</option>
                                        <option value="Second Class" <?php echo  set_select('class', 'Second Class'); ?>>Second Class</option>
                                        <option value="Pass Class" <?php echo  set_select('class', 'Pass Class'); ?>>Pass Class</option>
                                    </select>
                                </div>
                            </div>


                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">EMPLOYMENT HISTORY (from Recent employment to Oldest employment) - Last 7 positions held with role & responsibilities in detail</h3>
                                </div>
                            </div>
                            <!--  <button type="button" name="job_add" id="job_add" class="btn btn-success">Add Previous Employment Details</button> -->


                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Whether currently in service? <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="radio" class="minimal whether_in_service whether_in_service_no" id="no" name="whether_in_service" required value="no" <?php echo set_radio('whether_in_service', 'no'); ?>>
                                    No
                                    <input type="radio" class="minimal whether_in_service whether_in_service_yes" id="yes" name="whether_in_service" required value="yes" <?php echo set_radio('whether_in_service', 'yes'); ?>>
                                    Yes <span class="error"></span>
                                </div>
                            </div>
                            <div class="form-group name_of_present_organization_div">
                                <label for="roleid" class="col-sm-4 control-label">Name of the Present Organisation <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="name_of_present_organization" name="name_of_present_organization" placeholder="Name of the Present Organisation" value="<?php echo set_value('name_of_present_organization'); ?>" data-parsley-maxlength="40" maxlength="40" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 300 Characters)
                            </div>
                            <div class="form-group service_from_date_div">
                                <label for="roleid" class="col-sm-4 control-label">Period <span style="color:#F00">*</span></label>
                                <div class="col-sm-6">
                                    <div class="col-sm-5 date">
                                        <input type="text" class="form-control service_from_date" id="service_from_date" name="service_from_date" placeholder="From Date" value="<?php echo set_value('service_from_date'); ?>" readonly>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group comm_address_of_org_div">
                                <label for="roleid" class="col-sm-4 control-label">Communication Address of the Organisation <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="comm_address_of_org" name="comm_address_of_org" placeholder="Communication Address of the Organisation" value="<?php echo set_value('comm_address_of_org'); ?>" data-parsley-maxlength="255" maxlength="255" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 300 Characters)
                            </div>

                            <div class="form-group curr_designation_div">
                                <label for="roleid" class="col-sm-4 control-label">Designation/Post Held <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="curr_designation" name="curr_designation" placeholder="Designation/Post Held" value="<?php echo set_value('curr_designation'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 300 Characters)
                            </div>

                            <div class="form-group any_other_details_div">
                                <label for="roleid" class="col-sm-4 control-label">Any Other Details</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="any_other_details" name="any_other_details" placeholder="Any Other Details" value="<?php echo set_value('any_other_details'); ?>" data-parsley-maxlength="100" maxlength="100" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 300 Characters)
                            </div>

                            <div class="form-group no_service_div">
                                <label for="roleid" class="col-sm-4 control-label">If Not In Service</label>
                                <div class="col-sm-5 ">

                                </div>
                            </div>
                            <div class="form-group vrs_register_date_div">
                                <label for="roleid" class="col-sm-4 control-label">Date of Superannuation/VRS/Resignation etc <span style="color:#F00">*</span></label>
                                <div class="col-sm-5 ">
                                    <input placeholder="Date of VRS/Resignation etc" type="text" class="form-control" id="vrs_register_date" name="vrs_register_date" value="">

                                    <span id="vrs_register_date_err" class="error"></span>
                                </div>

                                <span class="error"></span>
                            </div>
                            <div class="form-group reason_of_resign_div">
                                <label for="roleid" class="col-sm-4 control-label">Reason for Resignation/Leaving <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="reason_of_resign" name="reason_of_resign" placeholder="Reason for Resignation/Leaving" value="<?php echo set_value('reason_of_resign'); ?>" data-parsley-maxlength="300" maxlength="300" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 300 Characters)
                            </div>


                            <?php

                            if (count($organization) > 0) {
                                $i = 0;
                                foreach ($organization as $job_key => $job_val) {
                                    $organization_val = $job_val;
                                    $designation_val = $designation[$job_key];
                                    $responsibilities_val = $responsibilities[$job_key];
                                    $job_from_date_val = $job_from_date[$job_key];
                                    $job_to_date_val = $job_to_date[$job_key];

                            ?>
                                    <div id="job_dynamic_field">
                                        <div id="job_row<?php echo $i; ?>">
                                            <div class="form-group">
                                                <label for="roleid" class="col-sm-4 control-label">Name of the Organisation<span style="color:#F00">*</span></label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="organization" name="organization[]" placeholder="Name of the Organisation" value="<?php echo $organization_val; //set_value('organization');
                                                                                                                                                                                    ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
                                                    <span class="error"> </span>
                                                </div>
                                                (Max 30 Characters)
                                            </div>

                                            <div class="form-group">
                                                <label for="roleid" class="col-sm-4 control-label">Period <span style="color:#F00">*</span></label>
                                                <div class="col-sm-6">
                                                    <div class="col-sm-3 date">
                                                        <input type="text" class="form-control job_from_date" id="job_from_date" name="job_from_date[]" placeholder="From Date" value="<?php echo $job_from_date_val; //echo set_value('job_from_date');
                                                                                                                                                                                        ?>" required readonly>
                                                    </div>
                                                    <div class="col-sm-3 date">
                                                        <input type="text" class="form-control job_to_date" id="job_to_date" name="job_to_date[]" placeholder="To Date" value="<?php echo $job_to_date_val; //echo set_value('job_to_date');
                                                                                                                                                                                ?>" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="roleid" class="col-sm-4 control-label">Designation <span style="color:#F00">*</span></label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="designation" name="designation[]" placeholder="Designation" value="<?php echo $designation_val; //echo set_value('designation');
                                                                                                                                                                    ?>" data-parsley-maxlength="40" maxlength="40" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"" required>
													<span class=" error"> </span>
                                                </div>
                                                (Max 40 Characters)
                                            </div>
                                            <div class="form-group">
                                                <label for="roleid" class="col-sm-4 control-label">Responsibilities <span style="color:#F00">*</span></label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="responsibilities" name="responsibilities[]" placeholder="Responsibilities" value="<?php echo $responsibilities_val; //echo set_value('responsibilities');
                                                                                                                                                                                    ?>" data-parsley-maxlength="300" maxlength="300" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"" required>
													<span class=" error"> </span>
                                                </div>
                                                (Max 300 Characters)
                                            </div>

                                            <?php
                                            if ($i == 0) { ?>
                                                <button type="button" name="job_add" id="job_add" class="btn btn-success">Add Previous Employment Details</button>
                                            <?php
                                            }
                                            if ($i > 4) {
                                            ?>
                                                <a href="javascript:void(0);" name="btn_remove_job" id="<?php echo $i; ?>" class="btn btn-danger btn_remove_job">Remove Employment Details</a>
                                                <!-- <button name="btn_remove_job" id="<?php echo $i; ?>" class="btn btn-danger btn_remove_job">Remove Employment Details</button>-->
                                            <?php
                                            } ?>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                    //echo '<br>';
                                    ?>
                                <?php }
                                ?>
                                <input type="hidden" name="org_add" id="org_add" value="<?php echo $i; ?>">
                            <?php
                            } else {
                            ?>
                                <input type="hidden" name="org_add" id="org_add" value="1">
                                <div id="job_dynamic_field">
                                    <!-- <div class="form-group">
    <label for="roleid" class="col-sm-4 control-label">Name of the Organisation <span style="color:#F00">*</span></label>
    <div class="col-sm-5">
        <input type="text" class="form-control" id="organization" name="organization[]" placeholder="Name of the Organisation" value="<?php  //echo set_value('organization'); 
                                                                                                                                        ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
        <span class="error"> </span>
    </div>
    (Max 30 Characters)
</div>

<div class="form-group">
    <label for="roleid" class="col-sm-4 control-label">Period <span style="color:#F00">*</span></label>
    <div class="col-sm-6">
        <div class="col-sm-3 date">
            <input type="text" class="form-control job_from_date" id="job_from_date" name="job_from_date[]" placeholder="From Date" value="<?php //echo set_value('job_from_date');
                                                                                                                                            ?>" required readonly>
        </div>
        <div class="col-sm-3 date">
            <input type="text" class="form-control job_to_date" id="job_to_date" name="job_to_date[]" placeholder="To Date" value="<?php //echo set_value('job_to_date');
                                                                                                                                    ?>" required readonly>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="roleid" class="col-sm-4 control-label">Designation <span style="color:#F00">*</span></label>
    <div class="col-sm-5">
        <input type="text" class="form-control" id="designation" name="designation[]" placeholder="Designation" value="<?php //echo set_value('designation');
                                                                                                                        ?>" data-parsley-maxlength="40" maxlength="40" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"" required>
		<span class=" error"> </span>
    </div>
    (Max 40 Characters)
</div>
                                    
<div class="form-group">
    <label for="roleid" class="col-sm-4 control-label">Responsibilities <span style="color:#F00">*</span></label>
    <div class="col-sm-5">
        <input type="text" class="form-control" id="responsibilities" name="responsibilities[]" placeholder="Responsibilities" value="<?php //echo set_value('responsibilities');
                                                                                                                                        ?>" data-parsley-maxlength="300" maxlength="300" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"" required>
		<span class=" error"> </span>
    </div>
    (Max 300 Characters)
</div>
<hr class="co-md-12"> -->

                                </div>
                                <button type="button" name="job_add" id="job_add" class="btn btn-success">Add Previous Employment Details</button>
                            <?php
                            }
                            ?>

                            <!-- Gaurav code start -->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">OTHER ORGANISATION</h3>
                                </div>
                            </div>

                            <div id="orgnsn_dynamic_field">
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Name of the Organisation </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="org_organization" name="org_organization[]" placeholder="Name of the Organisation" data-parsley-maxlength="30" data-parsley-trigger="change keyup" value="">
                                        <span class="error"> </span>
                                    </div>
                                    (Max 30 Characters)
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Period </label>
                                    <div class="col-sm-6">
                                        <div class="col-sm-3 date">
                                            <input type="text" class="form-control org_from_date" id="org_from_date" name="org_from_date[]" placeholder="From Date" value="" readonly>
                                        </div>
                                        <div class="col-sm-3 date">
                                            <input type="text" class="form-control org_to_date" id="org_to_date" name="org_to_date[]" placeholder="To Date" value="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Designation </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="org_designation" name="org_designation[]" placeholder="Designation" data-parsley-maxlength="40" data-parsley-trigger="change keyup" value="">
                                        <span class=" error"> </span>
                                    </div>
                                    (Max 40 Characters)
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Responsibilities</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="org_responsibilities" name="org_responsibilities[]" placeholder="Responsibilities" value="" data-parsley-maxlength="300" data-parsley-trigger="change keyup">
                                        <span class=" error"> </span>
                                    </div>
                                    (Max 300 Characters)
                                </div>
                                <hr class="co-md-12">
                            </div>

                            <button type="button" name="orgnsn_add" id="orgnsn_add" class="btn btn-success">Add Organisation Details</button>
                            <input type="hidden" name="orgnsn_add" id="orgnsn_add" value="1">





                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Experience as Principal / Director of a banking staff training college / centre/ management institution</h3>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                    <label for="roleid" class="col-sm-12 control-label"> </label> -->
                            <!-- <div class="col-sm-5">
                                    <input type="text" class="form-control" id="experience_as_principal" name="experience_as_principal[]" placeholder="Experience as Principal"  value="<?php //echo set_value('experience_as_principal');
                                                                                                                                                                                        ?>"  data-parsley-maxlength="300" maxlength="300" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" >
                                    <span class="error"> </span> 
                                </div>
                                (Max 300 Characters) -->
                            <!-- </div> -->

                            <!-- <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Experience as Faculty , Professor, Lecturer</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="experience_as_faculty" name="experience_as_faculty[]" placeholder="Experience as Faculty"  value="<?php //echo set_value('experience_as_faculty');
                                                                                                                                                                                    ?>"  data-parsley-maxlength="300" maxlength="300" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" >
                                    <span class="error"> </span> 
                                </div>
                                (Max 300 Characters) 
                            </div> -->

                            <div id="faculty_exp_dynamic_field">
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Name of the Organisation <span style="color:#F00"></span></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="exp_org_organization" name="exp_org_organization[]" placeholder="Name of the Organisation" value="" data-parsley-maxlength="30" data-parsley-trigger="change keyup">
                                        <span class="error"> </span>
                                    </div>
                                    (Max 30 Characters)
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Period <span style="color:#F00"></span></label>
                                    <div class="col-sm-6">
                                        <div class="col-sm-3 date">
                                            <input type="text" class="form-control exp_from_date" id="exp_from_date" name="exp_job_from_date[]" placeholder="From Date" value="" readonly>
                                        </div>
                                        <div class="col-sm-3 date">
                                            <input type="text" class="form-control exp_to_date" id="exp_to_date" name="exp_job_to_date[]" placeholder="To Date" value="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Designation <span style="color:#F00"></span></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="exp_designation" name="exp_designation[]" placeholder="Designation" value="" data-parsley-maxlength="40" data-parsley-trigger="change keyup">
                                        <span class=" error"> </span>
                                    </div>
                                    (Max 40 Characters)
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Responsibilities <span style="color:#F00"></span></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="exp_responsibilities" name="exp_responsibilities[]" placeholder="Responsibilities" value="" data-parsley-maxlength="300" data-parsley-trigger="change keyup">
                                        <span class=" error"> </span>
                                    </div>
                                    (Max 300 Characters)
                                </div>
                                <hr class="co-md-12">
                            </div>

                            <button type="button" name="faculty_exp_add" id="faculty_exp_add" class="btn btn-success">Add Experience Details</button>
                            <input type="hidden" name="faculty_exp_add" id="faculty_exp_add" value="1">
                            

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">EXPERIENCE AS FACULTY</h3>
                                </div>
                            </div>

                            <div id="add_fac_exp_dynamic_field">
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Experience as Faculty</label>
                                    <div class="col-sm-5">
                                        <textarea rows="4" cols="50" class="form-control" id="exp_in_bank" name="exp_in_bank[]" placeholder="Experience as Faculty" data-parsley-maxlength="1000" maxlength="1000" data-parsley-trigger="change keyup" ><?php echo set_value('exp_in_bank'); ?></textarea>
                                    </div>(Max 1000 Characters)
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Period</label>
                                    <div class="col-sm-6">
                                        <div class="col-sm-3 date">
                                            <input type="text" class="form-control exp_faculty_from_date" id="exp_faculty_from_date" name="exp_faculty_from_date[]" placeholder="From Date" value="<?php echo set_value('exp_faculty_from_date'); ?>" readonly>
                                        </div>

                                        <div class="col-sm-3 date">
                                            <input type="text" class="form-control exp_faculty_to_date" id="exp_faculty_to_date" name="exp_faculty_to_date[]" placeholder="To Date" value="<?php echo set_value('exp_faculty_to_date'); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Subjects Handled</label>
                                    <div class="col-sm-5">
                                        <textarea rows="4" cols="50" class="form-control" id="subject_handled" name="subject_handled[]" placeholder="Subjects Handled" data-parsley-maxlength="500" maxlength="500" data-parsley-trigger="change keyup" ><?php echo set_value('subject_handled'); ?></textarea>
                                    </div>(Max 500 Characters)
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Area of Specialization</label>
                                    <div class="col-sm-5">
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="exp_in_functional_area" name="exp_in_functional_area[]" placeholder="Area of Specialization" value="<?php echo set_value('exp_in_functional_area'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="roleid" class="col-sm-4 control-label">Membership of Professional Associations</label>
                                    <div class="col-sm-5">
                                        <textarea rows="4" cols="50" class="form-control" id="professional_ass" name="professional_ass[]" placeholder="Membership of Professional Associations" data-parsley-maxlength="500" maxlength="500" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/" data-parsley-maxlength="500" maxlength="500" data-parsley-trigger="change keyup"  ><?php echo set_value('professional_ass'); ?></textarea>
                                    </div>(Max 500 Characters)
                                </div>
                                <hr class="co-md-12">
                            </div>    
                            <button type="button" name="add_fac_exp_add" id="add_fac_exp_add" class="btn btn-success">Add Faculty Experience Details</button>
                            <input type="hidden" name="add_fac_exp_add" id="add_fac_exp_add" value="1">


                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">LANGUAGES</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Languages Known I <span style="color:#F00">*</span></label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="languages_known" name="languages_known" placeholder="Languages Known" value="<?php echo set_value('languages_known'); ?>" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/" data-parsley-maxlength="30" maxlength="30" required>
                                    <span class="error"></span>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" class="minimal" id="languages_option" name="languages_option[]" value="Read" <?php echo set_checkbox('languages_option', 'Read'); ?> required />
                                    <label>Read</label>
                                    <input type="checkbox" class="minimal" id="languages_option" name="languages_option[]" value="Write" <?php echo set_checkbox('languages_option', 'Write'); ?> />
                                    <label>Write</label>
                                    <input type="checkbox" class="minimal" id="languages_option" name="languages_option[]" value="Speak" <?php echo set_checkbox('languages_option', 'Speak'); ?> />
                                    <label>Speak</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Languages Known II</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="languages_known1" name="languages_known1" placeholder="Languages Known" value="<?php echo set_value('languages_known1'); ?>" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"></span>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" class="minimal" id="languages_option1" name="languages_option1[]" value="Read" <?php echo set_checkbox('languages_option1', 'Read'); ?> />
                                    <label>Read</label>
                                    <input type="checkbox" class="minimal" id="languages_option1" name="languages_option1[]" value="Write" <?php echo set_checkbox('languages_option1', 'Write'); ?> />
                                    <label>Write</label>
                                    <input type="checkbox" class="minimal" id="languages_option1" name="languages_option1[]" value="Speak" <?php echo set_checkbox('languages_option1', 'Speak'); ?> />
                                    <label>Speak</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Languages Known III</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="languages_known2" name="languages_known2" placeholder="Languages Known" value="<?php echo set_value('languages_known2'); ?>" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"></span>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" class="minimal" id="languages_option2" name="languages_option2[]" value="Read" <?php echo set_checkbox('languages_option2', 'Read'); ?> />
                                    <label>Read</label>
                                    <input type="checkbox" class="minimal" id="languages_option2" name="languages_option2[]" value="Write" <?php echo set_checkbox('languages_option2', 'Write'); ?> />
                                    <label>Write</label>
                                    <input type="checkbox" class="minimal" id="languages_option2" name="languages_option2[]" value="Speak" <?php echo set_checkbox('languages_option2', 'Speak'); ?> />
                                    <label>Speak</label>
                                </div>
                            </div>

                            <?php /* <div class="form-group">
											<label for="roleid" class="col-sm-10 control-label">Have your ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification.&nbsp;<span style="color:#F00">*</span> </label>
											<div class="col-sm-2">
												<input type="radio" class="minimal declaration_yes" id="Yes"   name="declaration1"  required value="Yes" <?php echo set_radio('declaration1', 'Yes'); ?>>
												Yes
												<input type="radio" class="minimal declaration_no" id="No"  name="declaration1"  required value="No" <?php echo set_radio('declaration1', 'No'); ?>>
											No <span class="error"></span> </div>
										</div>
										
										<div class="form-group" id="declaration_note_div" style="display:none;">
											<label for="roleid" class="col-sm-4 control-label">Declaration Note <span style="color:#F00">*</span></label>
											<div class="col-sm-5">
												<input type="text" class="form-control" id="declaration_note" name="declaration_note" placeholder="Declaration Note"  value="<?php echo set_value('declaration_note');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="200" maxlength="200" required>
											<span class="error"></span> </div>
										(Max 200 Characters) </div> */ ?>

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">REFERENCES</h3>
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="text-align: left;" for="roleid" class="col-sm-12 control-label">Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)</label>
                            </div>

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Professional Reference I</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Name <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="refname_one" name="refname_one" placeholder="Name" value="<?php echo set_value('refname_one'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required>
                                    <span class="error">
                                        <?php //echo form_error('refname_one');
                                        ?>
                                    </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Complete Address<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <textarea rows="4" cols="50" class="form-control" id="refaddressline_one" name="refaddressline_one" placeholder="Complete Address" required data-parsley-maxlength="250" maxlength="250" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/"><?php echo set_value('refaddressline_one'); ?></textarea>

                                    <span class="error">
                                        <?php //echo form_error('refaddressline_one');
                                        ?>
                                    </span>
                                </div>
                                (Max 250 Characters)
                            </div>

                            <div class="form-group"><?php //Organisation (If employed) 
                                                    ?>
                                <label class="col-sm-4 control-label">Organisation (If employed) </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="reforganisation_one" name="reforganisation_one" placeholder="Organisation (If employed)" value="<?php echo set_value('reforganisation_one'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"></span>
                                </div>(Max 30 Characters)
                            </div>

                            <div class="form-group"><?php //Designation 
                                                    ?>
                                <label class="col-sm-4 control-label">Designation</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="refdesignation_one" name="refdesignation_one" placeholder="Designation" value="<?php echo set_value('refdesignation_one'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"></span>
                                </div>(Max 30 Characters)
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Email Id <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="refemail_one" name="refemail_one" placeholder="Email Id" data-parsley-type="email" value="<?php echo set_value('refemail_one'); ?>" data-parsley-maxlength="45" required data-parsley-trigger-after-failure="focusout">
                                    <span class="error">
                                        <?php //echo form_error('refemail_one');
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="tel" class="form-control" id="refmobile_one" name="refmobile_one" placeholder="Mobile Number" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('refmobile_one'); ?>" required data-parsley-trigger-after-failure="focusout">
                                    <span class="error">
                                        <?php //echo form_error('refmobile_one');
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Professional Reference II</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Name <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="refname_two" name="refname_two" placeholder="Name" value="<?php echo set_value('refname_two'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required>
                                    <span class="error">
                                        <?php //echo form_error('refname_one');
                                        ?>
                                    </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Complete Address<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <textarea rows="4" cols="50" class="form-control" id="refaddressline_two" name="refaddressline_two" placeholder="Complete Address" data-parsley-maxlength="250" maxlength="250" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/"><?php echo set_value('refaddressline_two'); ?></textarea>
                                    <span class="error">
                                        <?php //echo form_error('refaddressline_one');
                                        ?>
                                    </span>
                                </div>
                                (Max 250 Characters)
                            </div>

                            <div class="form-group"><?php //Organisation (If employed) 
                                                    ?>
                                <label class="col-sm-4 control-label">Organisation (If employed) </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="reforganisation_two" name="reforganisation_two" placeholder="Organisation (If employed)" value="<?php echo set_value('reforganisation_two'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"></span>
                                </div>(Max 30 Characters)
                            </div>

                            <div class="form-group"><?php //Designation 
                                                    ?>
                                <label class="col-sm-4 control-label">Designation</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="refdesignation_two" name="refdesignation_two" placeholder="Designation" value="<?php echo set_value('refdesignation_two'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                                    <span class="error"></span>
                                </div>(Max 30 Characters)
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Email Id <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="refemail_two" name="refemail_two" placeholder="Email Id" data-parsley-type="email" value="<?php echo set_value('refemail_two'); ?>" data-parsley-maxlength="45" required data-parsley-trigger-after-failure="focusout">
                                    <span class="error">
                                        <?php //echo form_error('refemail_two');
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="tel" class="form-control" id="refmobile_two" name="refmobile_two" placeholder="Mobile" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('refmobile_two'); ?>" required data-parsley-trigger-after-failure="focusout">
                                    <span class="error">
                                        <?php //echo form_error('refmobile_two');
                                        ?>
                                    </span>
                                </div>
                            </div>

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">PUBLICATION</h3>
                                </div>
                            </div>
                            <!-- <div class="box-title box-header"><strong>PUBLICATION</strong> </div> -->
                            <br />
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Publication of Books</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="publication_of_books" name="publication_of_books" placeholder="Publication of Books" value="<?php echo set_value('publication_of_books'); ?>" data-parsley-maxlength="1000" maxlength="1000" data-parsley-pattern="/^[a-zA-Z-0-9/ , ]+$/">
                                    <span class="error"></span>
                                </div>
                                (Max 1000 Characters)
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Publication of articles (give latest, not more than ten)</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="publication_of_articles" name="publication_of_articles" placeholder="Publication of articles" value="<?php echo set_value('publication_of_articles'); ?>" data-parsley-maxlength="1000" maxlength="1000" data-parsley-pattern="/^[a-zA-Z-0-9/ , ]+$/">
                                    <span class="error"></span>
                                </div>
                                (Max 1000 Characters)
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Area of Specialization</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="area_of_specialization" name="area_of_specialization" placeholder="Area of Specialization" value="<?php echo set_value('area_of_specialization'); ?>" data-parsley-maxlength="1000" maxlength="1000" data-parsley-pattern="/^[a-zA-Z-0-9/ , ]+$/">
                                    <span class="error"></span>
                                </div>
                                (Max 1000 Characters)
                            </div>

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">OTHER INFORMATION</h3>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Earliest date of joining if selected <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <textarea rows="4" cols="50" class="form-control" id="earliest_date_of_joining" name="earliest_date_of_joining" required placeholder="Earliest date of joining if selected" data-parsley-maxlength="500" maxlength="100" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/" data-parsley-trigger="change keyup"><?php echo set_value('earliest_date_of_joining'); ?></textarea>
                                    <span class="error"> </span>
                                </div>
                                (Max 100 Characters)
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Why do you consider yourself suitable for the post of CEO of this Institute <span style="color:#F00">*</span> </label>
                                <div class="col-sm-5">
                                    <textarea rows="4" cols="50" class="form-control" id="suitable_of_the_post_of_CEO" name="suitable_of_the_post_of_CEO" required placeholder="Why do you consider yourself suitable for the post of CEO of this Institute" data-parsley-maxlength="4000" maxlength="4000" data-parsley-trigger="change keyup"><?php echo set_value('suitable_of_the_post_of_CEO'); ?></textarea>
                                    <span class="error"> </span>
                                </div>
                                (Max 4000 Characters)
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Any other information that the candidate would like to add</label>
                                <div class="col-sm-5">
                                    <textarea rows="4" cols="50" class="form-control" id="comment" name="comment" placeholder="Any other information that the candidate would like to add" data-parsley-maxlength="4000" maxlength="4000" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/"><?php echo set_value('comment'); ?></textarea>
                                    <span class="error"> </span>
                                </div>
                                (Max 4000 Characters)
                            </div>
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">DECLARATION </h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label for="roleid" class="col-sm-4 control-label">Declaration&nbsp;<span style="color:#F00">*</span> </label> -->
                                <div class="col-sm-12" align="justify">
                                    <input type="checkbox" name="declaration2" id="declaration2" value="Yes" required <?php echo set_checkbox('declaration2', 'Yes'); ?>>
                                    &nbsp; I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criterias according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me. <span class="error"></span>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Declaration 2&nbsp;<span style="color:#F00">*</span> </label>
                                <div class="col-sm-8" align="justify">
                                    <input type="checkbox" name="declaration3" id="declaration3" value="Yes" required <?php echo set_checkbox('declaration3', 'Yes'); ?>>
                                    &nbsp; I hereby agree that any legal proceedings in respect of any matter or claims or disputes arising out of application or out of said advertisement can be instituted by me at Mumbai only and shall have sole and exclusive jurisdiction to try any cause / dispute. I undertake to abide by all the terms and conditions of the advertisement given by the Indian Institute of Banking & Finance. <span class="error"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Declaration 3&nbsp;<span style="color:#F00">*</span> </label>
                                <div class="col-sm-8" align="justify">
                                    <input type="checkbox" name="declaration4" id="declaration4" value="Yes" required <?php echo set_checkbox('declaration4', 'Yes'); ?>>
                                    &nbsp;I further certify that no disciplinary / vigilance proceedings are either pending or contemplated against me and that there are no legal cases pending against me. There have been no major / minor penalties <span class="error"></span>
                                </div>
                            </div> -->

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">UPLOAD PHOTO AND SIGNATURE</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Photo <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="file" name="scannedphoto" id="scannedphoto" required style="word-wrap: break-word;width: 100%;">
                                    <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                                    <div id="error_photo"></div>
                                    <br>
                                    <div id="error_photo_size"></div>
                                    <span >
                                        Note : Only JPEG or JPG format are allowed.
                                    </span> (Minimum size 25 KB & Maximum 100 KB)
                                </div>
                                <img id="image_upload_scanphoto_preview" height="100" width="100" />
                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Signature <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="file" name="scannedsignaturephoto" id="scannedsignaturephoto" required style="word-wrap: break-word;width: 100%;">
                                    <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                                    <div id="error_signature"></div>
                                    <br>
                                    <div id="error_signature_size"></div>
                                    <span class="signature_text" style="display:none;"></span> 
                                    <span >
                                        Note : Only JPEG or JPG format are allowed.
                                    </span> ( Minimum size 25 KB & Maximum 100 KB )
                                </div>
                                <img id="image_upload_sign_preview" height="100" width="100" />
                            </div>
                            <!--<div class="form-group">
											<label for="roleid" class="col-sm-4 control-label">Resume/CV <span style="color:#F00">*</span></label>
											<div class="col-sm-5">
											<input type="file" name="uploadcv" id="uploadcv" required>
											<input type="hidden" id="hiddenuploadcv" name="hiddenuploadcv">
											<div id="error_uploadcv"></div>
											<br>
											<div id="error_uploadcv_size"></div>
											<span class="uploadcv_text" style="display:none;"></span> <span class="error">
											<?php //echo form_error('uploadcv');
                                            ?>
											</span> ( Max Size 2MB | Allowed formats PDF and DOCX Only) </div>
										<!--<img id="image_upload_uploadcv_preview" height="100" width="100"/> -->
                            <!--<img src="<?php //echo base_url() 
                                            ?>/uploads/uploadcv/resume.png" height="100" width="100">-->
                            <!--</div>
										</div>-->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">PLACE AND DATE</h3>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Place<span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="place" name="place" placeholder="Place" required value="<?php echo set_value('place'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                                    <span class="error"> </span>
                                </div>
                                (Max 30 Characters)
                            </div>
                            <!-- <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Date <span style="color:#F00">*</span></label>
                                <div class="col-sm-8">
                                    <div class="date"> -->
                            <!-- Visible (readonly) field for showing the date -->
                            <!-- <input type="text" class="form-control" id="submit_date_display" placeholder="Date" readonly> -->

                            <!-- Hidden field for submitting actual date/time -->
                            <!-- <input type="hidden" id="submit_date" name="submit_date" value="">
                                    </div>
                                </div>
                            </div> -->

                            <div class="form-group">
                                <label for="roleid" class="col-sm-4 control-label">Date <span style="color:#F00">*</span></label>
                                <div class="col-sm-8">
                                    <div class="date">
                                        <!-- Visible (readonly) field for showing the date -->
                                        <input type="text" class="form-control" id="submit_date_display" placeholder="Date" readonly>

                                        <!-- Hidden field for submitting actual date -->
                                        <input type="hidden" id="submit_date" name="submit_date" value="">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- <div class="box box-info"> -->
                        <!-- 	<div class="form-group m_t_15">
								<label for="roleid" class="col-sm-3 control-label">Security Code <span style="color:#F00">*</span></label>
								<div class="col-sm-2">
									<input type="text" name="code" id="code" required class="form-control">
									<input type="hidden" name="captcha_session_name" id="captcha_session_name" value="Head_Pdc_Nz">
									<span class="error" id="captchaid" style="color:#B94A48;">
										<?php
                                        if (form_error('code')) {
                                            echo form_error('code');
                                        }
                                        ?>
									</span>
								</div>
								<div class="col-sm-3">
									<div id="captcha_img">
										<?php
                                        if (!empty($captcha_for_head_pdc_nz)) {
                                            echo $captcha_for_head_pdc_nz;
                                        } else {
                                            echo '<span style="color:red;">Captcha not loaded. Please reload the page.</span>';
                                        }
                                        ?>
									</div>
									<span class="error">
										<?php //echo form_error('code');
                                        ?>
									</span>
								</div>id="new_captcha"  -->
                        <!-- <div class="col-sm-2"> <a href="javascript:void(0);" onclick="refresh_captcha_img();" class="forget">Change Image</a> <span class="error">
										<?php //echo form_error('code');
                                        ?>
									</span> </div>
							</div> -->

                        <!-- <div class="form-group m_t_15">
                                    <td>Security Code *</td>
                                    <td class="security-code">
                                        <div style="display:inline-block; vertical-align:top;">
                                            <input type="text" name="code" id="captcha_code">
                                            <span class="error" id="err_captcha_code"></span>
                                        </div>

                                        <div id="captcha_img" style="display:inline-block; margin-left:10px;"></div>
                                        <button type="button" onclick="refresh_captcha_img()">Change Image</button>
                                    </td>
                                </div>

                                <div class="box-footer">
                                    <div class="col-sm-6 col-sm-offset-3"> <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return checkform();" id="preview">Preview and Proceed</a>
                                        <button type="reset" class="btn btn-default" name="btnReset" id="btnReset" onClick="window.location.reload()">Reset</button>
                                    </div>
                                </div>
                            </div> -->

                        <div class="box box-info">
                            <!-- <?php
                                    if (form_error('code')) {
                                        echo form_error('code');
                                    }
                                    ?>

                                <?php
                                if (!empty($captcha_for_head_pdc_nz)) {
                                    echo $captcha_for_head_pdc_nz;
                                } else {
                                    echo '<span style="color:red;">Captcha not loaded. Please reload the page.</span>';
                                }
                                ?> -->

                            <div class="form-group m_t_15" style="display:flex; align-items:center; gap:10px;">
                                <label for="captcha_code" class="col-sm-3 control-label" style="white-space:nowrap;">
                                    Security Code <span style="color:#F00">*</span>
                                </label>

                                <div style="display:flex; align-items:center; gap:10px;">
                                    <input type="text" name="code" id="captcha_code" class="form-control" style="width:120px;">
                                    <div id="captcha_img" style="display:inline-block;"></div>
                                    <button type="button" class="btn btn-default" onclick="refresh_captcha_img()">Change Image</button>
                                </div>

                                <span class="error" id="err_captcha_code" style="color:#B94A48; margin-left:10px;"></span>
                            </div>

                            <div class="box-footer" style="margin-top:20px;">
                                <div class="col-sm-6 col-sm-offset-3" style="text-align:center;">
                                    <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return checkform();" id="preview">Preview and Proceed</a>
                                    <button type="reset" class="btn btn-default" name="btnReset" id="btnReset" onClick="window.location.reload()">Reset</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        </section>
    </div>
    <div class="modal fade" id="confirm" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <p style="color:#F00"> <strong>VERY IMPORTANT</strong><br>
                        I confirm that the Photo, Signature images uploaded belongs to me and they are clear and readable.<br />
                        <br />
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<link href="<?php echo base_url(); ?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url(); ?>js/careers_validation.js?<?php echo time(); ?>"></script>
<script>
    $(function() {
        $("#head_pdc_nz_dob").dateDropdowns({
            submitFieldName: 'head_pdc_nz_dob',
            minAge: 54,
            maxAge: 60
        });
        // Set all hidden fields to type text for the demo
        //$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
    }); // 1959-07-31 to 1971-07-31



    // Set all hidden fields to type text for the demo
    //$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
    // 1959-07-31 to 1971-07-31
    function refresh_captcha_img() {
        $.post("<?= site_url('careers/generate_captcha_ajax'); ?>", {
            session_name: "Head_Pdc_Nz"
        }, function(res) {
            $('#captcha_img').html(res);
            $("#captcha_code").val("");
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        refresh_captcha_img();
    });

    // Also refresh captcha on pageshow (browser back/forward)
    document.addEventListener("pageshow", function(event) {
        refresh_captcha_img();
    });
    //Added date on 27-10-25

    document.addEventListener('DOMContentLoaded', function() {
        const yesRadio = document.getElementById('caiib_yes');
        const noRadio = document.getElementById('caiib_no');
        const detailsSection = document.getElementById('caiib_details');

        function toggleDetails() {
            if (yesRadio.checked) {
                detailsSection.style.display = 'block';
                // document.getElementById('year_of_passing').setAttribute('required', 'required');
                document.getElementById('membership_number').setAttribute('required', 'required');
            } else {
                detailsSection.style.display = 'none';
                // document.getElementById('year_of_passing').removeAttribute('required');
                document.getElementById('membership_number').removeAttribute('required');
            }
        }

        yesRadio.addEventListener('change', toggleDetails);
        noRadio.addEventListener('change', toggleDetails);

        // Ensure correct state on reload (if validation fails)
        toggleDetails();
    });
    // end


    $(document).ready(function() {
        $(".whether_in_service_yes").prop("checked", true).trigger("change");
        $(".vrs_register_date_div").hide();
        $(".reason_of_resign_div").hide();
        $(".no_service_div").hide();
        $(document).on("change", ".whether_in_service", function() {

            if ($(".whether_in_service_yes").prop("checked") == true) {

                $("#service_from_date").prop('required', true);
                $(".vrs_register_date_div").hide();
                $(".reason_of_resign_div").hide();
                $(".no_service_div").hide();

                $("#vrs_register_date").removeAttr("required");
                $("#reason_of_resign").removeAttr("required");

                $(".name_of_present_organization_div").show();
                $(".service_from_date_div").show();

                $(".comm_address_of_org_div").show();
                $(".curr_designation_div").show();
                $(".any_other_details_div").show();

                $("#name_of_present_organization").attr('required', true);
                $("#comm_address_of_org").attr('required', true);
                $("#curr_designation").attr('required', true);
                //	$("#any_other_details").attr('required',true);
            } else if ($(".whether_in_service_no").prop("checked") == true) {
                $("#service_from_date").prop('required', false);
                $(".no_service_div").show();
                $(".vrs_register_date_div").show();
                $(".reason_of_resign_div").show();
                $("#vrs_register_date").attr('required', true);
                $("#reason_of_resign").attr('required', true);

                $(".name_of_present_organization_div").hide();
                $(".comm_address_of_org_div").hide();
                $(".curr_designation_div").hide();
                $(".any_other_details_div").hide();
                $(".service_from_date_div").hide();


                $("#name_of_present_organization").removeAttr("required");
                $("#comm_address_of_org").removeAttr("required");
                $("#curr_designation").removeAttr("required");
                $("#any_other_details").removeAttr("required");

            }
        });

        $(document).on("change", "#languages_known1", function() {
            if ($("#languages_known1").val() != "") {
                if ($("#languages_option1").prop("checked") == false) {
                    $("#languages_option1").attr('required', true);
                } else {
                    $("#languages_option1").removeAttr("required");
                    //$('#languages_option1').prop('checked', false);
                }
            } else {
                $("#languages_option1").removeAttr("required");
                //$('#languages_option1').prop('checked', false);
            }
        });
        $(document).on("change", "#languages_known2", function() {
            if ($("#languages_known2").val() != "") {
                if ($("#languages_option2").prop("checked") == false) {
                    $("#languages_option2").prop('required', true);
                } else {
                    $("#languages_option2").removeAttr("required");
                    //$('#languages_option2').prop('checked', false);
                }
            } else {
                $("#languages_option2").removeAttr("required");
                //$('#languages_option2').prop('checked', false);
            }
        });

        if ($("#declaration_note").val() != "") {
            $("#declaration_note_div").show();
        } else {
            $("#declaration_note").removeAttr("required");
        }

        $(document).on("click", ".declaration_yes", function() {
            //$("#declaration_note").attr("required");
            $("#declaration_note").prop('required', true);
            $("#declaration_note_div").show();
        });
        $(document).on("click", ".declaration_no", function() {
            $("#declaration_note").removeAttr("required");
            $("#declaration_note").val("");
            $("#declaration_note_div").hide();
        });

        // setTimeout(function() {
        //     $('#job_add').trigger('click');
        //     $('#job_add').trigger('click');
        //     $('#job_add').trigger('click');
        //     $('#job_add').trigger('click');

        // }, 1000);

        $('#job_add').click(function() {
            var i = $('#org_add').val();
            //alert(i);
            i++;
            if (i <= 8) {
                $('#job_dynamic_field').append('<div id="job_row' + i + '"><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Name of the Organisation<span style="color:#F00">*</span></label><div class="col-sm-5"><input type="text" class="form-control" required id="organization" name="organization[]" placeholder="Name of the Organisation" value=""  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"><span class="error"></span></div>(Max 30 Characters)</div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Period<span style="color:#F00">*</span></label><div class="col-sm-6"><div class="col-sm-3 date"><input type="text" required class="form-control job_from_date" id="job_from_date" name="job_from_date[]" placeholder="From Date" value="" readonly></div><div class="col-sm-3 date"><input type="text" class="form-control job_to_date" id="job_to_date" name="job_to_date[]" placeholder="To Date" required value="" readonly></div></div></div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Designation/Post Held<span style="color:#F00">*</span></label><div class="col-sm-5"><input type="text" class="form-control" id="designation" name="designation[]" required placeholder="Designation"  value=""  data-parsley-maxlength="40" maxlength="40" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"><span class="error"></span></div>(Max 40 Characters) </div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Responsibilities<span style="color:#F00">*</span></label><div class="col-sm-5"><input type="text" required class="form-control" id="responsibilities" name="responsibilities[]" placeholder="Responsibilities"  value=""  data-parsley-maxlength="300" maxlength="300" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"><span class="error"></span> </div>(Max 300 Characters) </div><a href="javascript:void(0);" name="btn_remove_job"  id="' + i + '" class="btn btn-danger btn_remove_job">Remove Employment Details</a></div><hr class="co-md-12">');
                $('#org_add').val(i);

                if (i <= 6) {
                    // $('#job_row' + i + '').find('.btn_remove_job').remove();
                }
            } else {
                alert('The Maximum Limit is 7 to add Employments');
            }
        });
        $(document).on('click', '.btn_remove_job', function() {
            //$('.btn_remove_job').click(function(){
            //alert('in');
            var job_button_id = $(this).attr("id");
            var i = $('#org_add').val();
            //alert(i);
            i--;
            //alert(i)
            if (i == 0) {
                i++;
            }
            $('#org_add').val(i);
            //alert($('#org_add').val());
            $("#job_row" + job_button_id + "").next().remove();
            $("#job_row" + job_button_id + "").remove();
        });

        //});

        var today = new Date();

        $('#vrs_register_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: today,
            autoclose: true,
            forceParse: true
        });
        $('#submit_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: today,
            autoclose: true,
            forceParse: true
        });

        // $('#year_of_passing').datepicker({
        //     format: 'yyyy',
        //     viewMode: "years",
        //     minViewMode: "years",
        //     endDate: today,
        //     autoclose: true,
        //     forceParse: true
        // });

        $('.from_date').datepicker({
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.to_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('.to_date').datepicker({
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.from_date').datepicker('setEndDate', new Date($(this).val()));
        });


        $('.ph_from_date').datepicker({
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.ph_to_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('.ph_to_date').datepicker({
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.ph_from_date').datepicker('setEndDate', new Date($(this).val()));
        });

        $('.from_date').datepicker({
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.phd_to_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('.phd_to_date').datepicker({
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.from_date').datepicker('setEndDate', new Date($(this).val()));
        });

        $('#ess_from_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('#ess_to_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('#ess_to_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('#ess_from_date').datepicker('setEndDate', new Date($(this).val()));
        });

        $('#post_gra_from_date').datepicker({
            format: 'yyyy',
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('#post_gra_to_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('#post_gra_to_date').datepicker({
            format: 'yyyy',
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('#post_gra_from_date').datepicker('setEndDate', new Date($(this).val()));
        });

        // $('#exp_faculty_from_date').datepicker({
        //     format: 'yyyy-mm-dd',
        //     endDate: today,
        //     autoclose: true,
        //     forceParse: true
        // }).on('changeDate', function() {
        //     $('#exp_faculty_to_date').datepicker('setStartDate', new Date($(this).val()));
        // });

        // $('#exp_faculty_to_date').datepicker({
        //     format: 'yyyy-mm-dd',
        //     endDate: today,
        //     autoclose: true,
        //     forceParse: true
        // }).on('changeDate', function() {
        //     $('#exp_faculty_from_date').datepicker('setEndDate', new Date($(this).val()));
        // });

        // $('#cer_from_date').datepicker({
        //     format: 'yyyy-mm-dd',
        //     endDate: today,
        //     autoclose: true,
        //     forceParse: true
        // }).on('changeDate', function() {
        //     $('#cer_to_date').datepicker('setStartDate', new Date($(this).val()));
        // });

        // $('#cer_to_date').datepicker({
        //     format: 'yyyy-mm-dd',
        //     endDate: today,
        //     autoclose: true,
        //     forceParse: true
        // }).on('changeDate', function() {
        //     $('#cer_from_date').datepicker('setEndDate', new Date($(this).val()));
        // });

        // 1. Define 'today' (set to midnight of the current day)
var today = new Date(); 
today.setHours(0, 0, 0, 0); 



    // Initialize all datepickers (only once)
    $(document).on('focus', '.qualification-from-date', function () {
        if (!$(this).data('datepicker')) {
            $(this).datepicker({
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                endDate: today,
                autoclose: true,
                forceParse: true
            }).on('changeDate', function (e) {
                // var $group = $(this).closest('.form-group');
                // var $toDatePicker = $group.find('.qualification-to-date');
                // var selectedDate = e.date; // directly use event's Date object

                // if ($toDatePicker.length) {
                //     // set start date of 'To Date'
                //     $toDatePicker.datepicker('setStartDate', selectedDate);
                // }
            });
        }
    });

    // Initialize all To Date pickers (only once)
    $(document).on('focus', '.qualification-to-date', function () {
        if (!$(this).data('datepicker')) {
            $(this).datepicker({
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                endDate: today,
                autoclose: true,
                forceParse: true
            }).on('changeDate', function (e) {
                // var $group = $(this).closest('.form-group');
                // var $fromDatePicker = $group.find('.qualification-from-date');
                // var selectedDate = e.date;

                // if ($fromDatePicker.length) {
                //     // set end date of 'From Date'
                //     $fromDatePicker.datepicker('setEndDate', selectedDate);
                // }
            });
        }
    });



    // Initialize all datepickers (only once)
    // Helper: destroy any old datepicker and re-init with correct format
    function reinitDatepicker($el, options) {
        $el.datepicker('destroy'); // remove old initialization (important)
        $el.datepicker(options);
    }


    // From Date
    $(document).on('focus', '.org_from_date', function () {
        var $this = $(this);

        // Always ensure correct format initialization
        reinitDatepicker($this, {
            format: 'dd-mm-yyyy',
            endDate: today,
            autoclose: true,
            forceParse: true
        });

        $this.off('changeDate').on('changeDate', function (e) {
            var $group = $(this).closest('.form-group');
            var $toDatePicker = $group.find('.org_to_date');
            var selectedDate = e.date;

            if ($toDatePicker.length) {
                reinitDatepicker($toDatePicker, {
                    format: 'dd-mm-yyyy',
                    endDate: today,
                    autoclose: true,
                    forceParse: true,
                    startDate: selectedDate
                });
            }
        });
    });

    // To Date
    $(document).on('focus', '.org_to_date', function () {
        var $this = $(this);

        // Always ensure correct format initialization
        reinitDatepicker($this, {
            format: 'dd-mm-yyyy',
            endDate: today,
            autoclose: true,
            forceParse: true
        });

        $this.off('changeDate').on('changeDate', function (e) {
            var $group = $(this).closest('.form-group');
            var $fromDatePicker = $group.find('.org_from_date');
            var selectedDate = e.date;

            if ($fromDatePicker.length) {
                reinitDatepicker($fromDatePicker, {
                    format: 'dd-mm-yyyy',
                    endDate: selectedDate,
                    autoclose: true,
                    forceParse: true
                });
            }
        });
    });


    $(document).on('focus', '.exp_faculty_from_date', function () {
        var $this = $(this);

        // Always ensure correct format initialization
        reinitDatepicker($this, {
            format: 'dd-mm-yyyy',
            endDate: today,
            autoclose: true,
            forceParse: true
        });

        $this.off('changeDate').on('changeDate', function (e) {
            var $group = $(this).closest('.form-group');
            var $toDatePicker = $group.find('.exp_faculty_to_date');
            var selectedDate = e.date;

            if ($toDatePicker.length) {
                reinitDatepicker($toDatePicker, {
                    format: 'dd-mm-yyyy',
                    endDate: today,
                    autoclose: true,
                    forceParse: true,
                    startDate: selectedDate
                });
            }
        });
    });

    // To Date
    $(document).on('focus', '.exp_faculty_to_date', function () {
        var $this = $(this);

        // Always ensure correct format initialization
        reinitDatepicker($this, {
            format: 'dd-mm-yyyy',
            endDate: today,
            autoclose: true,
            forceParse: true
        });

        $this.off('changeDate').on('changeDate', function (e) {
            var $group = $(this).closest('.form-group');
            var $fromDatePicker = $group.find('.exp_faculty_from_date');
            var selectedDate = e.date;

            if ($fromDatePicker.length) {
                reinitDatepicker($fromDatePicker, {
                    format: 'dd-mm-yyyy',
                    endDate: selectedDate,
                    autoclose: true,
                    forceParse: true
                });
            }
        });
    });


    // From Date
    $(document).on('focus', '.exp_from_date', function () {
        var $this = $(this);

        // Always ensure correct format initialization
        reinitDatepicker($this, {
            format: 'dd-mm-yyyy',
            endDate: today,
            autoclose: true,
            forceParse: true
        });

        $this.off('changeDate').on('changeDate', function (e) {
            var $group = $(this).closest('.form-group');
            var $toDatePicker = $group.find('.exp_to_date');
            var selectedDate = e.date;

            if ($toDatePicker.length) {
                reinitDatepicker($toDatePicker, {
                    format: 'dd-mm-yyyy',
                    endDate: today,
                    autoclose: true,
                    forceParse: true,
                    startDate: selectedDate
                });
            }
        });
    });

    // To Date
    $(document).on('focus', '.exp_to_date', function () {
        var $this = $(this);

        // Always ensure correct format initialization
        reinitDatepicker($this, {
            format: 'dd-mm-yyyy',
            endDate: today,
            autoclose: true,
            forceParse: true
        });

        $this.off('changeDate').on('changeDate', function (e) {
            var $group = $(this).closest('.form-group');
            var $fromDatePicker = $group.find('.exp_from_date');
            var selectedDate = e.date;

            if ($fromDatePicker.length) {
                reinitDatepicker($fromDatePicker, {
                    format: 'dd-mm-yyyy',
                    endDate: selectedDate,
                    autoclose: true,
                    forceParse: true
                });
            }
        });
    });


        $(document).on("click", "#add", function() {
            $('.from_date').datepicker({
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                endDate: today,
                autoclose: true,
                forceParse: true
            }).on('changeDate', function() {
                $('.to_date').datepicker('setStartDate', new Date($(this).val()));
            });
        });

        $(document).on("click", "#add", function() {
            $('.to_date').datepicker({
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                endDate: today,
                autoclose: true,
                forceParse: true
            }).on('changeDate', function() {
                $('.from_date').datepicker('setEndDate', new Date($(this).val()));
            });
        });

        $(document).on("click", "#add", function() {
            $('.from_date').datepicker({
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                endDate: today,
                autoclose: true,
                forceParse: true
            }).on('changeDate', function() {
                $('.phd_to_date').datepicker('setStartDate', new Date($(this).val()));
            });
        });

        $(document).on("click", "#add", function() {
            $('.phd_to_date').datepicker({
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                endDate: today,
                autoclose: true,
                forceParse: true
            }).on('changeDate', function() {
                $('.from_date').datepicker('setEndDate', new Date($(this).val()));
            });
        });


        $('#pro_from_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '+0d',
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('#pro_to_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('#pro_to_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '+0d',
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('#pro_from_date').datepicker('setEndDate', new Date($(this).val()));
        });


        $('.vrs_register_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.vrs_register_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('.service_from_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.service_from_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('.job_from_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.job_to_date').datepicker('setStartDate', new Date($(this).val()));
        });

        $('.job_to_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: today,
            autoclose: true,
            forceParse: true
        }).on('changeDate', function() {
            $('.job_from_date').datepicker('setEndDate', new Date($(this).val()));
        });

        $(document).on("click", "#job_add", function() {
            $('.job_from_date').datepicker({
                format: 'yyyy-mm-dd',
                endDate: today,
                autoclose: true,
                forceParse: true
            }).on('changeDate', function() {
                $('.job_to_date').datepicker('setStartDate', new Date($(this).val()));
            });
        });

        $(document).on("click", "#job_add", function() {
            $('.job_to_date').datepicker({
                format: 'yyyy-mm-dd',
                endDate: today,
                autoclose: true,
                forceParse: true
            }).on('changeDate', function() {
                $('.job_from_date').datepicker('setEndDate', new Date($(this).val()));
            });
        });

        var dtable = $('.dataTables-example').DataTable();
        $("#head_pdc_nz_dob").change(function() {
            var sel_dob = $("#head_pdc_nz_dob").val();
            if (sel_dob != '') {
                var dob_arr = sel_dob.split('-');
                if (dob_arr.length == 3) {
                    chkage(dob_arr[2], dob_arr[1], dob_arr[0]);
                } else {
                    alert('Select valid date');
                }
            }
        });

        var dt = new Date();
        dt.setFullYear(new Date().getFullYear() - 18);

        if ($('#hiddenphoto').val() != '') {
            $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
        }
        if ($('#hiddenscansignature').val() != '') {
            $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
        }
        if ($('#hiddenuploadcv').val() != '') {
            $('#image_upload_uploadcv_preview').attr('src', $('#hiddenuploadcv').val());
        }

        statecode = $("#state option:selected").val();

        if (statecode != '') {
            if (statecode == 'ASS' || statecode == 'JAM' || statecode == 'MEG') {
                document.getElementById('mendatory_state').style.display = "none";
                //document.getElementById('non_mendatory_state').style.display = "block";
                $("#aadhar_card").removeAttr("required");
            } else {
                document.getElementById('mendatory_state').style.display = "block";
                document.getElementById('mendatory_state').innerHTML = "*";
                //document.getElementById('non_mendatory_state').style.display = "none";
                $("#aadhar_card").attr("required", "true");
            }
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


        // $('#new_captcha').click(function(event) {
        // 	event.preventDefault();
        // 	$.ajax({
        // 		type: 'POST',
        // 		url: site_url + 'Careers/generatecaptchaajax/',
        // 		success: function(res) {
        // 			if (res != '') {
        // 				$('#captcha_img').html(res);
        // 			}
        // 		}
        // 	});
        // });

        /* $(document).keydown(function(event) {
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

    function sameAsAbove(fill) {


        // Array of all permanent address field IDs
    var permanentFields = [
        'addressline1_pr',
        'addressline2_pr',
        'addressline3_pr',
        'addressline4_pr',
        'district_pr',
        'city_pr',
        'state_pr',
        'pincode_pr',
        'contact_number_pr'
    ];

        if (fill.same_as_above.checked == true) {
            fill.addressline1_pr.value = fill.addressline1.value;
            fill.addressline2_pr.value = fill.addressline2.value;
            fill.addressline3_pr.value = fill.addressline3.value;
            fill.addressline4_pr.value = fill.addressline4.value;
            fill.district_pr.value = fill.district.value;
            fill.city_pr.value = fill.city.value;
            fill.state_pr.value = fill.state.value;
            fill.pincode_pr.value = fill.pincode.value;
            fill.contact_number_pr.value = fill.contact_number.value;

            // **CRITICAL FIX: Update Parsley validation and trigger change event**
        permanentFields.forEach(function(fieldId) {
            var $field = $('#' + fieldId);
            
            // 1. Reset Parsley state to remove any previous errors
            if ($field.parsley) {
                $field.parsley().reset();
            }
            
            // 2. For select fields (like state_pr), trigger the change event 
            // to ensure any dependent logic/validation is updated.
            if ($field.is('select')) {
                $field.trigger('change');
            }
        });

        } else {
            fill.addressline1_pr.value = '';
            fill.addressline2_pr.value = '';
            fill.addressline3_pr.value = '';
            fill.addressline4_pr.value = '';
            fill.district_pr.value = '';
            fill.city_pr.value = '';
            fill.state_pr.value = '';
            fill.pincode_pr.value = '';
            fill.contact_number_pr.value = '';

            // **CRITICAL FIX: Update Parsley validation and trigger change event**
        permanentFields.forEach(function(fieldId) {
            var $field = $('#' + fieldId);
            
            // 1. Reset Parsley state to remove any previous errors
            if ($field.parsley) {
                $field.parsley().reset();
                $field.trigger('change');
            }
            
            // 2. For select fields (like state_pr), trigger the change event 
            // to ensure any dependent logic/validation is updated.
            if ($field.is('select')) {
                $field.trigger('change');
            }
        });

        }
    }

    // function refresh_captcha_img() {
    // 	//$(".loading").show();
    // 	$.ajax({
    // 		type: 'POST',
    // 		url: site_url + 'Careers/generate_captcha_head_pdc_nz_ajax/',
    // 		data: {
    // 			"session_name": "Head_Pdc_Nz"
    // 		},
    // 		async: false,
    // 		success: function(res) {
    // 			if (res != '') {
    // 				$('#captcha_img').html(res);
    // 				$("#code").val("");
    // 				$("#captcha_code-error").html("");
    // 			}
    // 			//$(".loading").hide();
    // 		}
    // 	});
    // }

    // $('#usersAddForm').on('submit', function(e) {
    // 	e.preventDefault(); // prevent default submit

    // 	var code = $('#captcha_code').val();
    // 	var session_name = 'Head_Pdc_Nz';

    // 	$.post("<?= site_url('careers/check_captcha_code_ajax'); ?>", {
    // 		captcha_code: code,
    // 		session_name: session_name
    // 	}, function(response) {
    // 		if (response === 'true') {
    // 			// captcha is correct, submit the form
    // 			$('#usersAddForm')[0].submit();
    // 		} else {
    // 			// show error
    // 			$('#err_captcha_code').text('Invalid captcha code.');
    // 			refresh_captcha_img(); // refresh captcha
    // 		}
    // 	});
    // });

    // $('#usersAddForm').on('submit', function(e) {
    // 	e.preventDefault(); // stop default for now

    // 	var code = $('#captcha_code').val();
    // 	var session_name = 'Head_Pdc_Nz';
    // 	var form = this; // reference to the form

    // 	$.post("<?= site_url('careers/check_captcha_code_ajax'); ?>", {
    // 		captcha_code: code,
    // 		session_name: session_name
    // 	}, function(response) {
    // 		if (response === 'true') {
    // 			// captcha correct — submit the form via JS
    // 			form.submit(); // this submits the form normally
    // 		} else {
    // 			// captcha wrong — show error and refresh
    // 			$('#err_captcha_code').text('Invalid captcha code.');
    // 			refresh_captcha_img();
    // 		}
    // 	});
    // });

    // Gaurav start code

    $('#orgnsn_add').click(function() {
        var i = $('#orgnsn_add').val();
        //alert(i);
        i++;
        if (i <= 4) {
            $('#orgnsn_dynamic_field').append('<div id="orgnsn_row' + i + '"><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Name of the Organisation</label><div class="col-sm-5"><input type="text" class="form-control" id="org_organization" name="org_organization[]" placeholder="Name of the Organisation" value="" data-parsley-maxlength="30" maxlength="30" data-parsley-trigger="change keyup" ><span class="error"></span></div>(Max 30 Characters)</div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Period</label><div class="col-sm-6"><div class="col-sm-3 date"><input type="text" required class="form-control org_from_date" id="org_from_date" name="org_from_date[]" placeholder="From Date" value="" readonly></div><div class="col-sm-3 date"><input type="text" class="form-control org_to_date" id="org_to_date" name="org_to_date[]" placeholder="To Date" required value="" readonly></div></div></div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Designation</label><div class="col-sm-5"><input type="text" class="form-control" id="org_designation" name="org_designation[]" required placeholder="Designation" value="" maxlength="40" data-parsley-maxlength="40" data-parsley-trigger="change keyup"><span class="error"></span></div>(Max 40 Characters) </div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Responsibilities</label><div class="col-sm-5"><input type="text" required class="form-control" id="org_responsibilities" name="org_responsibilities[]" placeholder="Responsibilities"  value=""  data-parsley-maxlength="300" maxlength="300" data-parsley-trigger="change keyup"><span class="error"></span> </div>(Max 300 Characters) </div><a href="javascript:void(0);" name="btn_remove_orgnsn"  id="' + i + '" class="btn btn-danger btn_remove_orgnsn">Remove Organisation Details</a></div><hr class="co-md-12">');
            $('#orgnsn_add').val(i);

            if (i <= 6) {
                // $('#orgnsn_row' + i + '').find('.btn_remove_job').remove();
            }
        } else {
            alert('The Maximum Limit is 5 to add Organisation');
        }
    });

    $(document).on('click', '.btn_remove_orgnsn', function() {
        // $('.btn_remove_job').click(function(){
        // alert('in');
        var orgnsn_button_id = $(this).attr("id");
        var i = $('#orgnsn_add').val();
        // alert(i);
        i--;
        // alert(i)
        if (i == 0) {
            i++;
        }
        $('#orgnsn_add').val(i);
        //alert($('#org_add').val());
        $("#orgnsn_row" + orgnsn_button_id + "").next().remove();
        $("#orgnsn_row" + orgnsn_button_id + "").remove();
    });

    $('#faculty_exp_add').click(function() {
        var i = $('#faculty_exp_add').val();
        //alert(i);
        i++;
        if (i <= 4) {
            $('#faculty_exp_dynamic_field').append('<div id="faculty_exp_row' + i + '"><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Name of the Organisation<span style="color:#F00"></span></label><div class="col-sm-5"><input type="text" class="form-control" id="exp_org_organization" name="exp_org_organization[]" placeholder="Name of the Organisation" value=""  data-parsley-maxlength="30" maxlength="30" data-parsley-trigger="change keyup"><span class="error"></span></div>(Max 30 Characters)</div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Period<span style="color:#F00"></span></label><div class="col-sm-6"><div class="col-sm-3 date"><input type="text" class="form-control exp_from_date" id="exp_from_date" name="exp_job_from_date[]" placeholder="From Date" value="" readonly></div><div class="col-sm-3 date"><input type="text" class="form-control exp_to_date" id="exp_to_date" name="exp_job_to_date[]" placeholder="To Date" value="" readonly></div></div></div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Designation/Post Held<span style="color:#F00"></span></label><div class="col-sm-5"><input type="text" class="form-control" id="exp_designation" name="exp_designation[]" placeholder="Designation"  value=""  data-parsley-maxlength="40" maxlength="40" data-parsley-trigger="change keyup"><span class="error"></span></div>(Max 40 Characters) </div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Responsibilities<span style="color:#F00"></span></label><div class="col-sm-5"><input type="text" class="form-control" id="exp_responsibilities" name="exp_responsibilities[]" placeholder="Responsibilities"  value="" data-parsley-maxlength="300" maxlength="300" data-parsley-trigger="change keyup"><span class="error"></span> </div>(Max 300 Characters) </div><a href="javascript:void(0);" name="btn_remove_faculty_exp"  id="' + i + '" class="btn btn-danger btn_remove_faculty_exp">Remove Experience Details</a></div><hr class="co-md-12">');
            $('#faculty_exp_add').val(i);

            if (i <= 6) {
                // $('#faculty_exp_row' + i + '').find('.btn_remove_job').remove();
            }
        } else {
            alert('The Maximum Limit is 5 to add Experice');
        }
    });

    $(document).on('click', '.btn_remove_faculty_exp', function() {
        // $('.btn_remove_job').click(function(){
        // alert('in');
        var faculty_exp_button_id = $(this).attr("id");
        var i = $('#faculty_exp_add').val();
        // alert(i);
        i--;
        // alert(i)
        if (i == 0) {
            i++;
        }
        $('#faculty_exp_add').val(i);
        //alert($('#org_add').val());
        $("#faculty_exp_row" + faculty_exp_button_id + "").next().remove();
        $("#faculty_exp_row" + faculty_exp_button_id + "").remove();
    });

    $('#eduqual_add').click(function() {
        var i = $('#eduqual_add').val();
        i++;
        if (i <= 4) {
            $('#eduqual_dynamic_field').append('<div id="eduqual_row' + i + '"> <div class="form-group"><label for="roleid" class="col-sm-4 control-label">Qualification </label><div class="col-sm-5"><input type="text" class="form-control" id="cer_qua_name" name="cer_qua_name[]" placeholder="Qualification" value="" data-parsley-maxlength="50" maxlength="50" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"></div></div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Name of the Subject </label><div class="col-sm-5"><input type="text" class="form-control" id="cer_gra_sub" name="cer_gra_sub[]" placeholder="Name of the Subject" value="" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"><span class="error"></span></div></div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">College/Institution </label><div class="col-sm-5"><input type="text" class="form-control" id="cer_college_name" name="cer_college_name[]" placeholder="College/Institution" value="" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"><span class="error"></span></div>(Max 30 Characters)</div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">University</label><div class="col-sm-5"><input type="text" class="form-control" id="cer_university" name="cer_university[]" placeholder="University" value="" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"><span class="error"></span></div>(Max 30 Characters)</div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Period</label><div class="col-sm-6"><div class="col-sm-3 date"><input type="text" class="form-control qualification-from-date" id="cer_from_date" name="cer_from_date[]" placeholder="From Date" value="" readonly></div><div class="col-sm-3 date"><input type="text" class="form-control qualification-to-date" id="cer_to_date" name="cer_to_date[]" placeholder="To Date" value="" readonly></div></div></div><div class="form-group"><label class="col-sm-4 control-label">Percentage Obtained </label><div class="col-sm-5"><input type="number" class="form-control" id="cer_percentage" name="cer_percentage[]" placeholder="Percentage Obtained" value="" data-parsley-maxlength="20" step="0.01" data-parsley-type="number" max="100"><span class="error"></span></div></div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Class/Grade </label><div class="col-sm-5"><input type="text" class="form-control" id="cer_class" name="cer_class[]" placeholder="Class/Grade" value="" data-parsley-maxlength="50" maxlength="50" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required></div></div> <a href="javascript:void(0);" name="btn_remove_eduqual"  id="' + i + '" class="btn btn-danger btn_remove_eduqual">Remove Qualification</a></div><hr class="co-md-12">');
            
            $('#eduqual_add').val(i);

            if (i <= 6) {
                // $('#faculty_exp_row' + i + '').find('.btn_remove_job').remove();
            }
        } else {
            alert('The Maximum Limit is 5 to add qualification');
        }
    });

    $(document).on('click', '.btn_remove_eduqual', function() {
        // $('.btn_remove_job').click(function(){
        // alert('in');
        var eduqual_button_id = $(this).attr("id");
        var i = $('#eduqual_add').val();
        // alert(i);
        i--;
        // alert(i)
        if (i == 0) {
            i++;
        }
        $('#eduqual_add').val(i);
        //alert($('#org_add').val());
        $("#eduqual_row" + eduqual_button_id + "").next().remove();
        $("#eduqual_row" + eduqual_button_id + "").remove();
    });



    $('#add_fac_exp_add').click(function() {
        var i = $('#add_fac_exp_add').val();
        i++;
        if (i <= 5) {
            $('#add_fac_exp_dynamic_field').append('<div id="add_fac_exp_row' + i + '"> <div class="form-group"><label for="roleid" class="col-sm-4 control-label">Experience as Faculty</label><div class="col-sm-5"><textarea rows="4" cols="50" class="form-control" id="exp_in_bank" name="exp_in_bank[]" placeholder="Experience as Faculty" data-parsley-maxlength="1000" maxlength="1000" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/" data-parsley-trigger="change keyup"></textarea></div>(Max 1000 Characters)</div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Period<span style="color:#F00">*</span></label><div class="col-sm-6"><div class="col-sm-3 date"><input type="text" class="form-control exp_faculty_from_date" id="exp_faculty_from_date" name="exp_faculty_from_date[]" placeholder="From Date" readonly value=""></div><div class="col-sm-3 date"><input type="text" class="form-control exp_faculty_to_date" id="exp_faculty_to_date" name="exp_faculty_to_date[]" placeholder="To Date" readonly value="" readonly></div></div></div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Subjects Handled</label><div class="col-sm-5"><textarea rows="4" cols="50" class="form-control" id="subject_handled" name="subject_handled[]" placeholder="Subjects Handled" data-parsley-maxlength="1000" maxlength="500"  data-parsley-trigger="change keyup"></textarea></div>(Max 500 Characters)</div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Area of Specialization</label><div class="col-sm-5"><div class="col-sm-10"><input type="text" class="form-control" id="exp_in_functional_area" name="exp_in_functional_area[]" placeholder="Area of Specialization" value=""></div></div></div><div class="form-group"><label for="roleid" class="col-sm-4 control-label">Membership of Professional Associations</label><div class="col-sm-5"><textarea rows="4" cols="50" class="form-control" id="professional_ass" name="professional_ass[]" placeholder="Membership of Professional Associations" data-parsley-maxlength="500" maxlength="500" data-parsley-pattern="/^[a-zA-Z-0-9 , / ]+$/" data-parsley-trigger="change keyup"></textarea></div>(Max 500 Characters)</div><a href="javascript:void(0);" name="btn_remove_add_fac_exp"  id="' + i + '" class="btn btn-danger btn_remove_add_fac_exp">Remove Faculty Experience Details</a></div><hr class="co-md-12">');
            
            $('#add_fac_exp_add').val(i);

            if (i <= 6) {
                // $('#faculty_exp_row' + i + '').find('.btn_remove_job').remove();
            }
        } else {
            alert('The Maximum Limit is 5 to add faculty experience');
        }
    });

    $(document).on('click', '.btn_remove_add_fac_exp', function() {
        // $('.btn_remove_job').click(function(){
        // alert('in');
        var add_fac_exp_button_id = $(this).attr("id");
        var i = $('#add_fac_exp_add').val();
        // alert(i);
        i--;
        // alert(i)
        if (i == 0) {
            i++;
        }
        $('#add_fac_exp_add').val(i);
        //alert($('#org_add').val());
        $("#add_fac_exp_row" + add_fac_exp_button_id + "").next().remove();
        $("#add_fac_exp_row" + add_fac_exp_button_id + "").remove();
    });



    // Get current date and time in desired format (dd-mm-yyyy hh:mm:ss)
    document.addEventListener("DOMContentLoaded", function() {
        let now = new Date();
        let day = String(now.getDate()).padStart(2, '0');
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let year = now.getFullYear();

        // Format: DD-MM-YYYY
        let formattedDate = `${day}-${month}-${year}`;

        // Set value in visible and hidden inputs
        document.getElementById('submit_date_display').value = formattedDate;
        document.getElementById('submit_date').value = formattedDate;
    });


    $(document).ready(function() {
        // Function to handle the visibility and validation status
        function toggleSpouseField() {
            var maritalStatus = $('#marital_status').val();
            var $spouseField = $('#spouse_name');
            var $spouseStar = $('#spouse_required_star');
            
            // Define the statuses that require the spouse's name
            var requiredStatuses = ['Married', 'Widowed', 'Divorced'];

            if (requiredStatuses.includes(maritalStatus)) {
                // SHOW: For Married, Widowed, Divorced
                $spouseStar.show();
                $spouseField.prop('required', true);
                
                // Re-validate the field using Parsley (if used)
                if ($spouseField.parsley) {
                    $spouseField.parsley().validate();
                }

            } else {
                // HIDE: For Single or -Select-
                $spouseStar.hide();
                $spouseField.prop('required', false);
                
                // Important: Remove error messages and reset Parsley validation state
                if ($spouseField.parsley) {
                    $spouseField.removeClass('parsley-error').parsley().reset();
                }
            }
        }

        // 1. Bind the function to the change event of the select box
        $('#marital_status').on('change', toggleSpouseField);

        // 2. Run the function once on page load to set the initial state 
        // (useful if the form data is being pre-filled with PHP's set_value)
        toggleSpouseField(); 
    });


    $(function() {
  // Initialize Parsley with 'trigger' option
  $('form').parsley({
        trigger: 'change keyup'
      });
    });




</script>