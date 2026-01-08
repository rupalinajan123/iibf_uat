<?php

$memRes = array();
if($regId != "")
{
  $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $regId));
  //print_r($candidate_data); die;
  $candidateArr = $candidate_data[0];

  if (count($candidate_data) > 0) 
  {
    $entered_regnumber = !empty($candidateArr['entered_regnumber']) ? $candidateArr['entered_regnumber'] : '';
    $namesub = !empty($candidateArr['namesub']) ? $candidateArr['namesub'] : '';
    $firstname = !empty($candidateArr['firstname']) ? $candidateArr['firstname'] : '';
    $middlename = !empty($candidateArr['middlename']) ? $candidateArr['middlename'] : '';
    $lastname = !empty($candidateArr['lastname']) ? $candidateArr['lastname'] : '';
    $dateofbirth = !empty($candidateArr['dateofbirth']) ? $candidateArr['dateofbirth'] : '';
    $gender = !empty($candidateArr['gender']) ? $candidateArr['gender'] : '';
    $email_id = !empty($candidateArr['email_id']) ? $candidateArr['email_id'] : '';
    $mobile_no = !empty($candidateArr['mobile_no']) ? $candidateArr['mobile_no'] : '';
    $alt_email_id = !empty($candidateArr['alt_email_id']) ? $candidateArr['alt_email_id'] : '';
    $alt_mobile_no = !empty($candidateArr['alt_mobile_no']) ? $candidateArr['alt_mobile_no'] : '';
    $qualification_type = !empty($candidateArr['qualification_type']) ? $candidateArr['qualification_type'] : '';
    
    $addressline1 = !empty($candidateArr['address1']) ? $candidateArr['address1'] : '';
    if($addressline1 != "")
    {
      redirect(site_url('iibfdra/Version_2/TrainingBatches_test/candidate_list/'.base64_encode($bid)));
    }

    if($entered_regnumber != "")
    {
      $memres_qry = $this->db->query("SELECT m.* FROM member_registration m where  m.regnumber = '".$entered_regnumber."' AND NOT EXISTS (SELECT d.regnumber,d.regid FROM   dra_members d WHERE  (d.regnumber=m.regnumber)) ");
      $memRes = $memres_qry->result_array();      
    }
  }
  else
  {
    redirect(site_url('iibfdra/Version_2/TrainingBatches_test'));
  }
} ?>

