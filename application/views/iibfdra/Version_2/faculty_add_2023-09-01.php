<style>

.note {
  color: blue;
  font-size: small;
}

.note-error {
  color: red;
  font-size: small;
}

img {
  display: block;
  height: auto;
  max-width: 100%;
}

.img-display {
  flex-grow: 1;
  max-width: 372px;
}

.imgzoom {
  display: inline-block;
}
.datepicker table tr td.disabled, .datepicker table tr td.disabled:hover, .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover { cursor: not-allowed; }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1> Add Faculty Form </h1>
  </section>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

  <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add New Faculty</h3>
              <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/Version_2/faculty" class="btn btn-warning">Back</a> </div>
            </div>
            <div class="box-body">
              
              <form class="form-horizontal" autocomplete="off"  name="frmDrACenter" id="faculty_form" method="post" enctype="multipart/form-data" data-parsley-validate> 
              
                <?php if($action == 'edit') { ?>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label">Faculty No.<span style="color:#F00">*</span></label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?php echo "F".$faculty_data[0]['faculty_number']; ?>" readonly="readonly">
                      </div>
                    </div>
                  </div>
                <?php } ?>
              
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Name of Faculty</label>
                    <div class="col-sm-2">
                      <label class="control-label">Salutation <span style="color:#F00">*</span></label>
                      <select name="salutation" id="salutation" class="form-control salutation_cls" data-parsley-required>
                        <option value="">Select</option>
                        <option value="MR" <?php if(isset($faculty_data[0]['salutation']) && $faculty_data[0]['salutation'] == 'MR') { echo "selected='selected'"; } ?>>MR.</option> 
                        <option value="MRS" <?php if(isset($faculty_data[0]['salutation']) && $faculty_data[0]['salutation'] == 'MRS') { echo "selected='selected'"; } ?>>MRS.</option>
                        <option value="MISS" <?php if(isset($faculty_data[0]['salutation']) && $faculty_data[0]['salutation'] == 'MISS') { echo "selected='selected'"; } ?>>MISS.</option>
                      </select>
                    </div>
                
                    <div class="col-sm-7">
                      <label class="control-label">Full Name <span style="color:#F00">*</span></label>
                      <input type="text" class="form-control" id="faculty_name" name="faculty_name" placeholder="Full Name" value="<?php echo isset($faculty_data[0]['faculty_name'])?$faculty_data[0]['faculty_name']:'';?>" maxlength = "90"  data-parsley-maxlength="90" data-parsley-required data-parsley-errors-container="#faculty_name_error" data-parsley-trigger="focusin focusout" onkeypress="return onlyAlphabets(event)">
                      <span class="note" >Note: Please Enter only 90 characters.</span></br>
                      <span class="note-error" id="faculty_name_error"> <?php echo form_error('faculty_name'); ?></span>
                    </div>
                  </div>
                </div>
                
                <?php if($action == 'add'){  ?>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label">Upload faculty photo<span style="color:#F00">*</span></label>
                      <div class="col-sm-5">
                        <input type="file" class="form-control" name="faculty_photo" id="faculty_photo" data-parsley-required data-parsley-errors-container="#faculty_photo_error" onchange="validateFile(event,'faculty_photo_error', 'faculty_photo_img', '20kb', '300', '300')">
                        <span id="faculty_photo_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                        <span class="note" id="faculty_photo_note">Note: Please Upload only .jpg, .jpeg, .png  Files upto 20KB in 300*300 pixel dimensions.</span></br>
                        <span class="note-error" id="faculty_photo_error"> <?php echo form_error('faculty_photo'); ?></span>
                      </div>

                      <div class="col-sm-4 img-display">
                        <div class="form-group faculty_photo_img_zoom imgzoom">
                          <img height="90" width="120" id="faculty_photo_img" src="<?php echo base_url('assets/images/no_image1.png'); ?>" alt="">
                        </div>
                      </div>

                    </div>
                  </div>
                <?php }?>

                <?php if($action == 'edit' || $action == 'view'){ ?>

                  <?php if(empty($faculty_data[0]['faculty_photo'])){
                    $style = 'style="display: block;"';
                    $required = 'data-parsley-required';
                  }else{
                    $style = 'style="display: none;"';
                    $required = '';
                  }?>
                    
                    <div class="col-sm-12" >
                      <div class="form-group" id="faculty_upload_image" <?php echo $style; ?>>
                        <label for="roleid" class="col-sm-3 control-label">Upload faculty photo<span style="color:#F00">*</span></label>
                        <div class="col-sm-5">
                          <input type="file" <?php echo $required; ?> class="form-control" name="faculty_photo" id="faculty_photo_edit" onchange="validateFile(event,'faculty_photo_edit_error', 'faculty_photo_img', '20kb', '300', '300')" >
                          <span id="faculty_photo_edit_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                          <span class="note" id="faculty_photo_note">Note: Please Upload only .jpg, .jpeg, .png  Files upto 20KB in 300*300 pixel dimensions.</span></br>
                          <span class="note-error" id="faculty_photo_edit_error"> <?php echo form_error('faculty_photo'); ?></span>
                        </div>
                        <div class="col-sm-4" id="faculty_upload_image1" <?php echo $style; ?>>
                          <div class="form-group faculty_photo_img_zoom imgzoom">
                            <label for="roleid" class="col-sm-3 control-label"></label>
                            <img height="90" width="120" id="faculty_photo_img" src="<?php echo base_url('assets/images/no_image1.png'); ?>" />
                          </div>
                        </div>
                      </div>
                    </div>

                    <?php if(!empty($faculty_data[0]['faculty_photo'])){?> 
                      <div class="col-sm-12" id="faculty_photo_show">
                        <div class="form-group">
                          <label for="exampleInputName1" class="col-sm-3 control-label"><b>Faculty Photo</b></label>
                          <div class="col-sm-5">
                            <img height="60" width="100" src="<?php echo base_url(); ?>uploads/faculty_photo/<?php echo $faculty_data[0]['faculty_photo']; ?>" />
                            <?php if($action == 'edit') { ?>
                            <button type="button" value="Remove" id="btn_faculty_remove" class="btn-danger"><span class="fa fa-times" onclick="removeImg1('<?php echo $faculty_data[0]['faculty_id']; ?>')"></span></button>
                            <?php } ?>
                            <input type="hidden" name="old_faculty_photo_image" value="<?php echo $faculty_data[0]['faculty_photo']; ?>">
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                
                <?php } ?>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Date of Birth</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" id="dob" name="dob" placeholder="" value="<?php echo isset($faculty_data[0]['dob'])?$faculty_data[0]['dob']:'';?>" data-parsley-minimumage="25" data-date-format="dd/mm/yyyy" data-parsley-minimumage-message="Applicant must be at least 25 years of age to apply" data-parsley-pattern="/^([12]\d|0[1-9]|3[01])\D?(0[1-9]|1[0-2])\D?(\d{4})$/"
                      data-parsley-pattern-message="Please Enter Date in dd/mm/yyyy format" data-parsley-trigger="keyup focusin focusout">
                      <span class="note-error" id="dob_error"> <?php echo form_error('dob'); ?></span>
                      <?php if($action != 'view'){ ?>
                        <span class="note" id="dob_note">Note: Please Enter DOB like: dd/mm/yyyy   31/05/1994.</span>
                      <?php }?>
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">PAN No.<span style="color:#F00">*</span></label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" id="pan_no" name="pan_no" placeholder=""  value="<?php echo isset($faculty_data[0]['pan_no'])?$faculty_data[0]['pan_no']:'';?>" minlength="10" maxlength="10" data-parsley-required data-parsley-errors-container="#pan_no_error" data-parsley-pan_no_exist data-parsley-trigger="keyup focusin focusout" data-parsley-pan_no_exist-message="This PAN no is already Exists." data-parsley-pattern="/[A-Z]{5}[0-9]{4}[A-Z]{1}$/" data-parsley-pattern-message="Please Enter 10 digit PAN No with Valid Alphanumeric Format." >
                      <?php if($action != 'view'){ ?>
                      <span class="note" id="pan_no_note">Note: Please Enter PAN no like: ABCTY1234D.</span>
                      <?php }?>
                      <span class="note-error" id="pan_no_error"> <?php echo form_error('pan_no'); ?></span>
                    </div>
                  </div>
                </div>

                <?php if($action == 'add'){  ?>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label">Upload PAN photo<span style="color:#F00">*</span></label>
                      <div class="col-sm-5">
                        <input type="file" class="form-control" name="pan_photo" id="pan_photo" data-parsley-required data-parsley-errors-container="#pan_photo_error" 
                        onchange="validateFile(event,'pan_photo_error', 'pan_photo_img', '20kb', '300', '300')">
                        <span id="pan_photo_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                        <span class="note" id="faculty_photo_note">Note: Please Upload only .jpg, .jpeg, .png  Files upto 20KB in 300*300 pixel dimensions.</span></br>
                        <span class="note-error" id="pan_photo_error"> <?php echo form_error('pan_photo'); ?></span>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group pan_photo_img_zoom imgzoom">
                          <img height="90" width="120" id="pan_photo_img" src="<?php echo base_url('assets/images/no_image1.png'); ?>" />
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } ?>

                <?php if($action == 'edit' || $action == 'view'){ ?>

                    <?php if(empty($faculty_data[0]['pan_photo'])){
                      $style1 = 'style="display: block;"';
                      $required1 = 'data-parsley-required';
                    }else{
                      $style1 = 'style="display: none;"';
                      $required1 = '';
                    }?>

                  <div class="col-sm-12" >
                    <div class="form-group" id="pan_upload_image" <?php echo $style1; ?>>
                      <label for="roleid" class="col-sm-3 control-label">Upload PAN photo<span style="color:#F00">*</span></label>
                      <div class="col-sm-5">
                        <input type="file" <?php echo $required1; ?> class="form-control" name="pan_photo" id="pan_photo_edit" onchange="validateFile(event, 'pan_photo_edit_error', 'pan_photo_img', '20kb', '300', '300')">
                        <span id="pan_photo_edit_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</span>
                        <span class="note" id="faculty_photo_note">Note: Please Upload only .jpg, .jpeg, .png  Files upto 20KB in 300*300 pixel dimensions.</span></br>
                        <span class="note-error" id="pan_photo_edit_error"> <?php echo form_error('pan_photo'); ?></span>
                      </div>
                      <div class="col-sm-4" id="pan_upload_image1" <?php echo $style1; ?>>
                        <div class="form-group pan_photo_img_zoom imgzoom">
                          <img height="90" width="120" id="pan_photo_img" src="<?php echo base_url('assets/images/no_image1.png'); ?>" />
                        </div>
                      </div>
                    </div>
                  </div>

                    <?php if(!empty($faculty_data[0]['pan_photo'])){?> 
                      <div class="col-sm-12" id="pan_photo_show">
                        <div class="form-group">
                          <label for="exampleInputName1" class="col-sm-3 control-label"><b>PAN Photo</b></label>
                          <div class="col-sm-5">
                            <img height="90" width="120" src="<?php echo base_url(); ?>uploads/pan_photo/<?php echo $faculty_data[0]['pan_photo']; ?>" />
                            <?php if($action == 'edit') { ?>
                            <button type="button" value="Remove" id="btn_pan_remove" class="btn-danger"><span class="fa fa-times" onclick="removeImg2('<?php echo $faculty_data[0]['faculty_id']; ?>')"></span></button>
                            <?php }?>
                            <input type="hidden" name="old_pan_photo_image" value="<?php echo $faculty_data[0]['pan_photo']; ?>">
                          </div>
                        </div>
                      </div> 
                    <?php } ?>
                
                <?php } ?>


                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Base Location</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" id="base_location" name="base_location" placeholder="City / District / State" value="<?php echo isset($faculty_data[0]['base_location'])?$faculty_data[0]['base_location']:'';?>" maxlength="75" onkeypress="return alphanumeric(event)">
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Academic Qualification(s) with year of passing<span style="color:#F00">*</span></label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" id="academic_qualification" name="academic_qualification" placeholder="e.g. BE/Mcom (2023)" value="<?php echo isset($faculty_data[0]['academic_qualification'])?$faculty_data[0]['academic_qualification']:'';?>"   maxlength="100" data-parsley-errors-container="#academic_qualification_error" data-parsley-required data-parsley-trigger="keyup focusin focusout">
                      <span class="note-error" id="academic_qualification_error"> <?php echo form_error('academic_qualification'); ?></span>
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Professional Qualification(s) if any, (including from IIBF) with year of passing</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" id="personal_qualification" name="personal_qualification" placeholder="e.g. MBA (1994)" value="<?php echo isset($faculty_data[0]['personal_qualification'])?$faculty_data[0]['personal_qualification']:'';?>">
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Work Experience<span style="color:#F00">*</span></label>
                    <div class="col-sm-3">
                      <label for="roleid" class="control-label">Bank/ FI Name</label>
                    </div>
                    <div class="col-sm-3">
                      <label for="roleid" class="control-label">Last Position held, Employee Id</label>
                    </div>
                    <div class="col-sm-3">
                      <label for="roleid" class="control-label">Gross Duration</label>
                    </div>
                  </div>
                  <span class="note">Note : Please Add 3 Work experience only.</span>
                </div>

                <?php if($action == 'add'){?>

                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label"></label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="work_exp0" name="work_exp[]" placeholder="e.g. ABC Bank/ABC Agency"  value="<?php echo isset($faculty_data['work_exp2'])?$faculty_data['work_exp2']:'';?>" data-parsley-errors-container="#work_exp_error0" data-parsley-required >
                        <span class="note-error" id="work_exp_error0"></span>
                      </div>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="emp_id0" name="emp_id[]" placeholder="e.g. Manager - ABC123" value="<?php echo set_value('emp_id2');?>" data-parsley-errors-container="#emp_id_error0" data-parsley-required >
                        <span class="note-error" id="emp_id_error0"  ></span>
                      </div>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="e.g. 10 Years 1 month"  value="<?php echo set_value('gross_duration2');?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required>
                        <span class="note-error" id="gross_duration_error0" ></span>
                      </div>
                    </div>
                  </div>
                <? } 
                else {?>
                  <?php if(!empty($faculty_data[0]['work_exp1'])){ ?>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label"></label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="work_exp0" name="work_exp[]" placeholder="e.g. ABC Bank/ABC Agency" value="<?php echo $faculty_data[0]['work_exp1']; ?>" data-parsley-errors-container="#work_exp_error0" data-parsley-required >
                          <span class="note-error" id="work_exp_error0"></span>
                        </div>
                      <?php } if(!empty($faculty_data[0]['emp_id1'])){ ?>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="emp_id0" name="emp_id[]" placeholder="e.g. Manager - ABC123" value="<?php echo $faculty_data[0]['emp_id1']; ?>" data-parsley-errors-container="#emp_id_error0" data-parsley-required  >
                        <span class="note-error" id="emp_id_error0" ></span>
                      </div>
                    <?php } if(!empty($faculty_data[0]['gross_duration1'])){ ?>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="e.g. 10 Years 1 month"  value="<?php echo $faculty_data[0]['gross_duration1'];?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required >
                        <span class="note-error" id="gross_duration_error0"></span>
                      </div>
                    </div>
                  </div>
                  <?php }?>

                  <?php if(!empty($faculty_data[0]['work_exp2'])){ ?>
                    <div class="col-sm-12"  id="textbox-label">
                      <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label"></label>
                        
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="work_exp0" name="work_exp[]" placeholder="e.g. ABC Bank/ABC Agency" value="<?php echo $faculty_data[0]['work_exp2']; ?>" data-parsley-errors-container="#work_exp_error0" data-parsley-required >
                            <span class="note-error" id="work_exp_error0"></span>
                          </div>
                        <?php } if(!empty($faculty_data[0]['emp_id2'])){ ?>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="emp_id0" name="emp_id[]" placeholder="e.g. Manager - ABC123" value="<?php echo $faculty_data[0]['emp_id2']; ?>" data-parsley-errors-container="#emp_id_error0" data-parsley-required  >
                          <span class="note-error" id="emp_id_error0" ></span>
                        </div>
                      <?php } if(!empty($faculty_data[0]['gross_duration2'])){ ?>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="e.g. 10 Years 1 month"  value="<?php echo $faculty_data[0]['gross_duration2'];?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required >
                          <span class="note-error" id="gross_duration_error0"></span>
                        </div>
                     </div>
                    </div>
                  <?php } ?>

                  <?php if(!empty($faculty_data[0]['work_exp3'])){ ?>
                    <div class="col-sm-12" id="textbox-label">
                      <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label"></label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="work_exp0" name="work_exp[]" placeholder="e.g. ABC Bank/ABC Agency" value="<?php echo $faculty_data[0]['work_exp3']; ?>" data-parsley-errors-container="#work_exp_error0" data-parsley-required >
                            <span class="note-error" id="work_exp_error0"></span>
                          </div>
                        <?php } if(!empty($faculty_data[0]['emp_id3'])){ ?>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="emp_id0" name="emp_id[]" placeholder="e.g. Manager - ABC123" value="<?php echo $faculty_data[0]['emp_id3']; ?>" data-parsley-errors-container="#emp_id_error0" data-parsley-required  >
                          <span class="note-error" id="emp_id_error0" ></span>
                        </div>
                      <?php } if(!empty($faculty_data[0]['gross_duration3'])){ ?>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="e.g. 10 Years 1 month"  value="<?php echo $faculty_data[0]['gross_duration3'];?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required >
                          <span class="note-error" id="gross_duration_error0"></span>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                <?php }?>

                <div class="box-element" id="repeatable_workex"></div>
                <?php if($action == 'add' || $action == 'edit') { ?>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label"></label>
                    <div class="col-sm-6">
                     <a href="javascript:void(0);" id="btn_add_workex"><i class="btn btn-primary fa fa-plus-square cls_add_workex" title="Add work Experience"></i></a>&nbsp;
                      <a href="javascript:void(0);" id="btn_remove_workex" class="remove-added-box"><i class="btn btn-primary fa fa-minus-square" title="Remove work Experience"></i></a>

                    </div>
                  </div>
                </div>
                <?php }?>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Work Experience in IIBF if Any</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="work_exp_iibf" name="work_exp_iibf" placeholder="Work Experience in IIBF"  value="<?php echo isset($faculty_data[0]['work_exp_iibf'])?$faculty_data[0]['work_exp_iibf']:'';?>" maxlength="100" onkeypress="return alphanumeric(event)">
                      <span class="note">Note: Please Enter only 100 characters.</span>
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Experience as Faculty in DRA Training, if Any</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="DRA_training_faculty_exp" name="DRA_training_faculty_exp" placeholder="Experience as Faculty in DRA Training, if Any"  value="<?php echo isset($faculty_data[0]['DRA_training_faculty_exp'])?$faculty_data[0]['DRA_training_faculty_exp']:'';?>" maxlength="100" onkeypress="return alphanumeric(event)">
                      <span class="note">Note: Please Enter only 100 characters.</span>
                    </div>
                  </div>
                </div>

                <?php /*?> <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Period of Association with the agency in providing DRA training</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="DRA_training_faculty_exp" name="DRA_training_faculty_exp" placeholder="Years... Months..."  value="<?php echo isset($faculty_data[0]['DRA_training_faculty_exp'])?$faculty_data[0]['DRA_training_faculty_exp']:'';?>" maxlength="100">
                      <span class="note">Note: Enter Year and Month</span>
                    </div>
                  </div>
                </div> <?php */?>

                <?php /*?>  <div class="col-sm-12" id="agency_association_period" style="display: none;">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Period of Association with the Agency (for DRA Training purpose):</label>
                    <div class="col-sm-4">
                      <label for="roleid" class="control-label">Start Date</label>
                    </div>
                    <div class="col-sm-4">
                      <label for="roleid" class="control-label">End Date</label>
                    </div>
                  </div>
                </div>  <?php */?>

                <div class="col-sm-12" id="agency_association_period" style="display: none;">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Period of Association with the agency in providing DRA training</label>
                    <div class="col-sm-4">
                      <label for="roleid" class="control-label">Year</label>
                    </div>
                    <div class="col-sm-4">
                      <label for="roleid" class="control-label">Month</label>
                    </div>
                  </div>
                </div> 

                <?php /*?><div class="col-sm-12" id="agency_association_period1" style="display: none;">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label"></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Start Date" value="<?php echo isset($faculty_data[0]['start_date'])?$faculty_data[0]['start_date']:'';?>">
                    </div>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="end_date" name="end_date" placeholder="End Date" value="<?php echo isset($faculty_data[0]['end_date'])?$faculty_data[0]['end_date']:'';?>">
                    </div>
                  </div>
                  <span class="note-error" id="date_error"></span>
                </div><?php */?>

                <!--Previousely start date and end date was used to save dates now, as per client requirement, same is used to save start_date = year and end_date = month-->
                <div class="col-sm-12" id="agency_association_period1" style="display: none;">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label"></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="year" name="start_date" placeholder="--Year--" value="<?php echo isset($faculty_data[0]['start_date'])?$faculty_data[0]['start_date']:'';?>" onkeypress="return isNumber(event)" maxlength="4">
                      <span class="note">Note: Please Enter only Number.</span>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="month" name="end_date" placeholder="--Month--" value="<?php echo isset($faculty_data[0]['end_date'])?$faculty_data[0]['end_date']:'';?>" onkeypress="return isNumber(event)" maxlength="2">
                      <span class="note">Note: Please Enter only Number.</span>
                    </div>
                  </div>
                  <span class="note-error" id="date_error"></span>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Interested to take sessions on<span style="color:#F00">*</span></label>
                    <div class="col-sm-9">
                      <div class="form-check">
                        <input type="radio" class="form-check-input radiocls" id="radio1" name="session_interested_in" value="1" data-parsley-errors-container="#session_interested_error" data-parsley-required <?php if(isset($faculty_data[0]['session_interested_in']) && $faculty_data[0]['session_interested_in'] == '1') { echo 'checked="checked"'; };?>>Banking Subjects
                        <label class="form-check-label" for="radio1"></label>
                        
                        <input type="radio" class="form-check-input radiocls" id="radio2" name="session_interested_in" value="2" data-parsley-errors-container="#session_interested_error" data-parsley-required <?php if(isset($faculty_data[0]['session_interested_in']) && $faculty_data[0]['session_interested_in'] == '2') { echo 'checked="checked"'; };?> >Soft Skill in Banking
                        <label class="form-check-label" for="radio1"></label>
                        
                        <input type="radio" class="form-check-input radiocls" id="radio3" name="session_interested_in" value="3" data-parsley-errors-container="#session_interested_error" data-parsley-required <?php if(isset($faculty_data[0]['session_interested_in']) && $faculty_data[0]['session_interested_in'] == '3') { echo 'checked="checked"'; };?>>Banking Subjects and Soft Skill in Banking
                        <label class="form-check-label" for="radio1"></label>
                        <span class="note-error" id="session_interested_error"> <?php echo form_error('session_interested'); ?></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Qualification / Experience in Soft Skill in BFSI Sector, if any</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="softskills_banking_exp" name="softskills_banking_exp" placeholder="Qualification / Experience in Soft Skill in Banking" value="<?php echo isset($faculty_data[0]['softskills_banking_exp'])?$faculty_data[0]['softskills_banking_exp']:'';?>" maxlength="100" onkeypress="return alphanumeric(event)">
                      <span class="note">Note: Please Enter only 100 characters.</span>
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Experience/Comments on training specific activities, if any </label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="training_activities_exp" name="training_activities_exp" placeholder="Experience/Comments on training specific activities" value="<?php echo isset($faculty_data[0]['training_activities_exp'])?$faculty_data[0]['training_activities_exp']:'';?>"  maxlength="100" onkeypress="return alphanumeric(event)">
                      <span class="note">Note: Please Enter only 100 characters.</span>
                    </div>
                  </div>
                </div>

                <?php 
                  if($action != 'add'){
                    $status = isset($faculty_data[0]['status'])?$faculty_data[0]['status']:'';

                    if($status == 'In Review') {
                      $labelCls = 'text-warning';
                    } 
                    else if($status == 'Active'){
                      $labelCls = 'text-success';
                    }
                    else{
                      $labelCls = 'text-danger';
                    }
                ?>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label">Status </label>
                      <div class="col-sm-9">
                        <label for="roleid" class="<?php echo $labelCls; ?>"><?php echo $status; ?></label>
                      </div>
                    </div>
                  </div>
                <?php } ?>

                <?php if($action == 'view'){ 
                  if(count($log_data) > 0) { ?>
                    <div class="col-xs-12" >
                      <div class="box">
                        <div class="box-header with-border">
                          <h3 class="box-title">Faculty Status Logs</h3>
                          <div class="box-tools pull-right"> 
                              <!-- Collapse Button -->
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>
                          </div>
                          <!-- /.box-tools --> 
                        </div>
                        <div class="box-body">
                            <table id="example1"  class="table table-bordered dt-responsive table-hover" width="100%">
                              <thead> 
                                <tr>
                                  <th width="5%">Sr. No.</th>          
                                    <th width="15%">Status</th>
                                    <th width="20%">Reason</th>
                                    <th width="20%">Date/Time</th>
                                  </tr>
                              </thead>
                              <tbody>
                              <?php foreach($log_data as $key => $value) { ?>
                                <tr>
                                  <td><?php echo $key+1; ?></td>
                                  <td><?php echo $value['status']; ?></td>
                                  <td><?php echo $value['reason']; ?></td>
                                  <td><?php echo $value['created_on']; ?></td>
                                </tr>
                              <?php }?>
                              </tbody>
                            </table>
                        </div>
                      </div>
                    </div>
                  <?php }
                 }?>
                
                <div class="col-sm-6 col-sm-offset-3">
                  <div class="col-sm-12"> 
                    <center>
                     <?php if($action == 'add' || $action == 'edit') { ?>
                     <button type="submit" class="btn btn-success btn_submit" name="btn_submit" id="btn_submit" >Submit</button>
                     <?php } ?>
                     <a href="<?php echo base_url('iibfdra/Version_2/faculty'); ?>" class="btn btn-warning mr-2" >Back</a>  
                     <!-- <button type="reset" class="btn btn-danger" name="btn_reset" id="btn_reset">Cancel</button> -->
                    </center>
                  </div>
                </div>
              
              </form> 
            </div>
          </div>
        </div>
      </div>
  </section>
</div>

<?php /*
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> <?php */ ?>
<?php /*?><script src='<?php echo base_url("assets/js/jquery.elevatezoom.js");?>'></script><?php */?>

<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
  var action = '<?php echo $action; ?>';
  var institute_id = '<?php echo $institute_id; ?>';
  var faculty_id = '<?php echo isset($faculty_data[0]['faculty_id'])?$faculty_data[0]['faculty_id']:''; ?>';

  if(action == 'view'){
    $('#faculty_form input[type="text"]').prop("disabled", true);
    $('.salutation_cls').prop("disabled", true);
    $('.radiocls').prop("disabled", true);
  }

  
  $(document).ready(function () 
  {
    $('#dob').datepicker(
    {
        format: "dd/mm/yyyy",
        //startDate: '',
        endDate: '<?php echo date('d/m/Y', strtotime("-25years")); ?>',      
        autoclose: true,
        //todayBtn: "linked", 
        keyboardNavigation: true, 
        forceParse: false, 
        //calendarWeeks: true, 
        //todayHighlight:true, 
        clearBtn: true         
    }).attr('readonly', 'readonly');


    var id_count = '<?php echo @$id_count; ?>';
    if(id_count != ''){
      newid = id_count;
    }
    else{
      newid = 1;
    }

    $(function() {
     // $("#start_date").datepicker({ dateFormat: 'yy-mm-dd' });
      //$("#end_date").datepicker({ dateFormat: 'yy-mm-dd'});

      //$("#start_date").datepicker({ dateFormat: 'yy' });
      //$("#end_date").datepicker({ dateFormat: 'mm'});
    });

    $('#btn_add_workex').click(function(){

      if($(":input[name='work_exp[]']").length >= 3){
        $('.cls_add_workex').attr('disabled',true);
        return false;
      }
      else{
        $('.cls_add_workex').removeAttr('disabled');
      }

      var repeatable_workex = '<div class="col-sm-12" id="textbox-label"><input type="hidden" name="faculty_id[]" id="faculty_id'+newid+'" value=""><div class="form-group"><label for="roleid" class="col-sm-3 control-label"></label><div class="col-sm-3"><input type="text" class="form-control" id="work_exp'+newid+'" name="work_exp[]" placeholder="e.g. ABC Bank/ABC Agency"  value=""><span class="note-error" id="work_exp_error'+newid+'"></span></div><div class="col-sm-3"><input type="text" class="form-control" id="emp_id'+newid+'" name="emp_id[]" placeholder="e.g. Manager - ABC123"  value=""><span class="note-error" id="emp_id_error'+newid+'"></span></div><div class="col-sm-3"> <input type="text" class="form-control" id="gross_duration'+newid+'" name="gross_duration[]" placeholder="e.g. 10 Years 1 month"  value=""><span class="note-error" id="gross_duration_error'+newid+'"></span></div></div></div>';
      
      $('#repeatable_workex').append(repeatable_workex);
      $('#work_exp'+newid).attr('data-parsley-required','true');
      $('#work_exp'+newid).attr('data-parsley-errors-container','#work_exp_error'+newid);

      $('#emp_id'+newid).attr('data-parsley-required','true');
      $('#emp_id'+newid).attr('data-parsley-errors-container','#emp_id_error'+newid);

      $('#gross_duration'+newid).attr('data-parsley-required','true');
      $('#gross_duration'+newid).attr('data-parsley-errors-container','#gross_duration_error'+newid);

      newid++;
    });// add radio add more (parent)

    $(document).on('click', '#btn_remove_workex', function() {

      $('#work_exp').removeAttr('data-parsley-required');
      $('#work_exp').removeAttr('data-parsley-errors-container');

      $('#emp_id').removeAttr('data-parsley-required','true');
      $('#emp_id').removeAttr('data-parsley-errors-container');

      $('#gross_duration').removeAttr('data-parsley-required','true');
      $('#gross_duration').removeAttr('data-parsley-errors-container');

      $('div#textbox-label').last().remove(); 
      newid--;

      //alert('--'+newid);
      //console.log(newid);
      //console.log($(":input[name='work_exp[]']").length);
      if($(":input[name='work_exp[]']").length < 3){
        $('.cls_add_workex').removeAttr('disabled');
      }
    });

  });

  $('#pan_no').parsley();
    
  window.Parsley.addValidator('pan_no_exist', {
      
    validateString: function(value){
      return $.ajax({
        url: base_url+"iibfdra/Version_2/faculty/check_pan_no",
        method:"POST",
        data: {'action':action,pan_no: value, faculty_id:faculty_id, institute_id:institute_id},
        dataType: 'JSON',       
        success:function(data)
        {
            return true;
        }
      });
    }   
  });

  window.Parsley.addValidator("minimumage", {
    validateString: function(value, requirements) {
      // get validation requirments
      var reqs = value.split("/"),
        day = reqs[0],
        month = reqs[1],
        year = reqs[2];

      // check if date is a valid
      var birthday = new Date(year + "-" + month + "-" + day);

      // Calculate birtday and check if age is greater than 18
      var today = new Date();

      var age = today.getFullYear() - birthday.getFullYear();
      var m = today.getMonth() - birthday.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
        age--;
      }

      return age >= requirements;
    }
  });


 /* $("#faculty_photo,#faculty_photo_edit").change(function(e) {
    const file = this.files[0];
    var file_size = this.files[0].size;

    var allowedFiles = [".jpg", ".jpeg", ".png", ".JPG", ".JPEG", ".PNG"];
    var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");

    var reader = new FileReader();

    reader.onloadend = function() {
      if (reader.result == 'data:') {
        $('#faculty_photo_error').text('This file is corrupted');
        $('#btn_submit').attr('disabled',true);
      } else {
        $('#faculty_photo_edit_error').text('This file can be uploaded');
        if (!regex.test(file.name)) {
          if(action == 'add'){
            $('#faculty_photo_error').text("Please upload " + allowedFiles.join(', ') + " only.");
          }
          else{
            $('#faculty_photo_edit_error').text("Please upload " + allowedFiles.join(', ') + " only.");
          }
          $('#btn_submit').attr('disabled',true);
        }
        else{
          if(file_size>2097152) {
            if(action == 'add'){
              $("#faculty_photo_error").text("Please upload file less than 2MB");
            }
            else{
              $("#faculty_photo_edit_error").text("Please upload file less than 2MB");
            }
            $('#btn_submit').attr('disabled',true);
          } 
          else{
            if(action == 'add'){
              $("#faculty_photo_error").text("");
            }
            else{
              $("#faculty_photo_edit_error").text("");
            }
            $('#btn_submit').attr('disabled',false);
          }
        }
      }
      $('#faculty_photo_img').attr('src', reader.result);
    }
    reader.readAsDataURL(file);     
     
  });*/

  /*$("#pan_photo,#pan_photo_edit").change(function(e) {
    const file = this.files[0];
    var reader = new FileReader();
    reader.onloadend = function() {
       if (reader.result == 'data:') {
        $('#pan_photo_error').text('This file is corrupted');
        $('#btn_submit').attr('disabled',true);
      } else {
        $('#pan_photo_error_error').text('This file can be uploaded');
        $('#btn_submit').attr('disabled',false);
      }
      $('#pan_photo_img').attr('src', reader.result);
    }
    reader.readAsDataURL(file);  

    var allowedFiles = [".jpg", ".jpeg", ".png", ".JPG", ".JPEG", ".PNG"];
    var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");

    var file_size = this.files[0].size;
    console.log(file.name);
    if (!regex.test(file.name)) {
      console.log('if');
      if(action == 'add'){
        $('#pan_photo_error').text("Please upload " + allowedFiles.join(', ') + " only.");
      }
      else{
        $('#pan_photo_edit_error').text("Please upload " + allowedFiles.join(', ') + " only.");
      }
      $('#btn_submit').attr('disabled',true);
    }
    else{
      if(file_size>2097152) {
        console.log('ifif');
        if(action == 'add'){
          $("#pan_photo_error").text("Please upload file less than 2MB");
        }
        else{
          $("#pan_photo_edit_error").text("Please upload file less than 2MB");
        }
        $('#btn_submit').attr('disabled',true);
      } 
      else{
        console.log('else');
        if(action == 'add'){
          $("#pan_photo_error").text("");
        }
        else{
          $("#pan_photo_edit_error").text("");
        }
        $('#btn_submit').attr('disabled',false);
      }
    }     
  });*/

  /*$("#start_date,#end_date").change(function(e) {
    var start_date =  $('#start_date').val();
    var end_date =  $('#end_date').val();
    console.log('start_date:'+start_date+'end_date:'+end_date);
    if(start_date != '' && end_date != ''){
        if(start_date >= end_date){
          $('#date_error').text('Please Enter Satrt Date less than End Date');
          $('#btn_submit').attr('disabled',true);
        }
        else{
          $('#date_error').text('');
          $('#btn_submit').attr('disabled',false);
        }
      }
      else{
        $('#date_error').text('');
        $('#btn_submit').attr('disabled',false);
      }
  });*/

  var DRA_training_faculty_exp = '<?php echo $faculty_data[0]['DRA_training_faculty_exp']; ?>';
  if(DRA_training_faculty_exp != ''){
    $('#agency_association_period').css('display','block');
    $('#agency_association_period1').css('display','block');
  }
  else{
    $('#agency_association_period').css('display','none');
    $('#agency_association_period1').css('display','none');
  }

  $(document).on('keyup', '#DRA_training_faculty_exp', function(e) {
    var value =  $(this).val();
    if(value != ''){
      $('#agency_association_period').css('display','block');
      $('#agency_association_period1').css('display','block');
    }
    else{
      $('#agency_association_period').css('display','none');
      $('#agency_association_period1').css('display','none');
    }
  });

  

  function submit_form()
  {
    $("#page_loader").show();
    //var form = $(this);
    $("#faculty_form").parsley().validate();
    console.log($("#faculty_form").parsley().validate());
    
    if ($("#faculty_form").parsley().isValid())
    {
      console.log('no error found');
      $('#faculty_form').submit();
    }
    else
    {
      console.log('else error in parsley');
      $("#page_loader").hide();
       // e.preventDefault();
    }
  }

/* function imageCheck(event) {
    var file = document.querySelector('input[type=file]').files[0];
    var reader = new FileReader();
    reader.onload = function () {
        if (reader.result == 'data:') {
            alert('This file is corrupt')
        } else {
            alert('This file can be uploaded')
        }
    }
    reader.readAsDataURL(event.target.files[0]);
  }
*/

  function removeImg1(faculty_id){
      //$("#loading").show();
      //console.log('removeImg1'+faculty_id);
      $("#faculty_photo_show").hide();
      //$('#btn_remove').hide();
      $('#faculty_upload_image').css('display','block');
      $('#faculty_upload_image1').css('display','block');
      $('#faculty_photo_edit').attr('data-parsley-required','true');
      $('#faculty_photo_edit').attr('data-parsley-errors-container',"#faculty_photo_edit_error");

     /* $.ajax({
        type: 'POST',
        url: base_url+"iibfdra/Version_2/faculty/removeFile",
        data: {faculty_id: faculty_id, img:'faculty_photo'},
        dataType: "text",
        success: function(data) { 
          //console.log(data);
          if(data.trim() == 1){
            $("#faculty_photo_show").hide();
            //$('#btn_remove').hide();
            $('#faculty_upload_image').css('display','block');
            $('#faculty_upload_image1').css('display','block');
            $('#faculty_photo_edit').attr('data-parsley-required','true');
            $('#faculty_photo_edit').attr('data-parsley-errors-container',"#faculty_photo_edit_error");
          }
          else if(data.trim() == 0){
            //$("#loading").hide();
            alert('error in deletion');
          }
      
        }
      });*/
  }

  function removeImg2(faculty_id){
      //$("#loading").show();
      $('#pan_photo_show').hide();
      //$('#btn_remove').hide();
      $('#pan_upload_image').css('display','block');
      $('#pan_upload_image1').css('display','block');
      $('#pan_photo_edit').attr('data-parsley-required','true');
      $('#pan_photo_edit').attr('data-parsley-errors-container',"#pan_photo_edit_error");
/*$.ajax({
        type: 'POST',
        url: base_url+"iibfdra/Version_2/faculty/removeFile",
        data: {faculty_id: faculty_id, img:'pan_photo'},
        dataType: "text",
        success: function(data) { 
          if(data.trim() == 1){
            $('#pan_photo_show').hide();
            //$('#btn_remove').hide();
            $('#pan_upload_image').css('display','block');
            $('#pan_upload_image1').css('display','block');
            $('#pan_photo_edit').attr('data-parsley-required','true');
            $('#pan_photo_edit').attr('data-parsley-errors-container',"#pan_photo_edit_error");

            //$('#commodity_photo_img').attr('src', '');
            //$('#btn_remove').hide();
            //$('#btn_download').hide();
         
          }
          else if(data.trim() == 0){
            //$("#loading").hide();
            alert('error in deletion');
          }
      
        }
      });*/
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
</script>
