<?php
  
  $candidate_data = $this->master_model->getRecords('dra_members',array('regid' => $regId));
  //print_r($candidate_data); die;
  $candidateArr = $candidate_data[0];

  if(count($candidate_data) > 0){
    $namesub = !empty($candidateArr['namesub'])?$candidateArr['namesub']:'';
    $firstname = !empty($candidateArr['firstname'])?$candidateArr['firstname']:'';
    $middlename = !empty($candidateArr['middlename'])?$candidateArr['middlename']:'';
    $lastname = !empty($candidateArr['lastname'])?$candidateArr['lastname']:'';
    $dateofbirth = !empty($candidateArr['dateofbirth'])?$candidateArr['dateofbirth']:'';
    $gender = !empty($candidateArr['gender'])?$candidateArr['gender']:'';
    $email_id = !empty($candidateArr['email_id'])?$candidateArr['email_id']:'';
    $mobile_no = !empty($candidateArr['mobile_no'])?$candidateArr['mobile_no']:'';
    $alt_email_id = !empty($candidateArr['alt_email_id'])?$candidateArr['alt_email_id']:'';
    $alt_mobile_no = !empty($candidateArr['alt_mobile_no'])?$candidateArr['alt_mobile_no']:'';
    $qualification_type = !empty($candidateArr['qualification_type'])?$candidateArr['qualification_type']:'';
  }
  