<!-- custom style for datepicker dropdowns -->
<style>
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

  .box-header { padding: 10px 10px 10px 10px; margin:0 0 15px 0; }
  .box.custom_sub_header { border-radius: 0; border-left: none; border-right: none; margin: 0; border-bottom: none; }

  .note { color: blue; font-size: 12px; line-height: 15px; display: inline-block; margin: 5px 0 0 0; }
  .note-error { color: rgb(185, 74, 72); font-size: 12px; line-height: 15px; display: inline-block; margin: 5px 0 0 0; vertical-align:top; }
  .parsley-errors-list > li { display: inline-block !important; font-size: 12px; line-height: 14px; margin: 2px 0 0 0 !important; padding: 0 !important; }
  .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover, .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover { cursor: not-allowed; background: #eee; border: 1px solid #fff; }
  #loading { display: none;	position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; }
  #loading > p { margin: 0 auto; width: 100%; height: 100%; position: absolute; top: 20%; }
  #loading > p > img { max-height: 250px; margin:0 auto; display: block; }
  .form-group ul.parsley-errors-list li::before { content: ""; }
  .radio_btn_label { margin-bottom:0; }
  .radio_btn_label > input[type="radio"] { margin: 6px 3px 2px 2px; vertical-align: top; }
</style>

<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif"/></p>
</div>

<div class="content-wrapper">
  <section class="content-header">
    <h1>DRA Training Applicants Form</h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <form class="form-horizontal" autocomplete="off" name="draExamAddFrm" id="draExamAddFrm" method="post" enctype="multipart/form-data" data-parsley-validate="parsley">
         <input type="hidden" name="submit_form" id="submit_form" value="">
          <?php
          //print_r($batch_details);
          $current_date = date('Y-m-d');
          //echo '---'.$current_date .'<='. $date_after_3days;
          if ($current_date <= $training_from_date) 
          { ?>
            <div class="alert alert-warning alert-dismissible">
              <?php /* To be filled in with appropriate contents carefully as the same cannot be changed after the locking period i.e. (<?php echo $training_from_date; ?>) */ ?>

              Below mentioned fields (i.e. Basic Details) need to be filled or edited carefully with appropriate contents till end of Day - 1 of the Training Period (i.e. by 11.59.59 PM of <?php echo date("d.m.Y",strtotime($training_from_date)); ?>).<br>No contents can be changed or edited after the Day - 1 (i.e. <?php echo date("d.m.Y",strtotime($training_from_date)); ?>) of the Training Period. 
            </div>

            <div class="box box-info" id="basic_details_div">
              <div class="box-header with-border">
                <h3 class="box-title">Basic Details</h3>
                <div class="pull-right"> <a href="<?php echo base_url(); ?>iibfdra/Version_2/TrainingBatches_test/candidate_list/<?php echo base64_encode($bid); ?>" class="btn btn-warning">Back</a> </div>
              </div>
              <div class="box-body">
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
                else if ($_SESSION['custom_success'] != '') { ?>
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $_SESSION['custom_success']; ?>
                  </div>
                <?php }

                if (validation_errors() != '') { ?>
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo validation_errors(); ?>
                  </div>
                <?php }
                
                if ($img_error_msg != '') { ?>
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $img_error_msg; ?>
                  </div>
                <?php } ?>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Registration No.</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="Registration No." maxlength="10" value="<?php if($entered_regnumber != "") { echo $entered_regnumber; } else { echo set_value('reg_no'); } ?>" onkeypress="return isNumber(event);" autocomplete="off" onkeyup="remove_err_msg('reg_no')" />
                    
                    <input type="hidden" autocomplete="false" name="memtype" value="<?php echo set_value('memtype'); ?>" maxlength="6" id="memtype" />
                    <input type="hidden" autocomplete="false" name="membertype" value="<?php echo (set_value('membertype')) ? set_value('membertype') : 'normal_member'; ?>" id="membertype" />
                    <input type="hidden" autocomplete="false" name="examcd" value="<?php echo $data_examcode; ?>" />
                    <input type="hidden" autocomplete="false" id="batchid" name="bid" value="<?php echo set_value('bid'); ?><?php echo $bid; ?>" />
                    <span class="note-error" id="reg_no_error"></span>
                  </div>(Only for re-exam)
                  <?php if($entered_regnumber == "") { ?>
                  <button type="button" name="get_details" class="dra-get-memdetails" onclick="get_member_details()">Get Details</button>
                  <?php } ?>
                </div>

                <div class="form-group">
                  <label for="first_name" class="col-sm-3 control-label">Name <span class="mandatory-field">*</span></label>
                  <div class="col-sm-2">
                    Salutation <span class="mandatory-field">*</span>
                    <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                      <option value="">Select</option>
                      <option value="Mr." <?php if ((isset($namesub) && $namesub == 'Mr.') || set_value('sel_namesub') == 'Mr.') { echo 'selected="selected"'; } ?>>Mr.</option>
                      <option value="Mrs." <?php if ((isset($namesub) && $namesub == 'Mrs.') || set_value('sel_namesub') == 'Mrs.') { echo 'selected="selected"'; } ?>>Mrs.</option>
                      <option value="Ms." <?php if ((isset($namesub) && $namesub == 'Ms.') || set_value('sel_namesub') == 'Ms.') { echo 'selected="selected"'; } ?>>Ms.</option>
                      <option value="Dr." <?php if ((isset($namesub) && $namesub == 'Dr.') || set_value('sel_namesub') == 'Dr.') { echo 'selected="selected"'; } ?>>Dr.</option>
                      <option value="Prof." <?php if ((isset($namesub) && $namesub == 'Prof.') || set_value('sel_namesub') == 'Prof.') { echo 'selected="selected"'; } ?>>Prof.</option>
                    </select>
                  </div>

                  <div class="col-sm-4">
                    First Name <span class="mandatory-field">*</span>
                    <input type="text" name="firstname" id="firstname" class="form-control"   placeholder="First Name" value="<?php if($firstname != "") { echo $firstname; } else { echo set_value('firstname'); } ?>" autocomplete="off" onkeypress="return onlyAlphabets(event)" required maxlength="30" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-trigger="focusin focusout">
                    <span class="note" id="firstname">Note: You can Enter maximum 30 Characters</span></br>
                    <span class="error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="address_line3" class="col-sm-3 control-label address_fields"></label>
                  <div class="col-sm-3">
                    Middle Name
                    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" value="<?php if($middlename != "") { echo $middlename; } else { echo set_value('middlename'); } ?>" autocomplete="off" onkeypress="return onlyAlphabets(event)" maxlength="30" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-trigger="focusin focusout">
                    <span class="note" id="middlename">Note: You can Enter maximum 30 Characters</span></br>
                    <span class="error"></span>
                  </div>

                  <div class="col-sm-3">
                    Last Name <span class="mandatory-field"></span>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php if($lastname != "") { echo $lastname; } else { echo set_value('lastname'); } ?>" autocomplete="off" onkeypress="return onlyAlphabets(event)" maxlength="30" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-trigger="focusin focusout">
                    <span class="note" id="lastname">Note: You can Enter maximum 30 Characters</span></br>
                    <span class="error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="dob" class="col-sm-3 control-label">Date of Birth <span class="mandatory-field">*</span></label>
                  <div class="col-sm-2 example">
                    <input type="hidden" autocomplete="false" id="dateofbirth" name="dob" required value="<?php echo $dateofbirth; ?>">

                    <?php
                      $start_year = date('Y-m-d', strtotime("- 70 year", strtotime(date('Y-m-d'))));
                      $end_year = date('Y-m-d', strtotime("- 15 year", strtotime(date('Y-m-d'))));
                    ?>
                    <input type="text" class="form-control" id="dob_date" name="dob_date" placeholder="Date of Birth" value="<?php if($dateofbirth != "") { echo $dateofbirth; } else { echo set_value('dob_date'); } ?>" onchange="remove_err_msg('dob_date')" autocomplete="off" required data-parsley-errors-container="#dob_date_error"/>  
                    <span class="note">Note: Please Select date of birth between <?php echo $start_year; ?> to <?php echo $end_year; ?> date.</span>                 
                    
                    <?php /*
                    $min_year = date('Y', strtotime("- 18 year"));
                    $max_year = date('Y', strtotime("- 60 year"));
                    ?>
                    <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                    <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>"> */?>
                    <span id="dob_date_error" class="note-error"></span>
                    <span class="error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="gender" class="col-sm-3 control-label">Gender (M/F) <span class="mandatory-field">*</span></label>
                  <div class="col-sm-9">
                    <label class="radio_btn_label">
                      <input type="radio" class="minimal" id="male" name="gender" value="male" <?php if ((isset($gender) && $gender == 'male') || set_value('gender') == 'male') { echo 'checked="checked"'; } ?> required data-parsley-errors-container="#gender_error">Male
                    </label>&nbsp;&nbsp;&nbsp;

                    <label class="radio_btn_label">
                      <input type="radio" class="minimal" id="female" name="gender" value="female" <?php if ((isset($gender) && $gender == 'female')  || set_value('gender') == 'female') { echo 'checked="checked"'; } ?> required data-parsley-errors-container="#gender_error">Female
                    </label><br>
                    <span class="error" id="gender_error"></span>
                  </div>                  
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Contact Details<span class="mandatory-field">*</span></label>
                  <div class="col-sm-3">
                    Mobile No.<span class="mandatory-field">*</span>
                    <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile No" value="<?php if($mobile_no != "") { echo $mobile_no; } else { echo set_value('mobile_no'); } ?>" onkeypress="return isNumber(event)" required maxlength="10" data-parsley-type="digits" data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-mobile_no_exist data-parsley-trigger="focusout" data-parsley-mobile_no_exist-message="This Mobile no is already Exists." data-parsley-pattern="/^\d{10}$/" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div>
                  <div class="col-sm-3">
                    Alternate Mobile No.
                    <input type="text" class="form-control" id="alt_mobile" name="alt_mobile_no" placeholder="Alternate Mobile No" value="<?php if($alt_mobile_no != "") { echo $alt_mobile_no; } else { echo set_value('alt_mobile_no'); } ?>" onkeypress="return isNumber(event)" size="10" maxlength="10" data-parsley-type="digits" data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-pattern="/^\d{10}$/" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label"><span class="mandatory-field"></span></label>
                  <div class="col-sm-3">
                    Email Id<span class="mandatory-field">*</span>
                    <input type="text" class="form-control" id="email" name="email_id" placeholder="Email Id" autocomplete="off" value="<?php if($email_id != "") { echo $email_id; } else { echo set_value('email_id'); } ?>" autocomplete="off" required maxlength="80" data-parsley-type="email" data-parsley-pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/" data-parsley-maxlength="80" data-parsley-trigger-after-failure="focusout" data-parsley-errors-container="#email_error" data-parsley-email_exist data-parsley-trigger="focusin focusout" data-parsley-email_exist-message="This Email ID is already Exists.">
                    <span class="note-error" id="email_error"></span>
                  </div>
                  <div class="col-sm-3">
                    Alternate Email Id
                    <input type="text" class="form-control" id="alt_email" name="alt_email_id" autocomplete="off" placeholder="Alternate Email Id" value="<?php if($alt_email_id != "") { echo $alt_email_id; } else { echo set_value('alt_email_id'); } ?>" autocomplete="off" maxlength="80" data-parsley-type="email" data-parsley-pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/" data-parsley-errors-container="#alt_email_error"  data-parsley-maxlength="80" data-parsley-trigger="focusin focusout">
                    <span class="note-error" id="alt_email_error"></span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="participate_yes_no" class="col-sm-3 control-label">Qualification<span class="mandatory-field">*</span></label>
                  <div class="col-sm-5">   
                    <?php if($batch_details[0]['hours'] == '50') {  ?>                
                      <label class="radio_btn_label">
                        <input type="radio"  <?php if($batch_details[0]['hours'] == '50') { echo 'disabled'; }?>>Under Graduate
                      </label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php }
                    else { ?>
                      <label class="radio_btn_label">
                        <input type="radio" class="" id="Under_Graduate" name="qualification_type" value="Under_Graduate" <?php if ((isset($qualification_type) && $qualification_type == 'Under_Graduate')  || set_value('qualification_type') == 'Under_Graduate') { echo 'checked="checked"'; } ?> required data-parsley-errors-container="#qualification_type_error" onchange="apply_validation_for_qualification()">Under Graduate
                      </label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php } ?>

                    <label class="radio_btn_label">
                      <input type="radio" class="" id="Graduate" name="qualification_type" value="Graduate" <?php if ((isset($qualification_type) && $qualification_type == 'Graduate') || set_value('qualification_type') == 'Graduate' || $batch_details[0]['hours'] == '50') { echo 'checked="checked"'; } ?> required data-parsley-errors-container="#qualification_type_error" onchange="apply_validation_for_qualification()">Graduate
                    </label><br>
                    <span class="error" id="qualification_type_error"></span>
                  </div>
                  <span class="error"></span>
                </div>
              </div>

              <?php if (count($candidate_data) == 0) { ?>
                <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <?php /* <input type="hidden" name="btnSubmit1" value="submit_1"> */ ?>
                    <input type="button" class="btn btn-info btn_submit" id="btnSubmit1" name="btnSubmit1" value="Submit I">
                    <input type="button" class="btn btn-danger" name="" id="btnReset1" value="Reset" onclick="reset_form()">
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php }

          if ($current_date <= $date_after_3days) 
          { //else if($current_date >= $training_from_date && $current_date <= $date_after_3days) ?>
            <div class="alert alert-warning alert-dismissible">
              <?php /* To be filled in with appropriate contents carefully as the same cannot be changed after the locking period i.e. (<?php echo $date_after_3days; ?>) */ ?>
              
              Below mentioned fields (i.e. Other Details) need to be filled / uploaded / edited carefully with appropriate contents till end of Day - 3 of the Training Period (i.e. by 11.59.59 PM of <?php echo date("d.m.Y",strtotime($date_after_3days)); ?>).<br>No contents can be changed or edited after the Day - 3 (i.e. <?php echo date("d.m.Y",strtotime($date_after_3days)); ?>) of the Training Period. 
            </div>

            <div class="box box-info" id="other_details_div">
              <div class="box-header with-border">
                <h3 class="box-title">Other Details</h3>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="address_line1" class="col-sm-3 control-label">Address<span class="mandatory-field">*</span></label>
                  <div class="col-sm-4">
                    Line 1<span class="mandatory-field">*</span>
                    <input type="text" class="form-control form2_element" id="addressline1" name="addressline1" placeholder="Address line 1" value="<?php if(isset($memRes[0]['address1'])) {  echo $memRes[0]['address1']; } else { echo set_value('addressline1'); } ?>"  autocomplete="off" required maxlength="30" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div>

                  <div class="col-sm-4">
                    Line 2
                    <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line 2" value="<?php if(isset($memRes[0]['address2'])) {  echo $memRes[0]['address2']; } else { echo set_value('addressline2'); } ?>" autocomplete="off" maxlength="30" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="address_line1" class="col-sm-3 control-label"><span class="mandatory-field"></span></label>
                  <div class="col-sm-4">
                    Line 3
                    <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line 3"value="<?php if(isset($memRes[0]['address3'])) {  echo $memRes[0]['address3']; } else { echo set_value('addressline3'); } ?>" autocomplete="off" maxlength="30" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div>

                  <div class="col-sm-4">
                    Line 4
                    <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line 4"value="<?php if(isset($memRes[0]['address4'])) {  echo $memRes[0]['address4']; } else { echo set_value('addressline4'); } ?>" autocomplete="off" maxlength="30" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="state" class="col-sm-3 control-label">State<span class="mandatory-field">*</span></label>
                  <div class="col-sm-4">
                    <select class="form-control form2_element" id="ccstate" name="state" required onchange="get_city_ajax()" >
                        <option value="">Select</option>
                        <?php if(count($states) > 0){ foreach($states as $row1){  ?>
                        <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                        <?php } } ?>
                    </select>                  
                    <input hidden="statepincode" id="statepincode" value="<?php echo set_value('statepincode');?>">
                  </div>                
                </div>                
                
                <div class="form-group">
                  <label for="district" class="col-sm-3 control-label">District <span class="mandatory-field">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control form2_element" id="district" name="district" placeholder="District" required value="<?php echo set_value('district');?>"  autocomplete="off" data-parsley-maxlength="30" maxlength="30" data-parsley-trigger="focusin focusout">
                      <span class="error"></span>
                    </div>
                </div>
                            
                <div class="form-group">
                  <label for="city" class="col-sm-3 control-label">City <span class="mandatory-field">*</span></label>
                    <div class="col-sm-4">
                      <select class="form-control city form2_element" id="city" name="city" required >
                        <option value="">Select City</option>
                      </select>
                      <span class="error"></span>
                    </div>
                    <label style="width: 86px;" for="pincode" class="col-sm-2 control-label">Pincode <span class="mandatory-field">*</span></label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control form2_element" id="pincode" name="pincode" placeholder="Pincode" required value="<?php echo set_value('pincode');?>" onkeypress="return(isNumber(event));"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dracheckpin data-parsley-type="number" autocomplete="off" data-parsley-trigger="focusin focusout"> 
                      <span class="error"></span>
                    </div>
                </div>
              </div>

              <div class="box box-info custom_sub_header">
                <div class="box-header with-border">
                  <h3 class="box-title">Agency Details</h3>
                </div>

                <div class="box-body">
                  <div class="form-group">
                    <?php
                    $institute_name = '';
                    $drainstdata = $this->session->userdata('dra_institute');
                    if ($drainstdata) 
                    {
                      $institute_name = $drainstdata['institute_name'];
                      $institute_code = $drainstdata['institute_code'];
                    } ?>

                    <label for="inst_name" class="col-sm-3 control-label">Name Of Training Institute </label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" placeholder="Name Of Training Institute" value="<?php echo $institute_name; ?>" autocomplete="off" readonly="readonly">
                      <input type="hidden" class="form-control" value="<?php echo $institute_code; ?>" name="inst_code" autocomplete="off" readonly="readonly">
                    </div>
                  </div>

                  <?php
                  if (count($batch_details) > 0) 
                  {
                    foreach ($batch_details as $batchd) 
                    { ?>
                      <div class="form-group">
                        <label for="center" class="col-sm-3 control-label">Centre Name </label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" placeholder="Center Code" name="inst_city" value="<?php echo set_value('center_code'); ?> <?php echo $batchd['city_name']; ?>" autocomplete="off" readonly>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="center" class="col-sm-3 control-label">State </label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inst_state_name" name="inst_state_name" value="<?php echo $batchd['state_name']; ?>" autocomplete="off" readonly>
                          <input type="hidden" class="form-control" id="inst_state_code" name="inst_state_code" value="<?php echo $batchd['state_code']; ?>" autocomplete="off">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="center" class="col-sm-3 control-label">District </label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inst_district_name" name="inst_district_name" value="<?php echo $batchd['district']; ?>" autocomplete="off" readonly>
                          <input type="hidden" class="form-control" id="inst_district" name="inst_district" value="<?php echo $batchd['district']; ?>" autocomplete="off">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="center" class="col-sm-3 control-label">City </label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inst_city_name" name="inst_city_name" value="<?php echo $batchd['city_name']; ?>" autocomplete="off" readonly>
                          <input type="hidden" class="form-control" id="inst_city_id" name="inst_city_id" value="<?php echo $batchd['city']; ?>" autocomplete="off">
                        </div>
                      </div>

                      <div class="form-group">
                        <input type="hidden" class="form-control" id="inst_pincode" name="inst_pincode" value="<?php echo $batchd['pincode']; ?>" autocomplete="off">
                      </div>
                
                      <?php /*?><div class="form-group">
                              <label for="center" class="col-sm-3 control-label">Batch Name </label>
                              <div class="col-sm-5">
                              <input type="text" class="form-control" placeholder="Center Code"  value="<?php echo set_value('center_code');?> <?php echo $batchd['batch_name'] ;?>" autocomplete="off" readonly>
                            </div>
                          </div><?php */ ?>

                      <div class="form-group">
                        <label for="training_period" class="col-sm-3 control-label">Training Period </label>
                        <div class="col-sm-2">
                          From
                          <input type="text" class="form-control" name="training_period_from" value="<?php echo $batchd['batch_from_date']; ?>" autocomplete="off" readonly="readonly" />
                          <span class="error"></span>
                        </div>
                        <div class="col-sm-2">
                          To
                          <input type="text" class="form-control" name="training_period_to" placeholder="Training To Date" value=" <?php echo $batchd['batch_to_date']; ?>" autocomplete="off" readonly="readonly" />
                          <span class="error"></span>
                        </div>
                      </div>
              <?php }
                  } ?>
                </div>
              </div>

              <div class="box box-info custom_sub_header">
                <div class="box-header with-border">
                  <h3 class="box-title">Photograph, Signature and Copies of Documents of the Candidate</h3>
                </div>
                <div class="box-body">
                  <h4>Note</h4>
                  <ol>
                    <li>Allowed Proof of Identity image Size - 10 to 25 KB</li>
                    <li>Allowed Degree Certificate image Size - 50 to 100 KB</li>
                    <!--  <li>Allowed Training Certificate image Size - 50 to 100 KB</li>-->
                    <li>Allowed Photo Size - 10 to 20 KB</li>
                    <li>Allowed Signature Size - 10 to 20 KB</li>
                  </ol><br>

                  <div class="form-group idproof-wrap">
                    <label for="id_proof" class="col-sm-3 control-label">Select Id Proof <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5">
                      <?php if (count($idtype_master) > 0) 
                      {
                        $i = 1;
                        foreach ($idtype_master as $idrow) 
                        { ?>
                          <label class="radio_btn_label">
                            <input name="idproof" value="<?php echo $idrow['id']; ?>" type="radio" class="btn_check form2_element" <?php if (set_value('idproof')) { echo set_radio('idproof', $idrow['id'], TRUE); } ?> required data-parsley-errors-container="#idproof_error"><?php echo $idrow['name']; ?>
                          </label><br>
                          <?php $i++;
                        }
                      } ?>
                      <span class="error" id="idproof_error"></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id_proof" class="col-sm-3 control-label">Id Proof Number <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="id_no_proof">
                      <input type="text" class="form-control form2_element" id="idproof_no" name="idproof_no" placeholder="Id Proof Number." value="<?php echo set_value('idproof_no'); ?>" autocomplete="off" required data-parsley-errors-container="#idproof_no_error" data-parsley-idproof_exist data-parsley-trigger="focusin focusout" data-parsley-idproof_exist-message="This ID Proof is already Exists.">
                      <span class="note" id="idproof_no_note" style="display:block"></span>
                      <span class="note-error" id="idproof_no_error"></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id_proof" class="col-sm-3 control-label">Proof of Identity <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="exist_draidproofphoto">
                      <input type="file" class="form-control form2_element" name="draidproofphoto" id="draidproofphoto" autocomplete="off" onchange="validateFile(event, 'idproof_size_error', 'idproof_preview', '25kb')" required>
                      <input type="hidden" autocomplete="false" id="hiddenidproofphoto" name="hiddenidproofphoto">
                      <span id="draidproofphoto_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                      <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png Files upto 25KB in 100*120 pixel dimensions.</span></br>
                      <span class="note-error" id="idproof_size_error"></span>
                      <!-- <div id="error_dob"></div> -->
                      <br>
                      <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"></span>
                    </div>
                    <img id="idproof_preview" height="100" width="100" src="/assets/images/default1.png" />
                  </div>

                  <div class="form-group">
                    <label for="education" class="col-sm-3 control-label">Qualification<span class="mandatory-field">*</span></label>
                    <div class="col-sm-7">
                      <?php if($batch_details[0]['hours'] == '50') {  ?>
                        <label class="radio_btn_label">
                          <input type="radio" disabled>10th Pass
                        </label>
                        <label class="radio_btn_label">
                          <input type="radio" disabled>12th Pass
                        </label>
                      <?php } 
                      else {  ?>
                        <label class="radio_btn_label">
                          <input type="radio" class="radiocls form2_element" id="tenth" name="education_qualification" value="tenth" <?php if(set_value('education_qualification') == 'tenth') { echo 'checked="checked"'; }; ?> required data-parsley-errors-container="#education_qualification_error">10th Pass
                        </label>
                        <label class="radio_btn_label">
                          <input type="radio" class="radiocls form2_element" id="twelth" name="education_qualification" value="twelth" <?php if(set_value('education_qualification') == 'twelth') { echo 'checked="checked"'; }; ?> required data-parsley-errors-container="#education_qualification_error">12th Pass
                        </label>
                      <?php } ?>                      
                      <label class="radio_btn_label">  
                        <input type="radio" class="radiocls form2_element" id="graduate" name="education_qualification" value="graduate" <?php if(set_value('education_qualification') == 'graduate') { echo 'checked="checked"'; }; ?> required data-parsley-errors-container="#education_qualification_error">Graduation
                      </label>
                      <label class="radio_btn_label">  
                        <input type="radio" class="radiocls form2_element" id="post_graduate" name="education_qualification" value="post_graduate" <?php if(set_value('education_qualification') == 'post_graduate') { echo 'checked="checked"'; }; ?> required data-parsley-errors-container="#education_qualification_error">Post Graduation
                      </label>
                      <span class="error" id="education_qualification_error"></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="quali_certificate" class="col-sm-3 control-label">Qualification Certificate <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="exist_qualicertificate">
                      <input type="file" name="qualicertificate" id="qualicertificate" class="form-control form2_element" autocomplete="off" onchange="validateFile(event, 'error_qualicert', 'qualicertificate_preview', '100kb')" required> (As per educational qualification selected above)
                      <input type="hidden" autocomplete="false" id="hiddenqualicertificate" name="hiddenqualicertificate"></br>
                      <span id="qualicertificate_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                      <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png Files upto 100KB in 100*120 pixel dimensions.</span></br>
                      <span class="note-error" id="error_qualicert"></span>
                      <br>
                      <span class="qualicert_text" style="display:none;"></span>
                      <span class="error"></span>
                    </div>
                    <img id="qualicertificate_preview" height="100" width="100" src="/assets/images/default1.png" />
                  </div>

                  <div class="form-group">
                    <label for="photograph" class="col-sm-3 control-label">Passport Photograph of the Candidate <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="exist_drascannedphoto">
                      <input type="file" name="drascannedphoto" id="drascannedphoto" class="form-control form2_element" autocomplete="off" onchange="validateFile(event, 'scannedphoto_error', 'scanphoto_preview', '20kb')" required>
                      <input type="hidden" autocomplete="false" id="hiddenphoto" name="hiddenphoto">
                      <span id="drascannedphoto_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                      <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png Files upto 20KB in 100*120 pixel dimensions.</span></br>
                      <span class="note-error" id="scannedphoto_error"></span>
                      <br>

                      <span class="photo_text" style="display:none;"></span>
                      <span class="error"></span>
                    </div>
                    <img id="scanphoto_preview" height="100" width="100" src="/assets/images/default1.png" />
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label"> Full Signature of the Candidate <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="exist_drascannedsignature">
                      <input type="file" name="drascannedsignature" id="drascannedsignature" class="form-control form2_element" autocomplete="off" onchange="validateFile(event, 'error_signature', 'signature_preview', '20kb')" required>
                      <input type="hidden" autocomplete="false" id="hiddenscansignature" name="hiddenscansignature">
                      <span id="drascannedsignature_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                      <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png Files upto 20KB in 100*120 pixel dimensions.</span></br>
                      <span class="note-error" id="error_signature"></span>
                      <span class="signature_text" style="display:none;"></span>
                      <span class="error"></span>
                    </div>
                    <img id="signature_preview" height="100" width="100" src="/assets/images/default1.png" />
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Aadhar Card No.<span class="mandatory-field"></span></label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="aadhar_no" name="aadhar_no" placeholder="Aadhar card No." autocomplete="off" value="<?php echo set_value('aadhar_no'); ?>" onkeypress="return isNumber(event)" onchange="return isNumber(event)" data-parsley-minlength="12" data-parsley-minlength-message="Please enter 12 digit Aadhar No" maxlength="12" data-parsley-errors-container="#aadhar_no_error"  data-parsley-aadhar_no_exist data-parsley-trigger="focusin focusout" data-parsley-aadhar_no_exist-message="This Adhar no is already Exists." data-parsley-type="digits">
                      <span class="note">Note: Please Enter Aadhar no like: 666635870783</span></br>
                      <span class="error" id="aadhar_no_error"></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="box box-info custom_sub_header">
                <div class="box-header with-border">
                  <h3 class="box-title">
                    <label class="radio_btn_label">
                      <input name="declaration1" class="form2_element" value="1" type="checkbox" <?php if (set_value('declaration1')) { echo set_radio('declaration1', '1'); } ?> required >&nbsp; I Accept Declaration
                    </label>
                  </h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <div class="col-sm-12">
                      <p>
                        I Wish to enroll as a candidate for the above mentioned examination. I confirm having read Rules and Regulations and other instructions governing the above examination of the institute. I hereby agree to abide by all the said Rules and Regulations and other instructions of the institute. I declare that i have not been debarred/disqualified from appearing at the institute's examination/s at the time of submitting this application. I further declare that in case I am desirous of instituting any legal proceedings against the institute, I hereby agree that such legal proceedings shall be instituted only in Courts at New Delhi, Kolkata, Mumbai & Chennai as the case may be, in whose jurisdiction the application is submitted by me and not in any other Court.
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-3">
                  <input type="button" class="btn btn-info btn_submit" name="btnSubmit2" id="btnSubmit2" value="Submit II">
                  <input type="button" class="btn btn-danger" name="" id="btnReset2" value="Reset"  onclick="reset_form()">
                </div>
              </div>
            </div>
    <?php } ?>
        </form>
      </div>
    </div>
  </section>
</div>

<!-- Data Tables -->
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<!-- <script src="<?php echo base_url() ?>js/validation_dra.js"></script>
 -->
<?php /* <script src="<?php echo base_url() ?>js/validation_dra_batch.js"></script> */ ?>

<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
  
  //START : USED TO REMOVE ERROR MESSAGE ON VALUE CHANGE
  function remove_err_msg(input_id)
  {
    if($("#"+input_id).val() != "")
    {
      //$("#draExamAddFrm").parsley().validate()
      $("#"+input_id).removeClass('parsley-error');
      $("#"+input_id+"_error").html('');
    }
  }
  //END : USED TO REMOVE ERROR MESSAGE ON VALUE CHANGE

  function reset_form()
  {
    location.reload();
  }

  function get_member_details()
  {
    var regno = $("#reg_no").val();

    if(regno == '') { $("#reg_no_error").html('Please enter Registration No') }
    else { $("#reg_no_error").html('') }

    var batch_id = $('#batchid').val();
    var attr = $("#reg_no").attr('readonly');
    //alert(batch_id);
    if (regno != '' && typeof attr == typeof undefined && attr !== "false")
    {
      $("#loading").show();
      // validate reg no -
      var letterNumber = /^[0-9]+$/;
      if (regno.match(letterNumber)) 
      {
        //return true;  
      } 
      else 
      {
        $('#draExamAddFrm').parsley().destroy();
        $("#loading").hide();
        alert("Please enter numeric registration number only");
        $("#reg_no").val('').focus();
        return false;
      }

      // eof code
      var sdata = {
        'regno': regno,
        'batch_id': batch_id
      }
      $.ajax({
        type: "POST",
        url: site_url + 'iibfdra/Version_2/TrainingBatches_test/get_memdetails/',
        data: sdata,
        success: function(data) 
        {
          $('#draExamAddFrm').parsley().destroy();
          $("#loading").hide();
          //alert(data);  
          //console.log(data);
          /*$("input").removeAttr("required");
          $("select").removeAttr("required");
          $(".required-spn").text("");
          $("#exam_medium").attr("required", true);
          $("#exam_center").attr("required", true);
          $("#pincode").removeAttr("data-parsley-dracheckpin");*/
          //alert('swati');

          //   if(data == 2){

          //       alert('Number you have enterd is not your agency member.please enter valid Number');
          //       $("#reg_no").val('').focus();
          //       return false;

          //   }else if(data == 3){

          //       alert('Number you have enterd is not member of this batch.please enter valid Number');
          //       $("#reg_no").val('').focus();
          //       return false;

          //   }else if(data == 4){
          //       alert('You have already applied to exam');
          //       $("#reg_no").val('').focus();
          //       return false;
          //   }else if(data == 5){
          //       alert('You have already passed this exam');
          //       $("#reg_no").val('').focus();
          //       return false;
          //   }else if(data == 6){
          //       alert('You have already exist in exam menu.Please follow exam procedure');
          //       $("#reg_no").val('').focus();
          //       return false;
          //   }
          var obj = jQuery.parseJSON(data);
          var memtype = obj['membertype'];
          //console.log(obj);
          if (Object.keys(obj).length > 0) 
          {
            var flg = 0;
            $.each(obj, function(key, value) 
            {
              if (key == 'error_message') 
              {
                alert(value);
                $("#reg_no").val('').focus();
                flg = 1;
                return false;
              }

              if (key == 'error' && value == 1) 
              {
                alert("Invalid registration number");
                flg = 1;
                return false;
              }
              
              if ($("#" + key).length > 0) { $("#" + key).val(value); }
              if (key == 'sel_namesub') 
              { 
                //$('#sel_namesub').html('<option value="' + value + '">' + value + '</option>');
                if(value.toLowerCase() == "mr.") { $("#sel_namesub").val('Mr.'); }
                else if(value.toLowerCase() == "mrs.") { $("#sel_namesub").val('Mrs.'); }
                else if(value.toLowerCase() == "ms.") { $("#sel_namesub").val('Ms.'); }
                else if(value.toLowerCase() == "dr.") { $("#sel_namesub").val('Dr.'); }
                else if(value.toLowerCase() == "prof."){ $("#sel_namesub").val('Prof.'); }
                else { $("#sel_namesub").val(''); }
              }
              
              if (key == 'mobile') { $("#mobile_no").val(value); }
              
              if (key == 'exam_mode') { $("#" + value).prop('checked', true); }
              if (key == 'edu_quali') 
              { 
                if(value == 'G') { $("#Graduate").prop('checked', true); }
                else if(value == 'U') { $("#Under_Graduate").prop('checked', true); }
              }
              if (key == 'gender') { $("#" + value).prop('checked', true); }
              if (key == 'idproof') { $(".idproof-wrap").find("input[value='" + value + "']").prop('checked', true); }
              if (key == 'state') 
              { 
                $("#ccstate").val(value);
                get_city_ajax();
              }
              if (key == 'city') 
              { 
                //$('#city').html('<option value="' + value + '">' + value + '</option>');
                //$("#city").val(value);
              }
              
              //alert("Key : " + key);
              //alert("Value : " + value);
              // code to view existinf images, Added by Bhagwan Sahane, 25-01-2017 -
              if (key == 'idproofphoto' && value != '') {
                $("#exist_draidproofphoto").html('<img src="' + value + '" id="idproof_preview" height="100" width="100"/>');
              } else if (key == 'idproofphoto' && value == '') {
                $("#exist_draidproofphoto").html('<span class="error">Your Identity Proof is not available, kindly apply again with new application.</span>');
              }

              if (key == 'scannedphoto' && value != '') {
                $("#exist_drascannedphoto").html('<img src="' + value + '" id="scanphoto_preview" height="100" width="100"/>');
              } else if (key == 'scannedphoto' && value == '') {
                $("#exist_drascannedphoto").html('<span class="error">Your Scanned Photograph is not available, kindly apply again with new application.</span>');
              }

              if (key == 'scannedsignaturephoto' && value != '') {
                $("#exist_drascannedsignature").html('<img src="' + value + '" id="signature_preview" height="100" width="100"/>');
              } else if (key == 'scannedsignaturephoto' && value == '') {
                $("#exist_drascannedsignature").html('<span class="error">Your Scanned Signature is not available, kindly apply again with new application.</span>');
              }

              if (key == 'quali_certificate' && value != '') {
                $("#exist_qualicertificate").html('<img src="' + value + '" id="qualicertificate_preview" height="100" width="100"/>');
              } else if (key == 'quali_certificate' && value == '') {
                $("#exist_qualicertificate").html('<span class="error">Your Qualification Certificate is not available, kindly apply again with new application.</span>');
              }

              if (key == 'training_certificate' && value != '') {
                $("#exist_trainingcertificate").html('<img src="' + value + '" id="trcertificate_preview" height="100" width="100"/>');

              } else if (key == 'training_certificate' && value == '') {
                $("#exist_trainingcertificate").html('<span class="error">Your Training Certificate is not available, kindly apply again with new application.</span>');
              }

              if (key == 'dateofbirth')
              {
                var dob_arr = value.split('-');
                var dyear = dob_arr[0];
                var dmnth = dob_arr[1];
                var dday = dob_arr[2];

                $("#dateofbirth").val(value);
                $("#dob_date").val(value);
                //$(".day").val(dday);
                //$(".month").val(dmnth);
                //$(".year").val(dyear);

                /* not to keep it editable - 08-03-2017 */

                //$(".day").attr('disabled', true);
                //$(".month").attr('disabled', true);
                //$(".year").attr('disabled', true);
              }
            });

            if (flg == 1) 
            {
              return false;
            }

            if (memtype == 'normal_member') 
            {
              $("input[name='exam_mode']").prop('checked', false);
              //  $("input[name='edu_quali']").prop('checked', false);
            } 
            else 
            {
              //$("input[name='exam_mode']").attr("readonly","readonly");
              $("input[name='edu_quali']:not(:checked)").attr('disabled', true);
              $("input[name='idproof']:not(:checked)").attr('disabled', true);
              $("input[type='file']").removeAttr("required");

              $(".required-spn").text("");
              if ($("input[name='gender']:checked").length > 0) {
                $("input[name='gender']").removeAttr("disabled");
              }

              $('#training_from').datepicker('remove').attr("readonly", "readonly");
              $('#training_to').datepicker('remove').attr("readonly", "readonly");

              $("input[name='gender']:not(:checked)").attr('disabled', true);
              $("input[name='exam_mode']:not(:checked)").attr('disabled', true);

              /*keep pincode, district, city, address editable - change made on 19-01-2017*/
              $("#ccstate").attr('disabled', 'true');
              $("#pincode").attr("readonly", "readonly");
              $("#district").attr("readonly", "readonly");
              
              //$("#city").attr("disabled","true");
              //$("#addressline1").attr("readonly", "readonly");
              //$("#addressline2").attr("readonly", "readonly");
              //$("#addressline3").attr("readonly", "readonly");
              //$("#addressline4").attr("readonly", "readonly");

              $("#sel_namesub").attr('disabled', true);
              /* keep it editable - 20-01-2017 */
              $("#firstname").attr("readonly", "readonly");
              $("#middlename").attr("readonly", "readonly");
              $("#lastname").attr("readonly", "readonly");
              $("#stdcode").attr("readonly", "readonly");
              $("#phone").attr("readonly", "readonly");
              $("#mobile_no").attr("readonly", "readonly");
              $("#email_id").attr("readonly", "readonly");
              $("#aadhar_no").attr("readonly", "readonly"); // added by Bhagwan Sahane, on 06-05-2017

              $("#reg_no").attr("readonly", "readonly");
            }
          } 
          else 
          {
            $('#draExamAddFrm').parsley().destroy();
            $("#loading").hide();
            alert("Invalid registration number");
            $("#reg_no").val("");
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) 
        {
          $('#draExamAddFrm').parsley().destroy();
          $("#loading").hide();
          alert("Status: " + textStatus);
          alert("Error: " + errorThrown);
        }
      });
    }
  }

  validate_id_proof();
  function validate_id_proof()
  {
    var proof_type = $("input[type='radio'].btn_check:checked").val();
    var idproof_no = $("#idproof_no").val();

    $('#idproof_no_error').text("");

    if (proof_type == 1) 
    {
      var regex = /^\d{12}$/;
      if (regex.test(idproof_no)) 
      {
        $('#idproof_no_error').text("");
        errCnt = 0;
        return true;
      } 
      else 
      {
        $('#idproof_no_error').text("Please enter 12 digit Aadhar No with Valid Format mentioned above.");
        errCnt = 1;
        $("#idproof_no").focus();
        return false;
      }
    } 
    else if (proof_type == 2) 
    {
      var regex = /[A-Z]{2}[0-9]{13}$/;
      if (regex.test(idproof_no)) 
      {
        $('#idproof_no_error').text("");
        errCnt = 0;
        return true;
      } 
      else 
      {
        $('#idproof_no_error').text("Please enter Driving License with 15 alphanumeric values with Valid Format mentioned above.");
        errCnt = 1;
        $("#idproof_no").focus();
        return false;
      }
    } 
    else if (proof_type == 5) 
    {
      var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
      if (regex.test(idproof_no)) 
      {
        $('#idproof_no_error').text("");
        errCnt = 0;
        return true;
      } 
      else 
      {
        $('#idproof_no_error').text("Please enter PAN no with 10 alphanumeric values with Valid Format mentioned above.");
        errCnt = 1;
        $("#idproof_no").focus();
        return false;
      }
    } 
    else if (proof_type == 6) 
    {
      var regex = /^[A-Z]{1}[0-9]{7}/;
      if (regex.test(idproof_no)) 
      {
        $('#idproof_no_error').text("");
        errCnt = 0;
        return true;
      } 
      else 
      {
        $('#idproof_no_error').text("Please enter Passport No with 8 alphanumeric values with valid Format mentioned above.");
        errCnt = 1;
        $("#idproof_no").focus();
        return false;
      }
    } 
    else 
    {
      errCnt = 0;
      return true;
      $('#idproof_no_error').text("");
    }
  }  

  /* Get City From State in Agency tab */
  <?php if(set_value('state') != "") { ?> get_city_ajax(); <?php } ?>
  
  function get_city_ajax()
  {
    var state_code = $("#ccstate").val();
    if (state_code) 
    {
      $.ajax({
        type: 'POST',
        url: site_url + 'iibfdra/Version_2/TrainingBatches_test/getCity',
        data: 'state_code=' + state_code,
        success: function(html) {
          //alert(html);
          $('#city').show();
          $('#city').html(html);
        }
      });
    } 
    else 
    {
      $('#city').html('<option value="">Select State First</option>');
    }
  }

  $("#mobile_no").keypress(function(event) 
  {
    remove_first_zero('mobile_no')

    if (event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46) {
      return true;
    } else {
      return false;
    }
  });
  $("#mobile_no").keyup(function(event) { remove_first_zero('mobile_no') });

  $("#alt_mobile").keypress(function(event) 
  {
    remove_first_zero('alt_mobile')

    if (event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46) {
      return true;
    } else {
      return false;
    }
  });

  $("#alt_mobile").keyup(function(event) { remove_first_zero('alt_mobile') });

  function remove_first_zero(input_id)
  {
    if(input_id != "")
    {
      var x = $("#"+input_id).val();
      if (x.indexOf('0') == 0) 
      {
      $('#'+input_id).val(x.substring(1, x.length));
      return false;
      }
    }
  }

  apply_validation_for_qualification();
  function apply_validation_for_qualification()
  {
    var selected_val = $("input[name='qualification_type']:checked").val();
    if(selected_val == 'Under_Graduate')
    {
      $("#graduate").prop('checked', false);
      $("#post_graduate").prop('checked', false);

      $("#tenth").prop('checked', false);
      $("#twelth").prop('checked', false);

      $("#tenth").removeAttr("disabled");
      $("#twelth").removeAttr("disabled");

      $("#tenth").attr("required");
      $("#twelth").attr("required");

      $("#graduate").attr("disabled", "disabled");
      $("#post_graduate").attr("disabled", "disabled");

      $("#graduate").removeAttr("required");
      $("#post_graduate").removeAttr("required");
    }
    else if(selected_val == 'Graduate')
    {
      $("#tenth").prop('checked', false);
      $("#twelth").prop('checked', false);
      
      $("#graduate").removeAttr("disabled");
      $("#post_graduate").removeAttr("disabled");

      $("#graduate").attr("required");
      $("#post_graduate").attr("required");

      $("#tenth").attr("disabled", "disabled");
      $("#twelth").attr("disabled", "disabled");

      $("#tenth").removeAttr("required");
      $("#twelth").removeAttr("required");
    }
  }

  $(document).ready(function() 
  {
    //START : ON CLICK ON TOP FORM SUBMIT BUTTON, VALIDATE ONLY TOP FORM FIELDS
    //START : ON CLICK ON BOTTOM FORM SUBMIT BUTTON, VALIDATE BOTH FORM FIELDS
    $('#btnSubmit1').click(function () 
    {
      $("#submit_form").val('1');
      $('.form2_element').removeAttr('required');
      
      $('#addressline1').removeAttr('maxlength');
      $('#addressline1').removeAttr('data-parsley-maxlength');      
      $('#addressline2').removeAttr('maxlength');
      $('#addressline2').removeAttr('data-parsley-maxlength');
      $('#addressline3').removeAttr('maxlength');
      $('#addressline3').removeAttr('data-parsley-maxlength');
      $('#addressline4').removeAttr('maxlength');
      $('#addressline4').removeAttr('data-parsley-maxlength');
      $('#district').removeAttr('maxlength');
      $('#district').removeAttr('data-parsley-maxlength');
      $('#pincode').removeAttr('maxlength');
      $('#pincode').removeAttr('data-parsley-maxlength');
      $('#pincode').removeAttr('data-parsley-type');
      $('#pincode').removeAttr('data-parsley-dracheckpin');

      $('#idproof_no').removeAttr('data-parsley-idproof_exist');
      $('#aadhar_no').removeAttr('data-parsley-minlength');
      $('#aadhar_no').removeAttr('maxlength');
      $('#aadhar_no').removeAttr('data-parsley-type');
      //$('#aadhar_no').removeAttr('data-parsley-pattern');
      $('#aadhar_no').removeAttr('data-parsley-aadhar_no_exist');      
      
      $('#draExamAddFrm').parsley().validate();
      $("#draExamAddFrm").submit();
      if($('#draExamAddFrm').parsley().isValid() != false) {  }
    });

    $('#btnSubmit2').click(function () 
    {
      $("#submit_form").val('2');
      
      $("#idproof_size_error").html('');
      $("#error_qualicert").html('');
      $("#scannedphoto_error").html('');
      $("#error_signature").html('');

      $('.form2_element').attr('required','true');

      $('#addressline1').attr('maxlength', '30');
      $('#addressline1').attr('data-parsley-maxlength', '30');
      $('#addressline2').attr('maxlength', '30');
      $('#addressline2').attr('data-parsley-maxlength', '30');
      $('#addressline3').attr('maxlength', '30');
      $('#addressline3').attr('data-parsley-maxlength', '30');
      $('#addressline4').attr('maxlength', '30');
      $('#addressline4').attr('data-parsley-maxlength', '30');      
      $('#district').attr('maxlength', '30');
      $('#district').attr('data-parsley-maxlength', '30');      
      $('#pincode').attr('maxlength', '6');
      $('#pincode').attr('data-parsley-maxlength', '6');
      $('#pincode').attr('data-parsley-type', 'number');
      $('#pincode').attr('data-parsley-dracheckpin','');
      $('#idproof_no').attr('data-parsley-idproof_exist', '');
      $('#aadhar_no').attr('data-parsley-minlength', '12');
      $('#aadhar_no').attr('maxlength', '12');
      $('#aadhar_no').attr('data-parsley-type', 'digits');
      //$('#aadhar_no').attr('data-parsley-pattern', '/^\d{12}$/');
      $('#aadhar_no').attr('data-parsley-aadhar_no_exist', '');

      $('#idproof_no').parsley().validate();
      $('#draExamAddFrm').parsley().validate();
      $("#draExamAddFrm").submit();
      if($('#draExamAddFrm').parsley().isValid() != false && validate_id_proof() == true) {  }
    });
    //END : ON CLICK ON TOP FORM SUBMIT BUTTON, VALIDATE ONLY TOP FORM FIELDS
    //END : ON CLICK ON BOTTOM FORM SUBMIT BUTTON, VALIDATE BOTH FORM FIELDS

    $('#dob_date').datepicker(
    {
        format: "yyyy-mm-dd",
        startDate: '<?php echo date('Y-m-d', strtotime($start_year)); ?>',
        endDate: '<?php echo date('Y-m-d', strtotime($end_year)); ?>',        
        autoclose: true,
        //todayBtn: "linked", 
        keyboardNavigation: true, 
        forceParse: false, 
        //calendarWeeks: true, 
        //todayHighlight:true, 
        clearBtn: true         
    }).attr('readonly', 'readonly');

    var candidate_count = '<?php echo count($candidate_data); ?>';
    if (candidate_count > 0) 
    {
      $('#basic_details_div input[type="text"]').attr("disabled", true);
      $('#basic_details_div input[type="radio"]').attr("disabled", true);
      $('#sel_namesub').attr("disabled", true);
      //$(".day").attr('disabled', true);
      //$(".month").attr('disabled', true);
      //$(".year").attr('disabled', true);
      $("#dob_date").attr('disabled', true);
    }

    //date of birth dropdowns
    /*** $("#dateofbirth").dateDropdowns({
      submitFieldName: 'dob1',
      minAge: 0,
      maxAge: 100
    });*/

    /*** $("#dateofbirth").change(function() 
    {
      var sel_dob = $("#dateofbirth").val();
      if (sel_dob != '') {
        var dob_arr = sel_dob.split('-');
        if (dob_arr.length == 3) {
          chkage(dob_arr[2], dob_arr[1], dob_arr[0]);
        } else {
          alert('Select valid date');
        }
      }
    });*/

    //if invalid captcha entered keep unchecked exam mode disabled
    if ($("input[name='exam_mode']:checked").length > 0) {
      $("input[name='exam_mode']:not(:checked)").attr('disabled', true);
    }

    // change gender on chnage of name subtitle 
    if ($("input[name='gender']:checked").length > 0) {
      $("input[name='gender']:not(:checked)").attr('disabled', true);
    }

    $('#sel_namesub').change(function(event) 
    {
      var sel_namesub = $(this).val();
      $("input[name='gender']:not(:checked)").attr('disabled', false);
      if (sel_namesub == 'Mr.') 
      {
        $("#male").prop('checked', true);
        $("#female").attr('disabled', true);
        $("#gender_error").html('');
      } 
      else if (sel_namesub == 'Mrs.' || sel_namesub == 'Ms.') 
      {
        $("#female").prop('checked', true);
        $("#male").attr('disabled', true);
        $("#gender_error").html('');
      } 
      else 
      {
        $("input[name='gender']").removeAttr('disabled');
        $("#male").prop('checked', false);
        $("#female").prop('checked', false);
      }
    });

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

    $("body").on("contextmenu", function(e) 
    {
      return false;
    });    
  });

  $(document).on('keyup', '#idproof_no', function() { validate_id_proof(); });
  $(document).on('change', '#idproof_no', function() { validate_id_proof(); });

  $(document).on('change', '.btn_check', function() 
  {
    var proof_type = $("input[type='radio'].btn_check:checked").val();
    //console.log('proof_type--'+proof_type);
    $('#idproof_no_error').text("");
    $('#idproof_no').val("");

    if (proof_type == 1) 
    {
      $('#idproof_no_note').text('Note: Please Enter Aadhar no like: 666635870783');
      $("#idproof_no").attr({
        "onkeypress": "return isNumber(event)",
        "maxlength": "12"
      });
    }

    if (proof_type == 2) 
    {
      $('#idproof_no_note').text('Note: Please Enter Driving License No like: MH2730123476102');

      $("#idproof_no").attr({
        "onkeypress": "return alphanumeric(event)",
        "maxlength": "15"
      });
    }

    if (proof_type == 4) 
    {
      $('#idproof_no_note').text('Note: Please Enter maximum 10 digit Employee number');

      $("#idproof_no").attr({
        "onkeypress": "return isNumber(event)",
        "maxlength": "10"
      });
    }

    /*if(proof_type == 4){
      $('#idproof_no_note').text('');
    }*/

    if (proof_type == 5) 
    {
      $('#idproof_no_note').text('Note: Please Enter PAN no like: ABCTY1234D');

      $("#idproof_no").attr({
        "onkeypress": "return alphanumeric(event)",
        "maxlength": "10"
      });
    }

    if (proof_type == 6) 
    {
      $('#idproof_no_note').text('Note: Please Enter Passport like: J8369845');

      $("#idproof_no").attr({
        "onkeypress": "return alphanumeric(event)",
        "maxlength": "8"
      });
    }
  });

  $('#email').parsley();
  window.ParsleyValidator.addValidator('email_exist', 
  {
    validateString: function(value) {
      return $.ajax({
        url: base_url + "iibfdra/Version_2/TrainingBatches_test/check_email",
        method: "POST",
        async: false,
        data: {
          email: value,
          regId:'<?php echo $regId; ?>'
        },
        dataType: 'JSON',
        success: function(data) {
          return true;
        }
      });
    }
    //console.log(isSuccess);
    //return isSuccess;
  });

  $('#mobile_no').parsley();
  window.ParsleyValidator.addValidator('mobile_no_exist', 
  {
    validateString: function(value) {
      return $.ajax({
        url: base_url + "iibfdra/Version_2/TrainingBatches_test/check_mobile_no",
        method: "POST",
        async: false,
        data: {
          mobile_no: value,
          regId:'<?php echo $regId; ?>'
        },
        dataType: 'JSON',
        success: function(data) {
          return true;
        }
      });
    }
    //console.log(isSuccess);
    //return isSuccess;
  });

  $('#aadhar_no').parsley();
  window.ParsleyValidator.addValidator('aadhar_no_exist', 
  {
    validateString: function(value) {
      return $.ajax({
        url: base_url + "iibfdra/Version_2/TrainingBatches_test/check_aadhar_no",
        method: "POST",
        //async: false,
        data: {
          aadhar_no: value,
          regId:'<?php echo $regId; ?>'
        },
        dataType: 'JSON',
        success: function(data) {
          return true;
        }
      });
    }
    //console.log(isSuccess);
    //return isSuccess;
  });

  $('#idproof_no').parsley();
  window.ParsleyValidator.addValidator('idproof_exist', 
  {
    validateString: function(value) {
      return $.ajax({
        url: base_url + "iibfdra/Version_2/TrainingBatches_test/check_idproof",
        method: "POST",
        //async: false,
        data: {
          action: 'add',
          idproof: value,
          regId:'<?php echo $regId; ?>'
        },
        dataType: 'JSON',
        success: function(data) {
          return true;
        }
      });
    }
    //console.log(isSuccess);
    //return isSuccess;
  });

  window.Parsley.addValidator('dracheckpin', function (value, requirement)
  {
    var response = false;
    var datastring='statecode='+$('#ccstate').val()+'&pincode='+value;
    $.ajax({
      url:site_url+'iibfdra/Version_2/DraExam/checkpin/',
      data: datastring,
      type:'POST',
      async: false,
      success: function(data) 
      {
        if(data=='true')
        {
          response = true;
        }
        else
        {
          response = false;
        }
      }
    });
    return response;
  }, 31).addMessage('en', 'dracheckpin', 'Please enter Valid Pincode.');

  function isNumber(evt) 
  {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }

  function onlyAlphabets(key) 
  {
    var keycode = (key.which) ? key.which : key.keyCode;
    //alert(keycode);
    if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 32) {
      return true;
    } else {
      return false;
    }
  }

  function alphanumeric(key)
  {
    var keycode = (key.which) ? key.which : key.keyCode;

    if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 8 || keycode == 32 || (keycode >= 48 && keycode <= 57)) {
      return true;
    } else {
      return false;
    }
  }

  $.ajax({
		type: "POST",
		url: "<?php echo site_url('iibfdra/Version_2/TrainingBatches_test/remove_custom_session'); ?>",
		cache: false,
		dataType: 'JSON'
	});
</script>