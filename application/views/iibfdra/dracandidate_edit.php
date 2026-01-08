<?php //echo '--------------------------------------------'.$exam_edit; die; ?>
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
.mandatory-field {
    color:#F00;
}

.box-header { padding: 10px 10px 10px 10px; margin:0 0 15px 0; }
.box.custom_sub_header { border-radius: 0; border-left: none; border-right: none; margin: 0; border-bottom: none; }

.note { color: blue; font-size: 12px; line-height: 15px; display: inline-block; margin: 5px 0 0 0; }
.note-error { color: rgb(185, 74, 72); font-size: 12px; line-height: 15px; display: inline-block; margin: 5px 0 0 0; vertical-align:top; }
.parsley-errors-list > li { display: inline-block !important; font-size: 12px; line-height: 14px; margin: 2px 0 0 0 !important; padding: 0 !important; }
.datepicker table tr td.disabled, .datepicker table tr td.disabled:hover, .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover { cursor: not-allowed; background: #eee; border: 1px solid #fff; }
#loading { display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; }
#loading > p { margin: 0 auto; width: 100%; height: 100%; position: absolute; top: 20%; }
#loading > p > img { max-height: 250px; margin:0 auto; display: block; }
.form-group ul.parsley-errors-list li::before { content: ""; }
.radio_btn_label { margin-bottom:0; }
.radio_btn_label > input[type="radio"] { margin: 6px 3px 2px 2px; vertical-align: top; }
#basic_details_static_div .form-group { margin-bottom:0; }

#listitems_logs th { border: 1px solid #ccc !important;  text-align: center; background: #eee; }
#listitems_logs td { border: 1px solid #ccc !important;   }
</style>
<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif"/></p>
</div>

<div class="content-wrapper">  
  <section class="content-header">
      <h1> DRA examination application edit form </h1>        
  </section>
  
  <section class="content">
    <div class="row">
      <div class="col-md-12">  
        
      <?php if($this->session->flashdata('error')!='')
      { ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('error'); ?>
        </div>
      <?php } 
    
      if($this->session->flashdata('success')!=''){ ?>
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

      if(validation_errors()!=''){?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo validation_errors(); ?>
        </div>
      <?php } 

      if (isset($img_error_msg) && $img_error_msg != '') { ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $img_error_msg; ?>
        </div>
      <?php }?> 


        <form class="form-horizontal" autocomplete="off" name="draExamAddFrm" id="draExamAddFrm"  method="post"  enctype="multipart/form-data" data-parsley-validate>
          <input type="hidden" name="submit_form" id="submit_form" value="">
          <input type="hidden" autocomplete="false" id="batchid" name="bid" value="<?php echo set_value('bid'); ?><?php echo $examRes['batch_id'] ;?>" />
          <?php  $current_date = date('Y-m-d');
          $show_form_flag = 0;
          if($current_date <= $training_from_date || $exam_edit == 'Yes')
          {
            $show_form_flag = 1;
            if($exam_edit != 'Yes')
            { ?>
              <div class="alert alert-warning alert-dismissible">
                <?php /* To be filled in with appropriate contents carefully as the same cannot be changed after the locking period i.e. (<?php echo $training_from_date; ?>) */ ?>

                Below mentioned fields (i.e. Basic Details) need to be filled or edited carefully with appropriate contents till end of Day - 1 of the Training Period (i.e. by 11.59.59 PM of <?php echo date("d.m.Y",strtotime($training_from_date)); ?>).<br>No contents can be changed or edited after the Day - 1 (i.e. <?php echo date("d.m.Y",strtotime($training_from_date)); ?>) of the Training Period.
              </div>
          <?php } ?>

          <div class="box box-info" id="basic_details_div">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
              <div class="pull-right">
                  <?php if (strpos($redirection_path, 'allapplicants') !== false)
                  { ?>
                    <a href="<?php echo base_url('iibfdra/TrainingBatches/allapplicants/'.base64_encode($_SESSION['excode'])); ?>" class="btn btn-warning">Back</a> 
                  <?php } 
                  else 
                  { ?>
                    <a href="<?php echo base_url('iibfdra/TrainingBatches/candidate_list/'.base64_encode($examRes['batch_id'])); ?>" class="btn btn-warning">Back</a> 
                  <?php } ?>
                </div>
            </div>
          
            <div class="box-body"> 
              <div class="form-group">
                <label class="col-sm-3 control-label">Training Id</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="training_id" name="training_id" value="<?php echo $examRes['training_id'];?>" readonly='readonly' autocomplete="off"/>
                </div>
              </div>
              
              <div class="form-group" <?php if($candidate_details['entered_regnumber'] == "") { echo 'style="display:none"'; } ?>>
                <label class="col-sm-3 control-label">Registration no</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="Registration no"  value="<?php echo $candidate_details['entered_regnumber'];?>" autocomplete="off" readonly='readonly' />
                </div>                  
              </div>
              
              <input type="hidden" autocomplete="false" name="memtype" value="<?php echo set_value('memtype');?>" id="memtype" />
              <input type="hidden" autocomplete="false" name="membertype" value="<?php echo ( set_value('membertype') ) ? set_value('membertype') : 'normal_member';?>" id="membertype" />
              <input type="hidden" autocomplete="false" name="examcd" value="<?php echo $data_examcode;?>" />
              <span class="error"></span>

              <div class="form-group">
                <label for="first_name" class="col-sm-3 control-label">Name <span class="mandatory-field">*</span></label>
                <div class="col-sm-2">
                  Salutation <span class="mandatory-field">*</span>
                  <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                    <option value="">Select</option>
                    <option value="Mr." <?php if($examRes['namesub'] == 'Mr.') { echo 'selected="selected"'; } ?>>Mr.</option>
                    <option value="Mrs." <?php if($examRes['namesub'] == 'Mrs.') { echo 'selected="selected"'; } ?>>Mrs.</option>
                    <option value="Ms." <?php if($examRes['namesub'] == 'Ms.') { echo 'selected="selected"'; } ?>>Ms.</option>
                    <option value="Dr." <?php if($examRes['namesub'] == 'Dr.') { echo 'selected="selected"'; } ?>>Dr.</option>
                    <option value="Prof." <?php if($examRes['namesub'] == 'Prof.') { echo 'selected="selected"'; } ?>>Prof.</option>
                  </select>
                </div>
                
                <div class="col-sm-4">
                  First Name <span class="mandatory-field">*</span>
                  <input type="text" name="firstname" id="firstname" class="form-control"   placeholder="First Name" value="<?php echo $candidate_details['firstname'];?>" autocomplete="off" onkeypress="return onlyAlphabets(event)" required maxlength="30" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-trigger="focusin focusout">
                  <span class="note" id="firstname">Note: You can Enter maximum 30 Characters</span></br>
                  <span class="error"></span>
                </div>
              </div>
                        
              <div class="form-group">
                <label for="address_line3" class="col-sm-3 control-label address_fields"></label>
                <div class="col-sm-3">
                  Middle Name
                  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" value="<?php echo $candidate_details['middlename'];?>" autocomplete="off" onkeypress="return onlyAlphabets(event)" maxlength="30" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-trigger="focusin focusout">
                  <span class="note" id="middlename">Note: You can Enter maximum 30 Characters</span></br>
                  <span class="error"></span>
                </div>
              
                <div class="col-sm-3">
                  Last Name <span class="mandatory-field"></span>                    
                  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $candidate_details['lastname'];?>" autocomplete="off" onkeypress="return onlyAlphabets(event)" maxlength="30" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-trigger="focusin focusout">
                  <span class="note" id="lastname">Note: You can Enter maximum 30 Characters</span></br>
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="dob" class="col-sm-3 control-label">Date of Birth <span class="mandatory-field">*</span></label>
                <div class="col-sm-2 example">
                  <input type="hidden" autocomplete="false" id="dateofbirth" name="dob" required value="<?php echo $candidate_details['dateofbirth'];?>">

                  <?php
                    $start_year = date('Y-m-d', strtotime("- 70 year", strtotime(date('Y-m-d'))));
                    $end_year = date('Y-m-d', strtotime("- 15 year", strtotime(date('Y-m-d'))));
                  ?>

                  <input type="text" class="form-control" id="dob_date" name="dob_date" placeholder="Date of Birth" value="<?php if($candidate_details['dateofbirth'] != "") { echo $candidate_details['dateofbirth']; } ?>" onchange="remove_err_msg('dob_date')" autocomplete="off" required data-parsley-errors-container="#dob_date_error"/> 
                  <span class="note">Note: Please Select date of birth between <?php echo $start_year; ?> to <?php echo $end_year; ?> date.</span>
                  
                  <?php /*
                    $min_year = date('Y', strtotime("- 18 year"));
                    $max_year = date('Y', strtotime("- 60 year"));
                  ?>
                  <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                  <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>"> */ ?>
                  <span id="dob_date_error" class="note-error"></span>
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="gender" class="col-sm-3 control-label">Gender (M/F) <span class="mandatory-field">*</span></label>
                <div class="col-sm-9">
                  <label class="radio_btn_label">
                    <input type="radio" class="minimal" id="male" name="gender" value="male" <?php if($candidate_details['gender'] == 'male') { echo 'checked="checked"'; } ?> required data-parsley-errors-container="#gender_error">Male
                  </label>&nbsp;&nbsp;&nbsp;

                  <label class="radio_btn_label">
                    <input type="radio" class="minimal" id="female" name="gender" value="female" <?php if($candidate_details['gender'] == 'female') { echo 'checked="checked"'; } ?> required data-parsley-errors-container="#gender_error">Female
                  </label><br>
                  <span class="error" id="gender_error"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Details<span class="mandatory-field">*</span></label>
                <div class="col-sm-3">
                  Mobile No.
                  <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile No" value="<?php echo $candidate_details['mobile_no'];?>" required maxlength="10" data-parsley-type="digits" data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-mobile_no_exist data-parsley-mobile_no_exist-message="This Mobile no is already Exists." data-parsley-pattern="/^\d{10}$/" data-parsley-trigger="focusin focusout">
                  <span class="error note-error" id="mobile_no_error"></span>
                </div>
                <div class="col-sm-3">
                  Alternate Mobile No.
                  <input type="text" class="form-control" id="alt_mobile" name="alt_mobile_no" placeholder="Alternate Mobile No" value="<?php echo $candidate_details['alt_mobile_no'];?>" onkeypress="return isNumber(event)" size="10" maxlength="10" data-parsley-type="digits" data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-pattern="/^\d{10}$/"  data-parsley-trigger="focusin focusout">
                  <span class="error"></span>
                </div>
              </div> 
                            
              <div class="form-group">
                <label class="col-sm-3 control-label"><span class="mandatory-field"></span></label>
                <div class="col-sm-3">
                  Email Id
                  <input type="text" class="form-control" id="email" name="email_id" placeholder="Email Id" autocomplete="off" value="<?php echo $candidate_details['email_id'];?>" autocomplete="off" required maxlength="80" data-parsley-type="email" data-parsley-pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/" data-parsley-maxlength="80" data-parsley-errors-container="#email_error" data-parsley-email_exist data-parsley-trigger= focusout data-parsley-email_exist-message="This Email ID is already Exists.">
                  <span class="note-error" id="email_error"></span>
                </div>
                <div class="col-sm-3">
                  Alternate Email Id
                  <input type="text" class="form-control" id="alt_email" name="alt_email_id" autocomplete="off" placeholder="Alternate Email Id" value="<?php echo $candidate_details['alt_email_id'];?>" autocomplete="off" maxlength="80" data-parsley-type="email" data-parsley-pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/" data-parsley-errors-container="#alt_email_error"  data-parsley-maxlength="80"  data-parsley-trigger="focusin focusout">
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
                      <input type="radio" class="" id="Under_Graduate" name="qualification_type" value="Under_Graduate" <?php if(isset($candidate_details['qualification_type']) && $candidate_details['qualification_type'] == 'Under_Graduate') { echo 'checked="checked"'; } ?> required data-parsley-errors-container="#qualification_type_error" onchange="apply_validation_for_qualification()">Under Graduate
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php } ?>
                  

                  <label class="radio_btn_label">
                    <input type="radio" class="" id="Graduate" name="qualification_type" value="Graduate" <?php if ((isset($candidate_details['qualification_type']) && $candidate_details['qualification_type'] == 'Graduate') || $batch_details[0]['hours'] == '50') { echo 'checked="checked"'; } ?> required data-parsley-errors-container="#qualification_type_error" onchange="apply_validation_for_qualification()">Graduate
                  </label><br>
                  <span class="error" id="qualification_type_error"></span>
                </div>
              </div> 

              <?php if($exam_edit != 'Yes') 
              { ?>
                <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <?php /* <input type="hidden" name="btnSubmit1" value="submit_1"> */ ?>
                    <input type="button" class="btn btn-info btn_submit" id="btnSubmit1" name="btnSubmit1" value="Update I">
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
          <?php } 
          else
          { ?>
            <div class="box box-info" id="basic_details_static_div">
              <div class="box-header with-border">
                <h3 class="box-title">Basic Details</h3>
                <div class="pull-right">
                    <?php if (strpos($redirection_path, 'allapplicants') !== false)
                    { ?>
                      <a href="<?php echo base_url('iibfdra/TrainingBatches/allapplicants/'.base64_encode($_SESSION['excode'])); ?>" class="btn btn-warning">Back</a> 
                    <?php } 
                    else 
                    { ?>
                      <a href="<?php echo base_url('iibfdra/TrainingBatches/candidate_list/'.base64_encode($examRes['batch_id'])); ?>" class="btn btn-warning">Back</a> 
                    <?php } ?>
                </div>
              </div>
          
              <div class="box-body"> 
                <div class="form-group">
                  <label class="col-sm-3 control-label">Training Id</label>
                  <div class="col-sm-6">: <?php echo $examRes['training_id'];?></div>
                </div>
              
                <div class="form-group" <?php if($candidate_details['entered_regnumber'] == "") { echo 'style="display:none"'; } ?>>
                  <label class="col-sm-3 control-label">Registration no</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['entered_regnumber'];?></div>                  
                </div>
              
                <div class="form-group">
                  <label class="col-sm-3 control-label">Salutation</label>
                  <div class="col-sm-6">: <?php echo $examRes['namesub']; ?></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">First Name</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['firstname'];?></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Middle Name</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['middlename'];?></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Last Name</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['lastname'];?></div>
                </div>

                <div class="form-group">
                  <label for="dob" class="col-sm-3 control-label">Date of Birth</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['dateofbirth']; ?></div>
                </div>

                <div class="form-group">
                  <label for="gender" class="col-sm-3 control-label">Gender (M/F)</label>
                  <div class="col-sm-9">: <?php echo $candidate_details['gender']; ?></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Contact Details Mobile</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['mobile_no'];?></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Alternate Mobile No.</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['alt_mobile_no'];?></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Email Id</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['email_id'];?></div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label">Alternate Email Id</label>
                  <div class="col-sm-6">: <?php echo $candidate_details['alt_email_id'];?></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Qualification</label>
                  <div class="col-sm-6">: 
                    <?php if(isset($candidate_details['qualification_type']) && $candidate_details['qualification_type'] == 'Under_Graduate') { echo 'Under Graduate'; }
                    else if ((isset($candidate_details['qualification_type']) && $candidate_details['qualification_type'] == 'Graduate')) { echo 'Graduate'; } ?>
                  </div>
                </div>
              </div>
            </div>
          <?php }

          //echo '<pre>'; print_r($candidate_details); print_r($examRes); echo '</pre>';
          //if(($current_date <= $training_from_date && $current_date <= $date_after_3days) || $exam_edit == 'Yes') 
          if(($current_date <= $date_after_3days) || $exam_edit == 'Yes') 
          {
            $show_form_flag = 1;
            if($exam_edit != 'Yes')
            { ?>
            <div class="alert alert-warning alert-dismissible">
              <?php /* To be filled in with appropriate contents carefully as the same cannot be changed after the locking period i.e. (<?php echo $date_after_3days; ?>) */ ?>

              Below mentioned fields (i.e. Other Details) need to be filled / uploaded / edited carefully with appropriate contents till end of Day - 3 of the Training Period (i.e. by 11.59.59 PM of <?php echo date("d.m.Y",strtotime($date_after_3days)); ?>).<br>No contents can be changed or edited after the Day - 3 (i.e. <?php echo date("d.m.Y",strtotime($date_after_3days)); ?>) of the Training Period. 
            </div>
            <?php } ?>
        
            <div class="box box-info" id="other_details_div">
              <div class="box-header with-border">
                <h3 class="box-title">Other Details</h3>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="address_line1" class="col-sm-3 control-label">Address<span clas
                    s="mandatory-field">*</span></label>
                  <div class="col-sm-4">
                    Line 1<span class="mandatory-field">*</span>
                    <input type="text" class="form-control form2_element" id="addressline1" name="addressline1" placeholder="Address line 1" value="<?php echo $candidate_details['address1'];?>"  autocomplete="off" required maxlength="30" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div> 

                  <div class="col-sm-4">
                    Line 2
                    <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line 2" value="<?php echo $candidate_details['address2'];?>" autocomplete="off" maxlength="30" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div> 
                </div>

                <div class="form-group">
                  <label for="address_line1" class="col-sm-3 control-label"><span class="mandatory-field"></span></label>
                  <div class="col-sm-4">
                    Line 3
                    <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line 3"value="<?php echo $candidate_details['address3'];?>" autocomplete="off" maxlength="30" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div> 

                  <div class="col-sm-4">
                    Line 4
                    <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line 4"value="<?php echo $candidate_details['address4']; ?>" autocomplete="off" maxlength="30" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout">
                    <span class="error"></span>
                  </div> 
                </div>

                <div class="form-group">
                  <label for="state" class="col-sm-3 control-label">State<span class="mandatory-field">*</span></label>
                  <div class="col-sm-4">
                    <select class="form-control form2_element" id="ccstate" name="state" required onchange="get_city_ajax()" >
                      <option value="">Select</option>
                      <?php if(count($states) > 0){ foreach($states as $row1){  ?>
                      <option value="<?php echo $row1['state_code'];?>" <?php if($candidate_details['state'] == $row1['state_code']) { echo 'selected'; } ?>><?php echo $row1['state_name'];?></option>
                      <?php } } ?>
                    </select>                  
                    <input hidden="statepincode" id="statepincode" value="<?php echo set_value('statepincode');?>">
                  </div>                
                </div>

                <div class="form-group">
                  <label for="district" class="col-sm-3 control-label">District <span class="mandatory-field">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control form2_element" id="district" name="district" placeholder="District" required value="<?php echo $candidate_details['district']; ?>"  autocomplete="off" data-parsley-maxlength="30" maxlength="30" data-parsley-trigger="focusin focusout">
                      <span class="error"></span>
                    </div>
                </div>

                <div class="form-group">
                  <label for="city" class="col-sm-3 control-label">City <span class="mandatory-field">*</span></label>
                  <div class="col-sm-4">
                    <select class="form-control city form2_element" id="city" name="city" required >
                      <option value="">Select City</option>
                      <?php if($candidate_details['state'] != "")
                      {
                        $this->db->where('city_master.city_delete', '0');
                        $cities = $this->master_model->getRecords('city_master', array('city_master.state_code'=>$candidate_details['state']));
                        if(count($cities) > 0)
                        {
                          foreach($cities as $cit_res)
                          { ?>
                            <option value="<?php echo $cit_res['city_name']; ?>" <?php if($candidate_details['city'] == $cit_res['city_name']) { echo 'selected'; } ?>><?php echo $cit_res['city_name']; ?></option>
                          <?php }
                        }
                      } ?>
                    </select>
                    <span class="error"></span>
                  </div>

                  <label style="width: 86px;" for="pincode" class="col-sm-2 control-label">Pincode <span class="mandatory-field">*</span></label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control form2_element" id="pincode" name="pincode" placeholder="Pincode" required value="<?php echo $candidate_details['pincode']; ?>" onkeypress="return(isNumber(event));"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dracheckpin data-parsley-type="number" autocomplete="off"  data-parsley-trigger="focusin focusout"> 
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
                      if( $drainstdata ) {
                          $institute_name = $drainstdata['institute_name'];   
                          $institute_code = $drainstdata['institute_code'];
                      } ?>
                    <label for="inst_name" class="col-sm-3 control-label">Name Of Training Institute </label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" placeholder="Name Of Training Institute" value="<?php echo $institute_name; ?>" autocomplete="off" readonly="readonly">
                      <input type="hidden" class="form-control" value="<?php echo $institute_code; ?>" name="inst_code" autocomplete="off" readonly="readonly">
                    </div>
                  </div>
                            
                  <div class="form-group">
                    <label for="center" class="col-sm-3 control-label">Centre Name </label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" placeholder="Center Name"  value="<?php echo $batch_details[0]['city_name'] ;?>" autocomplete="off" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="center" class="col-sm-3 control-label">State </label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" id="inst_state_name" name="inst_state_name" value="<?php echo $batch_details[0]['state_name']; ?>" autocomplete="off" readonly>
                      <input type="hidden" class="form-control" id="inst_state_code" name="inst_state_code" value="<?php echo $batch_details[0]['state_code']; ?>" autocomplete="off">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="center" class="col-sm-3 control-label">District </label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" id="inst_district_name" name="inst_district_name" value="<?php echo $batch_details[0]['district']; ?>" autocomplete="off" readonly>
                      <input type="hidden" class="form-control" id="inst_district" name="inst_district" value="<?php echo $batch_details[0]['district']; ?>" autocomplete="off">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="center" class="col-sm-3 control-label">City </label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" id="inst_city_name" name="inst_city_name" value="<?php echo $batch_details[0]['city_name']; ?>" autocomplete="off" readonly>
                      <input type="hidden" class="form-control" id="inst_city_id" name="inst_city_id" value="<?php echo $batch_details[0]['city']; ?>" autocomplete="off">
                    </div>
                  </div>

                  <div class="form-group">
                    <input type="hidden" class="form-control" id="inst_pincode" name="inst_pincode" value="<?php echo $batch_details[0]['pincode']; ?>" autocomplete="off">
                  </div>
                                  
                  <div class="form-group">
                    <label for="training_period" class="col-sm-3 control-label">Training Period  </label>
                    <div class="col-sm-2">
                      From
                      <input type="text" class="form-control" value="<?php echo $batch_details[0]['batch_from_date'] ;?>" autocomplete="off" readonly="readonly"/>
                      <span class="error"></span>
                    </div> 

                    <div class="col-sm-2">
                      To
                      <input type="text" class="form-control"  placeholder="Training To Date"  value=" <?php echo $batch_details[0]['batch_to_date'] ;?>" autocomplete="off"  readonly="readonly"/>
                      <span class="error"></span>
                    </div> 
                  </div>

                  <?php 
                  if (strpos($redirection_path, 'allapplicants') !== false) 
                  { ?>
                    <div class="form-group">
                      <label for="center" class="col-sm-3 control-label">Exam Date </label>
                      <div class="col-sm-5">
                        <p><b></b><?php echo $exam_date[0]['exam_date']; ?></b></p>
                      </div>
                    </div>

                    <?php /********* START : FOR PHYSICAL MODE *************************/
                    if($chk_exam_mode == 'PHYSICAL')
                    { ?>
                      <div class="form-group">
                        <label for="center" class="col-sm-3 control-label">Exam Center Name <span class="mandatory-field">*</span></label>
                        <div class="col-sm-5">
                          <select required class="form-control" name="exam_center"  >
                          <option value="">Select</option>
                          <?php 
                            if(count($center_master) > 0){      
                              foreach($center_master as $centers){ ?>   
                                <option value="<?php echo $centers['center_code'];?>" <?php if($centers['center_code']==$examRes['exam_center_code']){echo  'selected="selected"';}?>><?php echo $centers['center_name'];?>
                                </option>
                          <?php } } ?>
                          </select>             
                        </div>
                      </div>
                    <?php }
                    /********* END : FOR PHYSICAL MODE *************************/ ?>

                    <div class="form-group">
                      <label for="center" class="col-sm-3 control-label">Exam medium <span class="mandatory-field">*</span></label>
                        <div class="col-sm-5">
                          <select required class="form-control" name="exam_medium"  >
                            <option value="">Select</option>
                            <?php if(count($medium_master) > 0){
                              foreach($medium_master as $mediums){     ?>
                                <option value="<?php echo $mediums['medium_code'];?>" <?php if($mediums['medium_code']==$examRes['exam_medium']){echo  'selected="selected"';}?>><?php echo $mediums['medium_description'];?>
                                  </option>
                              <?php } } ?>
                          </select>
                        </div>
                      </div>
                  <?php }?>
                
                  <?php if(count($subject_master_data) > 0) { ?>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Examination Date <span class="mandatory-field">*</span></label>
                      <div class="col-sm-5"><?php echo date("d-M-Y", strtotime($subject_master_data[0]['exam_date'])); ?>                           
                      </div>
                    </div>
                  <?php } ?>                              
                </div>
              </div>
                    
              <div class="box box-info custom_sub_header" id="photo_div">
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
                  </ol>
                  
                  <div class="form-group idproof-wrap">
                    <label for="id_proof" class="col-sm-3 control-label">Select Id Proof <span class="mandatory-field">*</span></label>                                    
                    <div class="col-sm-5">
                      <?php if (count($idtype_master) > 0) 
                      {
                        $i = 1;
                        foreach ($idtype_master as $idrow) 
                        { ?>
                          <label class="radio_btn_label">
                            <input name="idproof" value="<?php echo $idrow['id']; ?>" type="radio" class="btn_check form2_element" <?php if(isset($examRes['idproof']) && $examRes['idproof'] == $idrow['id']){ echo 'checked="checked"'; } ?> required data-parsley-errors-container="#idproof_error"><?php echo $idrow['name']; ?>
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
                      <input type="text" class="form-control form2_element" id="idproof_no" name="idproof_no" placeholder="Id Proof Number." value="<?php echo $examRes['idproof_no'];?>" autocomplete="off" required data-parsley-errors-container="#idproof_no_error" data-parsley-idproof_exist data-parsley-trigger= focusout data-parsley-idproof_exist-message="This ID Proof is already Exists.">
                      <span class="note" id="idproof_no_note" style="display:block"></span>
                      <span class="note-error" id="idproof_no_error"></span>
                    </div> 
                  </div>

                  <?php 
                  $style1 = $style2 = $style3 = $style4 = '';
                  $required1 = $required2 = $required3 = $required4 = '';
                  //print_r($examRes);
                  if(!empty($examRes['idproofphoto']))
                  {
                    $style1 = 'style="display: none;"';
                    $required1 = '"data-parsley-required"';
                  }

                  if(!empty($examRes['quali_certificate']))
                  {
                    $style2 = 'style="display: none;"';
                    $required2 = 'data-parsley-required';
                  }

                  if(!empty($examRes['scannedphoto']))
                  {
                    $style3 = 'style="display: none;"';
                    $required3 = 'data-parsley-required';
                  }

                  if(!empty($examRes['scannedsignaturephoto']))
                  {
                    $style4 = 'style="display: none;"';
                    $required4 = 'data-parsley-required';
                  } ?>
                
                  <div class="form-group">
                    <div id="exist_draidproofphoto" <?php echo $style1; ?>>
                      <label for="id_proof" class="col-sm-3 control-label">Proof of Identity <span class="mandatory-field">*</span></label>
                      <div class="col-sm-5">
                        <input type="file" class="form-control" name="draidproofphoto" id="draidproofphoto" autocomplete="off" onchange="validateFile(event, 'idproof_size_error', 'idproof_preview', '25kb')" <?php if(empty($examRes['idproofphoto'])){ echo 'data-parsley-required'; }   ?>>
                        <input type="hidden" autocomplete="false" id="hiddenidproofphoto" name="hiddenidproofphoto">
                        <span id="draidproofphoto_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                        <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png Files upto 25KB in 100*120 pixel dimensions.</span></br>
                        <span class="note-error" id="idproof_size_error"></span>
                        <!-- <div id="error_dob"></div> -->
                        <br>
                        <span class="dob_proof_text" style="display:none;"></span>
                        <span class="error"></span>
                      </div>
                      <img id="idproof_preview" height="100" width="100" src="/assets/images/default1.png"/>
                    </div>
                
                    <?php if(!empty($examRes['idproofphoto'])){?> 
                      <div class="col-sm-12" id="idproofphoto_show">
                        <div class="form-group">
                          <label for="exampleInputName1" class="col-sm-3 control-label"><b>Proof of Identity </b></label>
                          <div class="col-sm-5">
                            <img style="width: 25%;height: 25%;" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['idproofphoto']; ?>" />
                            <button type="button" value="Remove" id="btn_idproofphoto_remove" class="btn-danger" onclick="removeImg1('<?php echo $examRes['regid']; ?>')"><span class="fa fa-times"></span></button>
                            <input type="hidden" name="old_idproofphoto" id="old_idproofphoto" value="<?php echo $examRes['idproofphoto']; ?>">
                          </div>
                        </div>
                      </div>
                    <?php } ?>
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
                          <input type="radio" class="radiocls form2_element" id="tenth" name="education_qualification" value="tenth" <?php if(isset($examRes['qualification']) && $examRes['qualification'] == 'tenth') { echo 'checked="checked"'; }; ?> required data-parsley-errors-container="#education_qualification_error">10th Pass
                        </label>
                        <label class="radio_btn_label">
                          <input type="radio" class="radiocls form2_element" id="twelth" name="education_qualification" value="twelth" <?php if(isset($examRes['qualification']) && $examRes['qualification'] == 'twelth') { echo 'checked="checked"'; }; ?> required data-parsley-errors-container="#education_qualification_error">12th Pass
                        </label>
                      <?php } ?>
                      
                      <label class="radio_btn_label">  
                        <input type="radio" class="radiocls form2_element" id="graduate" name="education_qualification" value="graduate" <?php if(isset($examRes['qualification']) && $examRes['qualification'] == 'graduate') { echo 'checked="checked"'; }; ?> required data-parsley-errors-container="#education_qualification_error">Graduation
                      </label>
                      <label class="radio_btn_label">  
                        <input type="radio" class="radiocls form2_element" id="post_graduate" name="education_qualification" value="post_graduate" <?php if(isset($examRes['qualification']) && $examRes['qualification'] == 'post_graduate') { echo 'checked="checked"'; }; ?> required data-parsley-errors-container="#education_qualification_error">Post Graduation
                      </label>
                      <span class="error" id="education_qualification_error"></span>
                    </div>
                  </div>
                
                  <div class="form-group">
                    <div id="exist_qualicertificate" <?php echo $style2; ?>>
                      <label for="quali_certificate" class="col-sm-3 control-label">Qualification Certificate <span class="mandatory-field">*</span></label>
                      <div class="col-sm-5" id="exist_qualicertificate">
                        <input type="file" name="qualicertificate" id="qualicertificate" class="form-control" autocomplete="off" onchange="validateFile(event, 'error_qualicert', 'qualicertificate_preview', '100kb')" <?php if(empty($examRes['quali_certificate'])){ echo 'data-parsley-required'; }?>> (As per educational qualification selected above)
                        <input type="hidden" autocomplete="false" id="hiddenqualicertificate" name="hiddenqualicertificate"></br>
                        <span id="qualicertificate_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                        <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png Files upto 100KB in 100*120 pixel dimensions.</span></br>
                        <span class="note-error" id="error_qualicert"></span>
                        <br>
                        <span class="qualicert_text" style="display:none;"></span>
                        <span class="error"></span>
                      </div>
                      <img id="qualicertificate_preview" height="100" width="100" src="/assets/images/default1.png"/>
                    </div>

                    <?php if(!empty($examRes['quali_certificate'])){?> 
                      <div class="col-sm-12" id="quali_certificate_show">
                        <div class="form-group">
                          <label for="exampleInputName1" class="col-sm-3 control-label"><b>Qualification Certificate</b></label>
                          <div class="col-sm-5">
                            <img style="width: 25%;height: 25%;" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['quali_certificate']; ?>" />
                            <button type="button" value="Remove" id="btn_qualicertificate_remove" class="btn-danger" onclick="removeImg2('<?php echo $examRes['regid']; ?>')"><span class="fa fa-times"></span></button>
                            <input type="hidden" name="old_quali_certificate" id="old_quali_certificate" value="<?php echo $examRes['quali_certificate']; ?>">
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
          
                  <div class="form-group">
                    <div id="exist_drascannedphoto" <?php echo $style3; ?>>
                      <label for="photograph" class="col-sm-3 control-label">Passport Photograph of the Candidate <span class="mandatory-field">*</span></label>
                      <div class="col-sm-5" id="exist_drascannedphoto">
                        <input type="file" name="drascannedphoto" id="drascannedphoto" class="form-control" autocomplete="off" onchange="validateFile(event, 'scannedphoto_error', 'scanphoto_preview', '20kb')"  <?php if(empty($examRes['scannedphoto'])){ echo 'data-parsley-required'; }?>>
                        <input type="hidden" autocomplete="false" id="hiddenphoto" name="hiddenphoto">
                        <span id="drascannedphoto_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                        <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png Files upto 20KB in 100*120 pixel dimensions.</span></br>
                        <span class="note-error" id="scannedphoto_error"></span>
                        <br>

                        <span class="photo_text" style="display:none;"></span>
                        <span class="error"></span>
                      </div>
                      <img id="scanphoto_preview" height="100" width="100" src="/assets/images/default1.png"/>
                    </div>

                    <?php if(!empty($examRes['scannedphoto'])){?> 
                      <div class="col-sm-12" id="scannedphoto_show">
                        <div class="form-group">
                          <label for="exampleInputName1" class="col-sm-3 control-label"><b>Passport Photograph of the Candidate </b></label>
                          <div class="col-sm-5">
                            <img style="width: 25%;height: 25%;" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['scannedphoto']; ?>" />
                            <button type="button" value="Remove" id="btn_scannedphoto_remove" class="btn-danger" onclick="removeImg3('<?php echo $examRes['regid']; ?>')"><span class="fa fa-times"></span></button>
                            <input type="hidden" name="old_scannedphoto" id="old_scannedphoto" value="<?php echo $examRes['scannedphoto']; ?>">
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                
                  <div class="form-group">
                    <div id="exist_drascannedsignature" <?php echo $style4; ?>>
                      <label class="col-sm-3 control-label"> Full Signature of the Candidate <span class="mandatory-field">*</span></label>
                      <div class="col-sm-5" id="exist_drascannedsignature">
                        <input type="file" name="drascannedsignature" id="drascannedsignature" class="form-control" autocomplete="off" onchange="validateFile(event, 'error_signature', 'signature_preview', '20kb')" <?php if(empty($examRes['scannedsignaturephoto'])){ echo 'data-parsley-required'; }?>>
                        <input type="hidden" autocomplete="false" id="hiddenscansignature" name="hiddenscansignature">
                        <span id="drascannedsignature_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                        <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png Files upto 20KB in 100*120 pixel dimensions.</span></br>
                        <span class="note-error" id="error_signature"></span>
                        <span class="signature_text" style="display:none;"></span>
                        <span class="error"></span>
                      </div>
                      <img id="signature_preview" height="100" width="100" src="/assets/images/default1.png"/>
                    </div>

                    <?php if(!empty($examRes['scannedsignaturephoto'])){?> 
                      <div class="col-sm-12" id="scannedsignaturephoto_show">
                        <div class="form-group">
                          <label for="exampleInputName1" class="col-sm-3 control-label"><b>Full Signature of the Candidate  </b></label>
                          <div class="col-sm-5">
                            <img style="width: 25%;height: 25%;" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['scannedsignaturephoto']; ?>" />
                            <button type="button" value="Remove" id="btn_scannedsignaturephoto_remove" class="btn-danger" onclick="removeImg4('<?php echo $examRes['regid']; ?>')"><span class="fa fa-times"></span></button>
                            <input type="hidden" name="old_scannedsignaturephoto" id="old_scannedsignaturephoto" value="<?php echo $examRes['scannedsignaturephoto']; ?>">
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Aadhar Card No.<span class="mandatory-field"></span></label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="aadhar_no" name="aadhar_no" placeholder="Aadhar card No." autocomplete="off" value="<?php echo $examRes['aadhar_no'];?>" onkeypress="return isNumber(event)" data-parsley-minlength="12" data-parsley-minlength-message="Please enter 12 digit Aadhar No" maxlength="12" data-parsley-errors-container="#aadhar_no_error" data-parsley-type="digits" data-parsley-aadhar_no_exist data-parsley-trigger= focusout data-parsley-aadhar_no_exist-message="This Adhar no is already Exists.">
                      <span class="note">Note: Please Enter Aadhar no like: 666635870783</span></br>
                      <span class="error note-error" id="aadhar_no_error"></span>
                    </div>
                  </div>                    
                </div>
              </div>
              
              <input type="hidden" name="registrationtype" value="<?php echo $candidate_details['registrationtype']; ?>">
              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-3">
                  <input type="button" class="btn btn-info btn_submit" name="btnSubmit2" id="btnSubmit2" value="Update II">
                </div>
              </div>
            </div>
          <?php } //}?>
          
          <?php if(count($DraCandidateLogs) > 0)
          { ?>
            <div class="box" style="margin:15px 0 0 0">
              <div class="box-header with-border">
                <h3 class="box-title">Candidate Logs</h3>
                <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button></div>
              </div>
              
              <div class="box-body ">
                <div class="table-responsive">
                  <table id="listitems_logs" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Sr.No.</th>
                        <th>Action</th>
                        <th>Action Date </th>
                      </tr>
                    </thead>

                    <tbody class="no-bd-y" id="list222">
                      <?php $i=1;
                      foreach($DraCandidateLogs as $res_log)
                      { ?>   
                        <tr>
                          <td class="text-center"><?php echo $i; ?></td>
                          <td><?php echo $res_log['log_title']; ?></td>
                          <td><?php echo date("d-M-Y h:i:s A", strtotime($res_log['created_on'])); ?></td>
                        </tr>                              
                        <?php $i++;  
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php }  ?>

          <?php 
          if($show_form_flag == 0) { 
            redirect(site_url('iibfdra/TrainingBatches/candidate_list/'.$batchId)); 
          } ?>
        </form>
      </div>
    </div>
  </section>
</div>
  
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<?php /* <script src="<?php echo base_url() ?>js/validation_dra_batch.js"></script> */ ?>

<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

<script type="text/javascript">
  var errCnt = 0;
  var base_url = '<?php echo base_url(); ?>';
  var exam_edit = '<?php echo $exam_edit; ?>';

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

  var batch_id = $('#batchid').val();
  
  function is_mobile_no() 
  {
    return new Promise(function(resolve, reject) {
    var mobileStatus = false;
    // var mobile   = evt.target.value;
    var mobile = $('#mobile_no').val();
    $.ajax({
      url: base_url + "iibfdra/TrainingBatches/check_mobile_no",
      method: "POST",
      async: true,
      data: {
        mobile_no: mobile,
        regId:'<?php echo $candidate_details['regid']; ?>',
        batch_id:batch_id
      },
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 'success') {
          $('#mobile_no_error').html(response.massege);
          resolve(true);
        } else {
          $('#mobile_no_error').html(response.massege);
          resolve(false);
        }
      }
    });
    });
  }

  function is_idproof() 
  {
    return new Promise(function(resolve, reject) {
    var idStatus = false;
    // var idproof   = evt.target.value;
    var idproof = $('#idproof_no').val();
    $.ajax({
      url: base_url + "iibfdra/TrainingBatches/check_idproof",
      method: "POST",
      async: false,
      data: {
        idproof: idproof,
        regId:'<?php echo $candidate_details['regid']; ?>',
        batch_id:batch_id
      },
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 'success') {
          $('#idproof_no_error').html(response.massege);
          resolve(true);
        } else {
          $('#idproof_no_error').html(response.massege);
          resolve(false);
        }
      }
    });
    });
  }

  function is_email_check() 
  {
    // var email   = evt.target.value;
    return new Promise(function(resolve, reject) {
    var emailStatus = false;
    var email   = $('#email').val();  
    $.ajax({
      url: base_url + "iibfdra/TrainingBatches/check_email",
      method: "POST",
      async: true,
      data: {
        email: email,
        regId:'<?php echo $candidate_details['regid']; ?>',
        batch_id:batch_id
      },
      dataType: 'JSON',
      success: function(emailResponse) {
        if (emailResponse.status == 'success') {
          $('#email_error').html(emailResponse.massege);
          resolve(true);
        } else {
          $('#email_error').html(emailResponse.massege);
          resolve(false);
        }
      }
    });
    });
  }

  function is_aadhar_no_check(evt) 
  {
    return new Promise(function(resolve, reject) {
    var aadharStatus = false;
    // var aadhar_no   = evt.target.value;
    var aadhar_no   = $('#aadhar_no').val();  
    $.ajax({
      url: base_url + "iibfdra/TrainingBatches/check_aadhar_no",
      method: "POST",
      async: false,
      data: {
        aadhar_no: aadhar_no,
        regId:'<?php echo $candidate_details['regid']; ?>',
        batch_id:batch_id
      },
      dataType: 'JSON',
      success: function(aadharResponse) {
        if (aadharResponse.status == 'success') {
          $('#aadhar_no_error').html(aadharResponse.massege);
          resolve(true);
        } else {
          $('#aadhar_no_error').html(aadharResponse.massege);
          resolve(false);
        }
      }
    });
    });
  }


  function get_city_ajax()
  {
    var state_code = $("#ccstate").val();
    if (state_code) 
    {
      $("#loading").show();
      $.ajax({
        type: 'POST',
        async: false,
        url: site_url + 'iibfdra/TrainingBatches/getCity',
        data: 'state_code=' + state_code,
        success: function(html) {
          //alert(html);
          $('#city').show();
          $('#city').html(html);
          $("#loading").hide();
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
    //console.log('selected_val'+selected_val);
    if(selected_val == 'Under_Graduate')
    {
      $("#graduate").prop('checked', false);
      $("#post_graduate").prop('checked', false);

      //$("#tenth").prop('checked', false);
      //$("#twelth").prop('checked', false);

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
    $('#btnSubmit1').click( function () 
    {
      $("#submit_form").val('1');
      $('.form2_element').removeAttr('required');            
      
      $('#draidproofphoto').removeAttr('data-parsley-required');            
      $('#qualicertificate').removeAttr('data-parsley-required');            
      $('#drascannedphoto').removeAttr('data-parsley-required');            
      $('#drascannedsignature').removeAttr('data-parsley-required');            
      
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

      $('#idproof_no').removeAttr('data-parsley-idproof_exist');
      $('#aadhar_no').removeAttr('data-parsley-minlength');
      $('#aadhar_no').removeAttr('maxlength');
      $('#aadhar_no').removeAttr('data-parsley-type');
      //$('#aadhar_no').removeAttr('data-parsley-pattern');
      $('#aadhar_no').removeAttr('data-parsley-aadhar_no_exist');      
      
      $('#draExamAddFrm').parsley().validate();
      $("#draExamAddFrm").submit();
    });

    $('#btnSubmit2').click( async function () 
    {  
      $("#submit_form").val('2');

      var old_idproofphoto = $("#old_idproofphoto").val();
      if(old_idproofphoto == "") { $('#draidproofphoto').attr('data-parsley-required', 'true'); }

      var old_quali_certificate = $("#old_quali_certificate").val();
      if(old_quali_certificate == "") { $('#qualicertificate').attr('data-parsley-required', 'true'); }

      var old_scannedphoto = $("#old_scannedphoto").val();
      if(old_scannedphoto == "") { $('#drascannedphoto').attr('data-parsley-required', 'true'); }

      var old_scannedsignaturephoto = $("#old_scannedsignaturephoto").val();
      if(old_scannedsignaturephoto == "") { $('#drascannedsignature').attr('data-parsley-required', 'true'); }

      //$("#idproof_size_error").html('');
      //$("#error_qualicert").html('');
      //$("#scannedphoto_error").html('');
      //$("#error_signature").html('');

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
      $('#idproof_no').attr('data-parsley-idproof_exist', '');
      $('#aadhar_no').attr('data-parsley-minlength', '12');
      $('#aadhar_no').attr('maxlength', '12');
      $('#aadhar_no').attr('data-parsley-type', 'digits');
      //$('#aadhar_no').attr('data-parsley-pattern', '/^\d{12}$/');
      $('#aadhar_no').attr('data-parsley-aadhar_no_exist', '');

      $('#idproof_no').parsley().validate();
      $('#draExamAddFrm').parsley().validate();
      $("#draExamAddFrm").submit();      
    });
    //END : ON CLICK ON TOP FORM SUBMIT BUTTON, VALIDATE ONLY TOP FORM FIELDS
    //END : ON CLICK ON BOTTOM FORM SUBMIT BUTTON, VALIDATE BOTH FORM FIELDS

    if(exam_edit == 'Yes')
    {
      $('#basic_details_div input[type="text"]').attr("disabled", true);
      $('#basic_details_div input[type="radio"]').attr("disabled", true);
      $('#basic_details_div input[name="gender"]').attr("disabled", true);
      $('#sel_namesub').attr("disabled", true);
     /* $('#photo_div input[type="text"]').attr("disabled", true);
      $('#photo_div input[type="radio"]').attr("disabled", true);
      $('#addressline1').attr("disabled", true);
      $('#addressline2').attr("disabled", true);
      $('#addressline3').attr("disabled", true);
      $('#addressline4').attr("disabled", true);*/
    }

    //$('.minimal').removeAttr('disabled');
    //keep idproof and exam mode diabled which are not checked
    // $("input[name='idproof']:not(:checked)").attr('disabled', true);

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

    /*
    $("#dateofbirth").dateDropdowns({
      submitFieldName: 'dob1',
      minAge: 0,
      maxAge:100
    });

    $("#dateofbirth").change(function()
    {
      var sel_dob = $("#dateofbirth").val();
      if(sel_dob!='')
      {
        var dob_arr = sel_dob.split('-');
        if(dob_arr.length == 3)
        {
          chkage(dob_arr[2],dob_arr[1],dob_arr[0]); 
        }
        else
        { alert('Select valid date'); }
      }
    }); */

    $("body").on("contextmenu",function(e){
        return false;
    });
  });

  $(document).on('keyup', '#idproof_no', function() { validate_id_proof(); });
  $(document).on('change', '#idproof_no', function() { validate_id_proof(); });
  
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

  var proof_type = $("input[type='radio'].btn_check:checked").val();
  check_proof_type(proof_type); 

  $(document).on('change', '.btn_check', function()
  {
    var proof_type = $(this).val();
    $('#idproof_no').val("");
    check_proof_type(proof_type); 

  });

  function check_proof_type(proof_type) 
  {
    //console.log('proof_type--'+proof_type);
    $('#idproof_no_error').text("");    

    if(proof_type == 1){
      $('#idproof_no_note').text('Note: Please Enter Aadhar no like: 666635870783');

      $("#idproof_no").attr({
        "onkeypress" : "return isNumber(event)",
        "maxlength" : "12"
      });
    }

    if(proof_type == 2){
      $('#idproof_no_note').text('Note: Please Enter Driving License No like: MH27301234761024');

      $("#idproof_no").attr({
        "onkeypress" : "return alphanumeric(event)",
        "maxlength" : "15"
      });
    }

    if(proof_type == 4){
      $('#idproof_no_note').text('Note: Please Enter maximum 10 digit Employee number');

      $("#idproof_no").attr({
        "onkeypress" : "return isNumber(event)",
        "maxlength" : "10"
      });
    }

    /*if(proof_type == 4){
      $('#idproof_no_note').text('');
    }*/
    
    if(proof_type == 5){                                          
      $('#idproof_no_note').text('Note: Please Enter PAN no like: ABCTY1234D');

      $("#idproof_no").attr({
        "onkeypress" : "return alphanumeric(event)",
        "maxlength" : "10"
      });
    }

    if(proof_type == 6){
      $('#idproof_no_note').text('Note: Please Enter Passport like: J8369845');

      $("#idproof_no").attr({
        "onkeypress" : "return alphanumeric(event)",
        "maxlength" : "8"
      });
    }
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
  var batch_id = $('#batchid').val();
  $('#email').parsley();
  window.ParsleyValidator.addValidator('email_exist', 
  {
    validateString: function(value) {
      return $.ajax({
        url: base_url + "iibfdra/TrainingBatches/check_email",
        method: "POST",
        async: false,
        data: {
          email: value,
          batch_id:batch_id,
          regId:'<?php echo $candidate_details['regid']; ?>'
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
        url: base_url + "iibfdra/TrainingBatches/check_mobile_no",
        method: "POST",
        //async: false,
        data: {
          mobile_no: value,
          batch_id:batch_id,
          regId:'<?php echo $candidate_details['regid']; ?>'
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
        url: base_url + "iibfdra/TrainingBatches/check_aadhar_no",
        method: "POST",
        //async: false,
        data: {
          aadhar_no: value,
          batch_id:batch_id,
          regId:'<?php echo $candidate_details['regid']; ?>'
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
        url: base_url + "iibfdra/TrainingBatches/check_idproof",
        method: "POST",
        //async: false,
        data: {
          action: 'edit',
          batch_id:batch_id,
          idproof: value,
          regId:'<?php echo $candidate_details['regid']; ?>'
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
      url:site_url+'iibfdra/DraExam/checkpin/',
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

  function removeImg1(regid)
  {
    $("#idproofphoto_show").hide();
    $('#exist_draidproofphoto').show();
    $("#old_idproofphoto").val("");
    $('#draidproofphoto').attr('data-parsley-required','true');
    $('#draidproofphoto').attr('data-parsley-errors-container',"#idproof_size_error");    
  }

  function removeImg2(regid)
  {
    $("#quali_certificate_show").hide();
    $('#exist_qualicertificate').show();
    $("#old_quali_certificate").val("");
    $('#qualicertificate').attr('data-parsley-required','true');
    $('#qualicertificate').attr('data-parsley-errors-container',"#error_qualicert");    
  }

  function removeImg3(regid)
  {
    $("#scannedphoto_show").hide();
    $('#exist_drascannedphoto').show();
    $("#old_scannedphoto").val("");
    $('#drascannedphoto').attr('data-parsley-required','true');
    $('#drascannedphoto').attr('data-parsley-errors-container',"#scannedphoto_error");    
  }

  function removeImg4(regid)
  {
    $("#scannedsignaturephoto_show").hide();    
    $('#exist_drascannedsignature').show();
    $("#old_scannedsignaturephoto").val("");
    $('#drascannedsignature').attr('data-parsley-required','true');
    $('#drascannedsignature').attr('data-parsley-errors-container',"#error_signature");
  }

  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
  }

  function onlyAlphabets(key) {
      var keycode = (key.which) ? key.which : key.keyCode;
      //alert(keycode);
      if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 32)  
      {     
        return true;    
      }
      else
      {
        return false;
      }
          
  }

  function alphanumeric(key){
      var keycode = (key.which) ? key.which : key.keyCode;

      if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 8 || keycode == 32 || (keycode >= 48 && keycode <= 57))
      {     
        return true;    
      }
      else
      {
        return false;
      }
  }

  $.ajax({
    type: "POST",
    url: "<?php echo site_url('iibfdra/TrainingBatches/remove_custom_session'); ?>",
    cache: false,
    dataType: 'JSON'
  });
</script>