?>
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
.mandatory-field, .required-spn {
	color:#F00;
}

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>DRA Training Applicants Form</h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
      <!-- Horizontal Form -->
      <?php 
        //print_r($batch_details);
        $current_date = date('Y-m-d');
        //echo '---'.$current_date .'<='. $date_after_3days;
        if($current_date <= $training_from_date){ ?> 

          <form class="form-horizontal" autocomplete="off" name="draExamAddFrm" id="draExamAddFrm"  method="post"  enctype="multipart/form-data" data-parsley-validate="parsley">
            <div class="alert alert-warning alert-dismissible">
              To be filled in with appropriate contents carefully as the same cannot be changed after the locking period i.e. (<?php echo $training_from_date; ?>)
            </div>

          	<div class="box box-info" id="basic_details_div">
          		<div class="box-header with-border">
            		<h3 class="box-title">Basic Details</h3>
                <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/TrainingBatches/" class="btn btn-warning">Back</a> </div>
          		</div>
            	<!-- /.box-header -->
            	<!-- form start -->
            	<div class="box-body">
               
            	 <?php if($this->session->flashdata('error')!=''){?>
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('error'); ?>
                  </div>
                <?php } if($this->session->flashdata('success')!=''){ ?>
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo $this->session->flashdata('success'); ?>
                  </div>
                  <?php } 
                  if(validation_errors()!=''){?>
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo validation_errors(); ?>
                  </div>
                <?php } ?> 

                
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Registration No.</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="Registration No." maxlength="10" value="<?php echo set_value('reg_no');?>" onkeypress="return isNumber(event)" autocomplete="off"/>
                    <input type="hidden" autocomplete="false" name="memtype" value="<?php echo set_value('memtype');?>" maxlength="6" id="memtype"  />
                    <input type="hidden" autocomplete="false" name="membertype" value="<?php echo ( set_value('membertype') ) ? set_value('membertype') : 'normal_member';?>" id="membertype" />
                    <input type="hidden" autocomplete="false" name="examcd" value="<?php echo $data_examcode;?>" />
                    <input type="hidden" autocomplete="false" id="batchid" name="bid" value="<?php echo set_value('bid'); ?><?php echo $bid ;?>" />
                    <span class="note-error" id="registartion_no_error"></span>
                  </div>(Only for re-exam)
                  <button name="get_details" class="dra-get-memdetails">Get Details</button>
                </div>     

                <div class="form-group">
                  <label for="first_name" class="col-sm-3 control-label">Name <span class="mandatory-field">*</span></label>
                  <div class="col-sm-2">
                    Salutation <span class="mandatory-field">*</span>
                    <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                      <option value="">Select</option>
                      <option value="Mr." <?php if(isset($namesub) && $namesub == 'Mr.') { echo 'selected="selected"'; } ?>>Mr.</option>
                      <option value="Mrs." <?php if(isset($namesub) && $namesub == 'Mrs.') { echo 'selected="selected"'; } ?>>Mrs.</option>
                      <option value="Ms." <?php if(isset($namesub) && $namesub == 'Ms.') { echo 'selected="selected"'; } ?>>Ms.</option>
                      <option value="Dr." <?php if(isset($namesub) && $namesub == 'Dr.') { echo 'selected="selected"'; } ?>>Dr.</option>
                      <option value="Prof." <?php if(isset($namesub) && $namesub == 'Prof.') { echo 'selected="selected"'; } ?>>Prof.</option>
                    </select>
                  </div>
                      
                  <div class="col-sm-4">
                    First Name <span class="mandatory-field">*</span>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo $firstname;?>" data-parsley-pattern="/^[a-zA-Z-. ]+$/" maxlength="30" autocomplete="off"  onkeypress="return onlyAlphabets(event)">
                    <span class="note" id="firstname">Note: You can Enter maximum 30 Characters</span></br>
                    <span class="error"><?php //echo form_error('middlename');?></span>
                 </div>
                </div>
                            
                <div class="form-group">
                  <label for="address_line3" class="col-sm-3 control-label address_fields"></label>
                  <div class="col-sm-3">
                    Middle Name
                    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" value="<?php echo $middlename;?>" data-parsley-pattern="/^[a-zA-Z-. ]+$/" maxlength="30" autocomplete="off"  onkeypress="return onlyAlphabets(event)">
                    <span class="note" id="middlename">Note: You can Enter maximum 30 Characters</span></br>
                    <span class="error"><?php //echo form_error('middlename');?></span>
                  </div>
                
                  <div class="col-sm-3">
                     Last Name <span class="mandatory-field"></span>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $lastname;?>" data-parsley-pattern="/^[a-zA-Z-. ]+$/" maxlength="30" autocomplete="off"  onkeypress="return onlyAlphabets(event)" >
                    <span class="note" id="lastname">Note: You can Enter maximum 30 Characters</span></br>
                    <span class="error"><?php //echo form_error('lastname');?></span>
                    <!-- <span class="note" id="faculty_name_error">(Max 30 Characters)</span> -->
                  </div>
                </div>
                            
                <div class="form-group">
                  <label for="dob" class="col-sm-3 control-label">Date of Birth <span class="mandatory-field">*</span></label>
                  <div class="col-sm-2 example">
                    <input type="hidden" autocomplete="false" id="dateofbirth" name="dob" required value="<?php echo $dateofbirth;?>">
                    <?php 
                      $min_year = date('Y', strtotime("- 18 year"));
                      $max_year = date('Y', strtotime("- 60 year"));
                    ?>
                    <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                    <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                    <span id="dob_error" class="note-error"></span>
                          
                    <span class="error"><?php //echo form_error('dob');?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="gender" class="col-sm-3 control-label">Gender (M/F) <span class="mandatory-field">*</span></label>
                  <div class="col-sm-2">
                      <input type="radio" class="minimal" id="male" name="gender" required value="male" <?php if(isset($gender) && $gender == 'male') { echo 'checked="checked"'; } ?>>
                      Male
                      <input type="radio" class="minimal" id="female" name="gender"  required value="female" <?php if(isset($gender) && $gender == 'female') { echo 'checked="checked"'; } ?>>
                      Female
                      </br>
                    </div>
                    <span class="error"><?php //echo form_error('gender');?></span>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Contact Details<span class="mandatory-field">*</span></label>
                  <div class="col-sm-3">
                    Mobile No.<span class="mandatory-field">*</span>
                    <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile No" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php echo $mobile_no;?>" required  data-parsley-mobile_no_exist data-parsley-trigger= focusout data-parsley-mobile_no_exist-message="This Mobile no is already Exists."  maxlength="10" data-parsley-pattern="/^\d{10}$/" data-parsley-trigger-after-failure="focusout" onkeypress="return isNumber(event)" > 
                    <span class="error"><?php //echo form_error('mobile');?></span>
                  </div>
                  <div class="col-sm-3">
                    Alternate Mobile No.
                    <input type="text" class="form-control" id="alt_mobile" name="alt_mobile_no" placeholder="Alternate Mobile No" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php echo $alt_mobile_no;?>"  size="10" maxlength="10" data-parsley-pattern="/^\d{10}$/" data-parsley-trigger-after-failure="focusout" onkeypress="return isNumber(event)" > 
                    <span class="error"><?php //echo form_error('mobile');?></span>
                  </div>
                </div> 
                                
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"><span class="mandatory-field"></span></label>
                  <div class="col-sm-3">
                    Email Id<span class="mandatory-field">*</span>
                    <input type="text" class="form-control" id="email" name="email_id" placeholder="Email Id" autocomplete="off" data-parsley-type="email" data-parsley-pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/" value="<?php echo $email_id; ?>"  data-parsley-maxlength="80" maxlength="80" required autocomplete="off" data-parsley-trigger-after-failure="focusout" data-parsley-errors-container="#email_error" data-parsley-email_exist data-parsley-trigger= focusout data-parsley-email_exist-message="This Email ID is already Exists.">
                    <span class="note-error" id="email_error"><?php //echo form_error('email');?></span>
                  </div>
                  <div class="col-sm-3">
                    Alternate Email Id
                    <input type="text" class="form-control" id="alt_email" name="alt_email_id" autocomplete="off" placeholder="Alternate Email Id" data-parsley-type="email" data-parsley-pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/" data-parsley-errors-container="#alt_email_error" value="<?php echo $alt_email_id; ?>"  data-parsley-maxlength="80" maxlength="80" autocomplete="off" data-parsley-trigger-after-failure="focusout">
                    <span class="note-error" id="alt_email_error"><?php //echo form_error('email');?></span>
                  </div>
                </div>  

                <div class="form-group">
                  <label for="participate_yes_no" class="col-sm-3 control-label">Qualification<span class="mandatory-field">*</span></label>
                  <div class="col-sm-5">
                    <input type="radio" class="" id="Under_Graduate" name="qualification_type" required value="Under_Graduate" <?php if(isset($qualification_type) && $qualification_type == 'Under_Graduate') { echo 'checked="checked"'; } ?>>UG (min 10th pass)
                    <input type="radio" class="" id="Graduate" name="qualification_type" required value="Graduate" <?php if(isset($qualification_type) && $qualification_type == 'Graduate') { echo 'checked="checked"'; } ?>>Graduate
                    </br>
                   </div>
                  <span class="error"><?php //echo form_error('gender');?></span>
                </div>      

              </div><!--.box-body-->
              <?php if(count($candidate_data)==0) { ?>
              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-3">
                  <input type="hidden" name="btnSubmit1" value="submit_1">
                  <input type="submit" class="btn btn-info btn_submit" id="btnSubmit" value="Submit I">  
                  <input type="reset" class="btn btn-danger" name="" id="btnReset" value="Reset">  
                </div>
              </div>
            <?php }?>
   
            </div><!--.box-info-->
          </form>

      <?php } if($current_date <= $training_from_date && $current_date <= $date_after_3days) { 
          //else if($current_date >= $training_from_date && $current_date <= $date_after_3days) {?>
        <form class="form-horizontal" autocomplete="off" name="draExamAddFrm" id="draExamAddFrm"  method="post"  enctype="multipart/form-data" data-parsley-validate="parsley">
            <div class="alert alert-warning alert-dismissible">
              To be filled in with appropriate contents carefully as the same cannot be changed after the locking period i.e. (<?php echo $date_after_3days; ?>)
            </div>
            
            <div class="box box-info" id="other_details_div">
              <div class="box-header with-border">
                <h3 class="box-title">Other Details</h3>
              </div>

              <div class="box-body">
              <div class="form-group">
                <label for="address_line1" class="col-sm-3 control-label">Address<span class="mandatory-field">*</span></label>
                <div class="col-sm-4">
                  Line 1
                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line 1" required value="<?php echo set_value('addressline1');?>"  data-parsley-maxlength="50" autocomplete="off" maxlength="30">
                  <span class="error"><?php //echo form_error('addressline1');?></span>
                </div> 

                <div class="col-sm-4">
                  Line 2
                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line 2" data-parsley-maxlength="50" value="<?php echo set_value('addressline2');?>" autocomplete="off" maxlength="30">
                  <span class="error"><?php //echo form_error('addressline2');?></span>
                </div> 
              </div>

               <div class="form-group">
                <label for="address_line1" class="col-sm-3 control-label"><span class="mandatory-field"></span></label>
                <div class="col-sm-4">
                  Line 3
                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line 3" data-parsley-maxlength="50" value="<?php echo set_value('addressline3');?>"  autocomplete="off" maxlength="30">
                  <span class="error"><?php //echo form_error('addressline2');?></span>
                </div> 

                <div class="col-sm-4">
                  Line 4
                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line 4" data-parsley-maxlength="50" value="<?php echo set_value('addressline4');?>"  autocomplete="off" maxlength="30">
                  <span class="error"><?php //echo form_error('addressline2');?></span>
                </div> 
              </div>
              </div>

              <div class="box box-info">
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
  								  }
  								?>

                  <label for="inst_name" class="col-sm-3 control-label">Name Of Training Institute </label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control"  placeholder="Name Of Training Institute"  value="<?php echo $institute_name;?>"   autocomplete="off"  readonly="readonly">

                    <input type="hidden" class="form-control"  value="<?php echo $institute_code;?>"  name="inst_code" autocomplete="off"  readonly="readonly">
                  </div>
                </div>

                <?php
                if( count($batch_details) > 0 ) {
                  foreach ($batch_details as $batchd) { ?>
                    <div class="form-group">
                      <label for="center" class="col-sm-3 control-label">Centre Name </label>
                      <div class="col-sm-5">
                        <input type="text" class="form-control" placeholder="Center Code" name="city" value="<?php echo set_value('center_code');?> <?php echo $batchd['city_name'] ;?>" autocomplete="off" readonly>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="center" class="col-sm-3 control-label">State </label>
                      <div class="col-sm-5">
                        <input type="text" class="form-control" id="state_name" name="state_name" value="<?php echo $batchd['state_name'] ;?>" autocomplete="off" readonly>

                        <input type="hidden" class="form-control" id="state_code" name="state_code" value="<?php echo $batchd['state_code'] ;?>" autocomplete="off">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="center" class="col-sm-3 control-label">District </label>
                      <div class="col-sm-5">
                        <input type="text" class="form-control" id="district_name" name="district_name" value="<?php echo $batchd['district'] ;?>" autocomplete="off" readonly>

                        <input type="hidden" class="form-control" id="district" name="district" value="<?php echo $batchd['district'] ;?>" autocomplete="off">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="center" class="col-sm-3 control-label">City </label>
                      <div class="col-sm-5">
                        <input type="text" class="form-control" id="city_name" name="city_name" value="<?php echo $batchd['city_name'] ;?>" autocomplete="off" readonly>

                        <input type="hidden" class="form-control" id="city_id" name="city_id" value="<?php echo $batchd['city'] ;?>" autocomplete="off">
                      </div>
                    </div>

                    <div class="form-group">
                      <input type="hidden" class="form-control" id="pincode" name="pincode" value="<?php echo $batchd['pincode'] ;?>" autocomplete="off">
                      </div>
                    </div>
                                
                    <?php /*?><div class="form-group">
                        <label for="center" class="col-sm-3 control-label">Batch Name </label>
                        <div class="col-sm-5">
                        <input type="text" class="form-control" placeholder="Center Code"  value="<?php echo set_value('center_code');?> <?php echo $batchd['batch_name'] ;?>" autocomplete="off" readonly>
                      </div>
                    </div><?php */?>
                                
                    <div class="form-group">
                        <label for="training_period" class="col-sm-3 control-label">Training Period  </label>
                        <div class="col-sm-2">
                            From
                            <input type="text" class="form-control" name="training_period_from" value="<?php echo $batchd['batch_from_date'] ;?>" autocomplete="off"  readonly="readonly"/>
                            <span class="error"><?php //echo form_error('training_from');?></span>
                        </div> 
                        <div class="col-sm-2">
                            To
                            
                            <input type="text" class="form-control" name="training_period_to"  placeholder="Training To Date"  value=" <?php echo $batchd['batch_to_date'] ;?>" autocomplete="off"  readonly="readonly"/>
                            <span class="error"><?php //echo form_error('training_to');?></span>
                          
                        </div> 
                    </div>
                <?php }
                  }
                ?>
              </div><!--.box-body-->
              </div><!--.box-info-->
                      
              <div class="box box-info">
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
                      <?php if(count($idtype_master) > 0)
                      {
                        $i=1;
                        foreach($idtype_master as $idrow)
                        {?>
                          <input name="idproof" value="<?php echo $idrow['id'];?>" type="radio" class="btn_check" data-parsley-required
                          <?php if(set_value('idproof')){echo set_radio('idproof', $idrow['id'], TRUE);}?> ><?php echo $idrow['name'];?><br>
                         <?php 
                        $i++;}
                      }?>
                      <span class="error"><?php //echo form_error('idproof');?></span>
                    </div>
                  </div>

                    <div class="form-group">
                        <label for="id_proof" class="col-sm-3 control-label">Id Proof Number <span class="mandatory-field">*</span></label>
                        <div class="col-sm-5" id="id_no_proof">
                            <input type="text" class="form-control" id="idproof_no" name="idproof_no" placeholder="Id Proof Number." value="<?php echo set_value('id_number');?>" data-parsley-required data-parsley-errors-container="#idproof_no_error"  data-parsley-idproof_exist data-parsley-trigger= focusout data-parsley-idproof_exist-message="This ID Proof is already Exists." autocomplete="off">
                            <span class="note" id="idproof_no_note"></span></br>
                            <span class="note-error" id="idproof_no_error"></span>
                        </div> 
                    </div>
                    
                  <div class="form-group">
                    <label for="id_proof" class="col-sm-3 control-label">Proof of Identity <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="exist_draidproofphoto">
                      <input  type="file" name="draidproofphoto" id="draidproofphoto" data-parsley-required autocomplete="off" onchange="validateFile(event, 'idproof_size_error', 'idproof_preview', '25kb')">
                      <input type="hidden" autocomplete="false" id="hiddenidproofphoto" name="hiddenidproofphoto">
                      <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png  Files upto 25KB in 100*120 pixel dimensions.</span></br>
                      <span class="note-error" id="idproof_size_error"></span>
                      <!-- <div id="error_dob"></div> -->
                      <br>
                      
                      <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                    <img id="idproof_preview" height="100" width="100"/>
                  </div>

                  <div class="form-group">
                    <label for="education" class="col-sm-3 control-label">Qualification<span class="mandatory-field"></span></label>
                    <div class="col-sm-7">
                      <input type="radio" class="radiocls" id="tenth" name="education_qualification" value="tenth">10th Pass
                      <input type="radio" class="radiocls" id="twelth" name="education_qualification" value="twelth">12th Pass
                      <input type="radio" class="radiocls" id="graduate" name="education_qualification" value="graduate">Graduation
                      <input type="radio" class="radiocls" id="post_graduate" name="education_qualification" value="post_graduate">Post Graduation
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
                  </div>
                    
                  <div class="form-group">
                    <label for="quali_certificate" class="col-sm-3 control-label">Qualification Certificate <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="exist_qualicertificate">
                        <input  type="file" name="qualicertificate" id="qualicertificate" data-parsley-required autocomplete="off" onchange="validateFile(event, 'error_qualicert', 'qualicertificate_preview', '100kb')"> (As per educational qualification selected above)
                        <input type="hidden" autocomplete="false" id="hiddenqualicertificate" name="hiddenqualicertificate"></br>
                        <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png  Files upto 100KB in 100*120 pixel dimensions.</span></br>
                        <span class="note-error" id="error_qualicert"></span>
                     <br>
                     
                     <span class="qualicert_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('qualicertificate');?></span>
                    </div>
                    <img id="qualicertificate_preview" height="100" width="100"/>
                  </div>
             
                  <div class="form-group">
                    <label for="photograph" class="col-sm-3 control-label">Passport Photograph of the Candidate <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="exist_drascannedphoto">
                        <input  type="file" name="drascannedphoto" id="drascannedphoto" data-parsley-required autocomplete="off" onchange="validateFile(event, 'scannedphoto_error', 'scanphoto_preview', '20kb')">
                         <input type="hidden" autocomplete="false" id="hiddenphoto" name="hiddenphoto">
                        <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png  Files upto 20KB in 100*120 pixel dimensions.</span></br>
                        <span class="note-error" id="scannedphoto_error"></span>
                     <br>
                     
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('drascannedphoto');?></span>
                    </div>
                    <img id="scanphoto_preview" height="100" width="100"/>
                  </div>
                    
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label"> Full Signature of the Candidate <span class="mandatory-field">*</span></label>
                    <div class="col-sm-5" id="exist_drascannedsignature">
                          <input  type="file" name="drascannedsignature" id="drascannedsignature" data-parsley-required autocomplete="off" onchange="validateFile(event, 'error_signature', 'signature_preview', '20kb')">
                          <input type="hidden" autocomplete="false" id="hiddenscansignature" name="hiddenscansignature">
                          <span class="note" id="idproof_preview_note">Note: Please Upload only .jpg, .jpeg, .png  Files upto 20KB in 100*120 pixel dimensions.</span></br>
                          <span class="note-error" id="error_signature"></span>
                          <span class="signature_text" style="display:none;"></span>
                          <span class="error"><?php //echo form_error('drascannedsignature');?></span>
                      </div>
                      <img id="signature_preview" height="100" width="100"/>
                    </div>
                    
                    <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Aadhar Card No.<span class="mandatory-field"></span></label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="aadhar_no" name="aadhar_no" placeholder="Aadhar card No." autocomplete="off" value="<?php echo set_value('aadhar_no');?>" onkeypress="return isNumber(event)" minlength="12" maxlength="12" data-parsley-errors-container="#aadhar_no_error" data-parsley-trigger= focusout data-parsley-pattern="/^\d{12}$/"  data-parsley-pattern-message="Please enter 12 digit Aadhar No" data-parsley-aadhar_no_exist data-parsley-trigger= focusout data-parsley-aadhar_no_exist-message="This Adhar no is already Exists."  >
                          <span class="note">Note: Please Enter Aadhar no like: 666635870783</span></br>
                          <span class="error" id="aadhar_no_error"><?php //echo form_error('email');?></span>
                        </div>
                    </div>
                    
                </div><!--.box-body-->
              </div><!--.box-info-->
                       
              <div class="box box-info">
              <div class="box-header with-border">
            		<h3 class="box-title"> 
                <input name="declaration1" value="1" type="checkbox" required 
              <?php if(set_value('declaration1'))
              {
                echo set_radio('declaration1', '1');
              }?>>&nbsp; I Accept Declaration</h3> 
            	</div>
              <div class="box-body">
              	<div class="form-group">
                  	<div class="col-sm-12">
                      <p>
                      I Wish to enroll as a candidate for the above mentioned examination. I confirm having read Rules and Regulations and other instructions governing the above examination of the institute. I hereby agree to abide by all the said Rules and Regulations and other instructions of the institute. I declare that i have not been debarred/disqualified from appearing at the institute's examination/s at the time of submitting this application. I further declare that in case I am desirous of instituting any legal proceedings against the institute, I hereby agree that such legal proceedings shall be instituted only in Courts at New Delhi, Kolkata, Mumbai & Chennai as the case may be, in whose jurisdiction the application is submitted by me and not in any other Court.
                      </p>
                      </div>
              	</div>
              </div><!--.box-body-->
              </div><!--.box-info-->
              
                <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <input type="submit" class="btn btn-info btn_submit" name="btnSubmit" id="btnSubmit" value="Submit II">  
                    <input type="reset" class="btn btn-danger" name="" id="btnReset" value="Reset">  
                  </div>
                </div>

            </div>
          </form>

      <?php }?>
      </div>

      </div>
    </section>
 
</div>

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<!-- <script src="<?php echo base_url()?>js/validation_dra.js"></script>
 -->
 <script src="<?php echo base_url()?>js/validation_dra_batch.js"></script>
<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';

	$(document).ready(function() {

    var candidate_count = '<?php echo count($candidate_data); ?>';
    if( candidate_count>0){
      $('#basic_details_div input[type="text"]').attr("disabled", true);
      $('#basic_details_div input[type="radio"]').attr("disabled", true);
      $('#sel_namesub').attr("disabled", true);
      $(".day").attr('disabled', true);
      $(".month").attr('disabled', true);
      $(".year").attr('disabled', true);
    }

		$('#draExamAddFrm').parsley('validate');
		//date of birth dropdowns
		$("#dateofbirth").dateDropdowns({
			submitFieldName: 'dob1',
			minAge: 0,
			maxAge:100
		});
		$("#dateofbirth").change(function(){
			var sel_dob = $("#dateofbirth").val();
			if(sel_dob!='')
			{
				var dob_arr = sel_dob.split('-');
				if(dob_arr.length == 3)
				{
					chkage(dob_arr[2],dob_arr[1],dob_arr[0]);	
				}
				else
				{	alert('Select valid date');	}
			}
		});
		//change captcha
		$('#new_captcha').click(function(event){
			event.preventDefault();
			var sdata = {'captchaname':'draexamcaptcha'};
			$.ajax({
				type: 'POST',
				data: sdata,
				url: site_url+'iibfdra/DraExam/generatecaptchaajax/',
				success: function(res)
				{	
					if(res!='')
					{$('#captcha_img').html(res);
					}
				}
			});
		});

    $('#btnReset').on('click', function(e){
        location.reload();
    });
    
		//if invalid captcha entered keep unchecked exam mode disabled
		if( $("input[name='exam_mode']:checked").length > 0) {
			$("input[name='exam_mode']:not(:checked)").attr('disabled', true);
		}
		// change gender on chnage of name subtitle 
		if( $("input[name='gender']:checked").length > 0) {
			$("input[name='gender']:not(:checked)").attr('disabled', true);
		}
		$('#sel_namesub').change(function(event){
			//alert("test");
			var sel_namesub = $(this).val();
			$("input[name='gender']:not(:checked)").attr('disabled', false);
			if(sel_namesub == 'Mr.') {
				$("#male").prop('checked', true);
				$("#female").attr('disabled', true);
			}
			else if(sel_namesub == 'Mrs.' || sel_namesub == 'Ms.') {
				$("#female").prop('checked', true);	
				$("#male").attr('disabled', true);
			} else {
				$("input[name='gender']").removeAttr('disabled');		
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


    /* Get City From State in Agency tab */
    $('#ccstate').on('change',function(){
    var state_code = $(this).val();
    if(state_code){
      $.ajax({
        type:'POST',
        url: site_url+'iibfdra/TrainingBatches/getCity',
        data:'state_code='+state_code,
        success:function(html){
          //alert(html);
          $('#city').show();
          $('#city').html(html);
        }
      });
      }else{
        $('#city').html('<option value="">Select State First</option>');
      }
    });


		$("body").on("contextmenu",function(e){
			return false;
		});

    $("#reg_no").on("focusout", function() {
      var regno = $(this).val();
      var batch_id = $('#batchid').val();
      var attr = $(this).attr('readonly');
      //alert(batch_id);
      if( regno != '' && typeof attr == typeof undefined && attr !== "false" ) {
        // validate reg no -
        var letterNumber = /^[0-9]+$/; 
        if(regno.match(letterNumber))   
        {  
          //return true;  
        }  
        else  
        {   
          alert("Please enter numeric registration number only");
          $("#reg_no").val('').focus();
          return false;   
        }

        // eof code
        var sdata = {'regno':regno,'batch_id':batch_id}
        $.ajax({
          type: "POST",  
          url: site_url+'iibfdra/TrainingBatches/get_memdetails/',
          data: sdata, 
          success: function(data){
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
            var obj = jQuery.parseJSON( data );
            var memtype = obj['membertype'];
            //console.log(obj);
            if( Object.keys(obj).length > 0 ) {
              var flg = 0;
              $.each( obj, function( key, value ) {
                if( key == 'error_message') {
                  alert(value);
                  $("#reg_no").val('').focus();
                  flg = 1;
                  return false;
                }
                          
                if( key == 'error' && value == 1 ) {
                  alert("Invalid registration number");
                  flg = 1;
                  return false;
                }

                if( key == 'exam_mode' ) {
                  $("#"+value).prop('checked', true);
                }
                if( key == 'edu_quali' ) {
                  $("#"+value).prop('checked', true);
                }

                if( key == 'gender' ) {
                  $("#"+value).prop('checked', true);
                }

                if( key == 'idproof' ) {
                  $(".idproof-wrap").find("input[value='"+value+"']").prop('checked', true);
                }

                if( key == 'state' ) {
                  $("#ccstate").val(value);
                }
                
                if( key == 'city' ) {
                  $('#city').html('<option value="'+value+'">'+value+'</option>');
                }

                if( key == 'sel_namesub' ) {
                  $('#sel_namesub').html('<option value="'+value+'">'+value+'</option>');
                }
                
                if($("#"+key).length > 0 ) {
                  $("#"+key).val(value);
                }
                //alert("Key : " + key);
                //alert("Value : " + value);
                // code to view existinf images, Added by Bhagwan Sahane, 25-01-2017 -
                if( key == 'idproofphoto' && value != '' ) {
                  $("#exist_draidproofphoto").html('<img src="'+value+'" id="idproof_preview" height="100" width="100"/>');
                }
                else if( key == 'idproofphoto' && value == '' )
                {
                  $("#exist_draidproofphoto").html('<span class="error">Your Identity Proof is not available, kindly apply again with new application.</span>');  
                }

                if( key == 'scannedphoto' && value != '' ) {
                  $("#exist_drascannedphoto").html('<img src="'+value+'" id="scanphoto_preview" height="100" width="100"/>');
                }

                else if( key == 'scannedphoto' && value == '' )
                {
                  $("#exist_drascannedphoto").html('<span class="error">Your Scanned Photograph is not available, kindly apply again with new application.</span>');  
                }

                if( key == 'scannedsignaturephoto' && value != '' ) {
                  $("#exist_drascannedsignature").html('<img src="'+value+'" id="signature_preview" height="100" width="100"/>');
                }

                else if( key == 'scannedsignaturephoto' && value == '' )
                {
                  $("#exist_drascannedsignature").html('<span class="error">Your Scanned Signature is not available, kindly apply again with new application.</span>'); 
                }

                if( key == 'quali_certificate' && value != '' ) {
                  $("#exist_qualicertificate").html('<img src="'+value+'" id="qualicertificate_preview" height="100" width="100"/>');
                }

                else if( key == 'quali_certificate' && value == '' )
                {
                  $("#exist_qualicertificate").html('<span class="error">Your Qualification Certificate is not available, kindly apply again with new application.</span>');  
                }

                if( key == 'training_certificate' && value != '' ) {

                  $("#exist_trainingcertificate").html('<img src="'+value+'" id="trcertificate_preview" height="100" width="100"/>');

                }

                else if( key == 'training_certificate' && value == '' )
                {
                  $("#exist_trainingcertificate").html('<span class="error">Your Training Certificate is not available, kindly apply again with new application.</span>');  
                }

              
                if( key == 'dateofbirth' ) {
                  var dob_arr = value.split('-');
                  var dyear = dob_arr[0];
                  var dmnth = dob_arr[1];
                  var dday = dob_arr[2];

                  $("#dateofbirth").val(value);
                  $(".day").val(dday);
                  $(".month").val(dmnth);
                  $(".year").val(dyear);

                  /* not to keep it editable - 08-03-2017 */

                  $(".day").attr('disabled', true);
                  $(".month").attr('disabled', true);
                  $(".year").attr('disabled', true);
                }
              });

              if( flg == 1 ) {
                return false; 
              }

              if( memtype == 'normal_member' ) {
                $("input[name='exam_mode']").prop('checked', false);

                //  $("input[name='edu_quali']").prop('checked', false);

              } else {
                //$("input[name='exam_mode']").attr("readonly","readonly");
                $("input[name='edu_quali']:not(:checked)").attr('disabled', true);
                $("input[name='idproof']:not(:checked)").attr('disabled', true);
                $("input[type='file']").removeAttr("required");

                $(".required-spn").text("");
                if( $("input[name='gender']:checked").length > 0) {
                  $("input[name='gender']").removeAttr("disabled");
                }

              $('#training_from').datepicker('remove').attr("readonly","readonly");
              $('#training_to').datepicker('remove').attr("readonly","readonly");

              $("input[name='gender']:not(:checked)").attr('disabled', true);
              $("input[name='exam_mode']:not(:checked)").attr('disabled', true);

              /*keep pincode, district, city, address editable - change made on 19-01-2017*/
              $("#ccstate").attr('disabled', 'true');
              $("#pincode").attr("readonly","readonly");
              $("#district").attr("readonly","readonly");
              //$("#city").attr("disabled","true");
              $("#addressline1").attr("readonly","readonly");
              $("#addressline2").attr("readonly","readonly");
              $("#addressline3").attr("readonly","readonly");
              $("#addressline4").attr("readonly","readonly");

              $("#sel_namesub").attr('disabled', true);
              /* keep it editable - 20-01-2017 */
              $("#firstname").attr("readonly","readonly");
              $("#middlename").attr("readonly","readonly");
              $("#lastname").attr("readonly","readonly");
              $("#stdcode").attr("readonly","readonly");
              $("#phone").attr("readonly","readonly");
              $("#mobile_no").attr("readonly","readonly");
              $("#email_id").attr("readonly","readonly");
              $("#aadhar_no").attr("readonly","readonly"); // added by Bhagwan Sahane, on 06-05-2017

              $("#reg_no").attr("readonly","readonly");
                

              }

            } else {
              alert("Invalid registration number");
              $("#reg_no").val(""); 
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus); alert("Error: " + errorThrown); 
          }       
        });      
       }
    });
	});

  $(document).on('keyup', '#idproof_no', function() {
    var proof_type = $("input[type='radio'].btn_check:checked").val();
    var idproof_no = $(this).val();

    $('#idproof_no_error').text("");
  
    if(proof_type == 1){
        var regex = /^\d{12}$/;
        if (regex.test(idproof_no)) {
            $('#idproof_no_error').text("");
            errCnt = 0;
            return true;
        } else {
            $('#idproof_no_error').text("Please enter 12 digit Aadhar No with Valid Format mentioned above.");
            errCnt = 1;
            return false;
        }
    }
    else if(proof_type == 2){
        var regex = /[A-Z]{2}[0-9]{13}$/;
        if (regex.test(idproof_no)) {
            $('#idproof_no_error').text("");
            errCnt = 0;
            return true;
        } else {
            $('#idproof_no_error').text("Please enter Driving License with 15 alphanumeric values with Valid Format mentioned above.");
            errCnt = 1;
            return false;
        }
    }

    else if(proof_type == 5){
        var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if (regex.test(idproof_no)) {
            $('#idproof_no_error').text("");
            errCnt = 0;
            return true;
        } else {
            $('#idproof_no_error').text("Please enter PAN no with 10 alphanumeric values with Valid Format mentioned above.");
            errCnt = 1;
            return false; 
        }
    }

    else if(proof_type == 6){
        var regex = /^[A-Z]{1}[0-9]{7}/;
        if (regex.test(idproof_no)) {
            $('#idproof_no_error').text("");
            errCnt = 0;
            return true;
        } else {
            $('#idproof_no_error').text("Please enter Passport No with 8 alphanumeric values with valid Format mentioned above.");
            errCnt = 1;
            return false;  
        }
    }
    else{
        errCnt = 0;
        return true;
        $('#idproof_no_error').text("");
    }
});

$(document).on('change', '.btn_check', function() {
    var proof_type = $(this).val();
    //console.log('proof_type--'+proof_type);
    $('#idproof_no_error').text("");
    $('#idproof_no').val("");

    if(proof_type == 1){
      $('#idproof_no_note').text('Note: Please Enter Aadhar no like: 666635870783');

      $("#idproof_no").attr({
        "onkeypress" : "return isNumber(event)",
        "maxlength" : "12"
      });
    }

    if(proof_type == 2){
      $('#idproof_no_note').text('Note: Please Enter Driving License No like: MH2730123476102');

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

});

$('#email').parsley();
    
  window.ParsleyValidator.addValidator('email_exist', {
      
      validateString: function(value){
          return $.ajax({
              url: base_url+"iibfdra/TrainingBatches/check_email",
              method:"POST",
              data: {email: value},
              dataType: 'JSON',       
              success:function(data)
              {
                  return true;
              }
          });
      }
      //console.log(isSuccess);
      //return isSuccess;
      
  });

    
  $('#mobile_no').parsley();
    
  window.ParsleyValidator.addValidator('mobile_no_exist', {
      
      validateString: function(value){
          return $.ajax({
              url: base_url+"iibfdra/TrainingBatches/check_mobile_no",
              method:"POST",
              data: {mobile_no: value},
              dataType: 'JSON',       
              success:function(data)
              {
                  return true;
              }
          });
      }
      //console.log(isSuccess);
      //return isSuccess;
      
  });
    
  $('#aadhar_no').parsley();
    
    window.ParsleyValidator.addValidator('aadhar_no_exist', {
        
      validateString: function(value){
            return $.ajax({
                url: base_url+"iibfdra/TrainingBatches/check_aadhar_no",
                method:"POST",
                data: {aadhar_no: value},
                dataType: 'JSON',       
                success:function(data)
                {
                    return true;
                }
            });
        }
        //console.log(isSuccess);
        //return isSuccess;
        
    });

    $('#idproof_no').parsley();
    
    window.ParsleyValidator.addValidator('idproof_exist', {
      
      validateString: function(value){
          return $.ajax({
              url: base_url+"iibfdra/TrainingBatches/check_idproof",
              method:"POST",
              data: {action: 'add', idproof: value},
              dataType: 'JSON',       
              success:function(data)
              {
                  return true;
              }
          });
      }
      //console.log(isSuccess);
      //return isSuccess;
      
    });



    

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
</script> 