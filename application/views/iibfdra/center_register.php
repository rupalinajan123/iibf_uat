<style>
.control-label {
	font-weight: bold !important;
}
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> Add Center Form </h1>
  </section>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
  <form class="form-horizontal" name="frmDrACenter" id="frmDrACenter"  method="post" action="<?php echo base_url();?>iibfdra/Center" enctype="multipart/form-data" data-parsley-validate="parsley">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add New Center</h3>
              <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/Center/listing" class="btn btn-warning">Back</a> </div>
            </div>
            <div class="box-body">
              
			  <?php 
			  
			  if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } 
			  
			   if($var_errors!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $var_errors; ?> </div>
              <?php } 
			  
			  if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } 
			 if(validation_errors()!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo validation_errors(); ?> </div>
              <?php }
			  if(@$var_errors!='')
			  {?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $var_errors; ?> </div>
              <?php } ?>
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <select class="form-control" id="state" name="state" required >
                    <option value="">Select</option>
                    <?php if(count($states) > 0){
                                foreach($states as $row1){  ?>
                    <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                    <?php } } ?>
                  </select>
                  <input hidden="statepincode" id="statepincode" value="">
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Name Of Location(City) <span style="color:#F00">*</span></label>
                <div class="col-sm-3">
                  <select class="form-control city" id="city" name="city" required >
                    <option value="">Select</option>
                    <?php if(count($cities) > 0){
                              foreach($cities as $row1){  ?>
                    <option value="<?php echo $row1['id'];?>" <?php echo  set_select('city', $row1['id']); ?>><?php echo $row1['city_name'];?></option>
                    <?php } } ?>
                  </select>
                </div>
                <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode"  value="<?php echo set_value('pincode');?>"  onkeypress="return event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46 "  data-parsley-maxlength="6"  maxlength="6" size="6" data-parsley-check_center_pincode data-parsley-type="number" data-parsley-trigger-after-failure="focusout"  required >
                  (Max 6 digits) </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line1<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1"  value="<?php echo set_value('addressline1');?>"  data-parsley-maxlength="75" maxlength="75" required >
                 <!--data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"  -->
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line2</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo set_value('addressline2');?>"  data-parsley-maxlength="75" maxlength="75" >
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line3</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo set_value('addressline3');?>"  data-parsley-maxlength="75" maxlength="75"  >
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line4</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo set_value('addressline4');?>" data-parsley-maxlength="75" maxlength="75" >
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">District <span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="district" name="district" placeholder="District"  value="<?php echo set_value('district');?>"  data-parsley-maxlength="30" maxlength="30" required >
                </div>
              </div>

              <!--<div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Office Number<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="office_no" name="office_no" placeholder="Office Number" value="<?php echo set_value('office_no');?>" data-parsley-pattern="[0-9 _,]*" data-parsley-minlength="10" maxlength="10" data-parsley-maxlength="12" data-parsley-check_office_no data-parsley-trigger-after-failure="focusout" onkeypress="return event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46 " >
                </div>
              </div>-->
              
               <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Office Number<span style="color:#F00">*</span></label>
                <div class="col-sm-2">
                    STD Code (Max 5 digits)
                     <input type="text" class="form-control" id="stdcode" name="stdcode" placeholder="STD Code" value="<?php echo set_value('stdcode');?>" data-parsley-type="number" data-parsley-maxlength="5" data-parsley-minlength="4" size="5" maxlength="5"  required >    
                </div> 
                <div class="col-sm-2">
                    Phone No (Max 8 digits)
                    <input type="text" class="form-control" id="office_no" name="office_no" placeholder="Office Number" value="<?php echo set_value('office_no');?>" data-parsley-pattern="[0-9 _,]*" data-parsley-minlength="7" data-parsley-maxlength="12" data-parsley-check_office_no data-parsley-trigger-after-failure="focusout" maxlength="8"  required>                   
                </div> 
			</div>
              
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Contact Person Name<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" placeholder="Contact Person Name"  value="<?php echo set_value('contact_person_name');?>"  maxlength="90" required>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="contact_person_mobile" name="contact_person_mobile" placeholder="Mobile Number"  value="<?php echo set_value('contact_person_mobile');?>" data-parsley-type="number" placeholder="Mobile Number" maxlength="10" minlength="10"  onkeypress="return event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46 "  required >
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Email id<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="email_id" name="email_id" placeholder="Email id"  data-parsley-type="email" value="<?php echo set_value('email_id');?>" maxlength="80"  data-parsley-maxlength="80"   data-parsley-check_cpemail data-parsley-trigger-after-failure="focusout" required >
                  (Enter valid and correct email ID to receive communication) </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Center Type <span style="color:#F00">*</span></label>
                <div class="col-sm-5"><br>
                  <input type="radio" class="minimal cls_gender due_diligence" id="Regular"  name="center_type"  value="R" <?php echo (set_value('center_type')=='R')?" checked=' checked'":""?>    <?php echo set_radio('center_type','Regular'); ?> checked  required>
                  Regular
                  <input type="radio" class="minimal cls_gender due_diligence" id="Temporary" name="center_type"  value="T" <?php echo (set_value('center_type')=='T')?" checked=' checked'":""?>     <?php echo set_radio('center_type','Temporary'); ?> required >
                  Temporary </div>
              </div>
              <div class="T divbox">
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">1. Faculty Name<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name1" name="faculty_name1" placeholder="1. Faculty Name"  value="<?php echo set_value('faculty_name1');?>">
                  </div>
                </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="roleid" class="col-sm-8 control-label">1. Faculty Qualification<span style="color:#F00">*</span></label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="faculty_qualification1" name="faculty_qualification1" placeholder="1. Faculty Qualification"  value="<?php echo set_value('faculty_qualification1');?>" >
                  </div>
                </div>
              </div>
                <div class="form-group">
                  <label for="cv1" class="col-sm-2 control-label">1.CV (PDF only)<span style="color:#F00">*</span></label>
                  <div class="col-sm-3">
                  <input  type="file" name="cv1" id="cv1"  autocomplete="off"  onChange="validateFileTypeCV('cv1')" >
                   <input type="hidden" id="hiddenuploadfile1" name="hiddenuploadfile1">
                    <div id="error_file1"></div>
                    <span class="error"><?php echo form_error('cv1');?></span> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">2. Faculty Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name2" name="faculty_name2" placeholder="2. Faculty Name"  value="<?php echo set_value('faculty_name2');?>" >
                  </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                  <label for="roleid" class="col-sm-8 control-label">2. Faculty Qualification</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="faculty_qualification2" name="faculty_qualification2" placeholder="2. Faculty Qualification"  value="<?php echo set_value('faculty_qualification2');?>" >
                  </div>
                </div>
              </div>
              <div class="form-group">
                  <label for="cv2" class="col-sm-2 control-label">2.CV (PDF only)</label>
                  <div class="col-sm-3">
                  <input  type="file" name="cv2" id="cv2"  autocomplete="off"  o onChange="validateFileTypeCV('cv2')" >
                   <input type="hidden" id="hiddenuploadfile1" name="hiddenuploadfile1">
                    <div id="error_file1"></div>
                    <span class="error"><?php echo form_error('cv2');?></span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">3. Faculty Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name3" name="faculty_name3" placeholder="3. Faculty Name"  value="<?php echo set_value('faculty_name3');?>" >
                  </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                  <label for="roleid" class="col-sm-8 control-label">3. Faculty Qualification</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="faculty_qualification3" name="faculty_qualification3" placeholder="3. Faculty Qualification"  value="<?php echo set_value('faculty_qualification3');?>" >
                  </div>
                </div>
                </div>
                <div class="form-group">
                  <label for="cv3" class="col-sm-2 control-label">3.CV (PDF only)</label>
                  <div class="col-sm-3">
                  <input  type="file" name="cv3" id="cv3"  autocomplete="off"   onChange="validateFileTypeCV('cv3')" >
                   <input type="hidden" id="hiddenuploadfile1" name="hiddenuploadfile1">
                    <div id="error_file1"></div>
                    <span class="error"><?php echo form_error('cv3');?></span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">4. Faculty Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name4" name="faculty_name4" placeholder="4. Faculty Name"  value="<?php echo set_value('faculty_name4');?>" >
                  </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                  <label for="roleid" class="col-sm-8 control-label">4. Faculty Qualification</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="faculty_qualification4" name="faculty_qualification4" placeholder="4. Faculty Qualification"  value="<?php echo set_value('faculty_qualification4');?>" >
                  </div>
                </div>
              </div>
                <div class="form-group">
                  <label for="cv4" class="col-sm-2 control-label">4.CV (PDF only)</label>
                  <div class="col-sm-3">
                  <input  type="file" name="cv4" id="cv4"  autocomplete="off"   onChange="validateFileTypeCV('cv4')"  >
                   <input type="hidden" id="hiddenuploadfile1" name="hiddenuploadfile1">
                    <div id="error_file1"></div>
                    <span class="error"><?php echo form_error('cv4');?></span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">5. Faculty Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name5" name="faculty_name5" placeholder="5. Faculty Name"  value="<?php echo set_value('faculty_name5');?>" >
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-8 control-label">5. Faculty Qualification</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="faculty_qualification5" name="faculty_qualification5" placeholder="5. Faculty Qualification"  value="<?php echo set_value('faculty_qualification5');?>" >
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="cv5" class="col-sm-2 control-label">5.CV (PDF only)</label>
                  <div class="col-sm-3" >
                  <input  type="file" name="cv5" id="cv5"  autocomplete="off"  onChange="validateFileTypeCV('cv5')"  >
                  <input type="hidden" id="hiddenuploadfile1" name="hiddenuploadfile1">
                  <div id="error_file1"></div>
                  <span class="error"><?php echo form_error('cv5');?></span> </div>
                </div>
                <div id="file1">
                <div class="form-group">
                  <label for="upload_file1" class="col-sm-4 control-label">Request Letter From Accredited Institute <span style="color:#F00">*</span></label>
                  <div class="col-sm-5" >
                  <input  type="file" name="upload_file1" id="upload_file1"  autocomplete="off" autocomplete="off" onChange="validateFileType('upload_file1')" accept=".jpg,.jpeg,.png,.pdf" >
                   <input type="hidden" id="hiddenuploadfile1" name="hiddenuploadfile1">
                    <div id="error_file1"></div>
                    <span class="error"><?php echo form_error('upload_file1');?></span> </div>
                </div>
              </div>
                <div class="form-group">
                  <label for="upload_file2" class="col-sm-4 control-label">Letter From Sponsoring Agency </label>
                  <div class="col-sm-5" id="center_file">
                    <input  type="file" name="upload_file2" id="upload_file2"  autocomplete="off" onChange="validateFileType('upload_file2')" accept=".jpg,.jpeg,.png,.pdf" >
                    <input type="hidden" id="hiddenuploadfile2" name="hiddenuploadfile2">
                    <div id="error_file2"></div>
                    <span class="error"><?php echo form_error('upload_file2');?></span> 
                  </div>
                </div>
              </div>
              <!-- divbox -->
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Due diligence </label>
                <div class="col-sm-3">
                  <input type="radio" class="minimal cls_gender due_diligence" id="due_diligence"   name="due_diligence"  value="Yes" <?php echo set_radio('due_diligence', 'Yes'); ?>>
                  Yes
                  <input type="radio" class="minimal cls_gender due_diligence" id="due_diligence"  name="due_diligence"  value="No" <?php echo set_radio('due_diligence', 'No'); ?>>
                  No </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">GST No</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="Enter GST No"  value="<?php echo set_value('gstin_no');?>"  data-parsley-maxlength="15" maxlength="15" data-parsley-pattern="\d{2}[A-Z]{5}\d{4}[A-Z]{1}\d[Z]{1}[A-Z\d]{1}">
                </div>
              </div>              
              
              <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Remarks  </label>
                      <div class="col-sm-6">
          				<textarea style="width:100%; text-align:left;" name="remarks" id="remarks" class="control-label" data-parsley-maxlength="500" maxlength="500" ><?php echo set_value('remarks');?></textarea>
                      </div>
               </div>
             
            </div>
          </div>
          <!-- agency address section -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Agency Registered Office Address</h3>
                </div>
                  <div class="box-body">
                    <?php 
                      $loginAgency  = $this->session->userdata('dra_institute');
                      //echo"<pre>"; print_r($city_name); exit;
                     ?>
                     <div class="col-md-6">
                      <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Name Of Agency</label>
                      <div class="col-sm-6"> <?php echo $loginAgency['institute_name']; ?> </div>
                      </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Main Office Address</label>
                        <div class="col-sm-5"> <?php echo $loginAgency['address1'];?>,<br>
                          <?php echo $loginAgency['address2'];?>
                          <?php echo $loginAgency['address3'];?>
                          <?php echo $loginAgency['address4'];?>
                          <?php echo $loginAgency['address5'];?>   
                        </div>
                      <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">City</label>
                        <div class="col-sm-6">  
                          <?php if(is_numeric($loginAgency['address6']))
                          {
                           echo strtoupper($city_name[0]['city_name']);
                          }
                          else
                          {
                           echo strtoupper($loginAgency['address6']);
                          }?>   </div>
                      </div>
                    </div>
                      
                    </div><!--/ col-md-6 -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">State</label>
                        <div class="col-sm-5"> 
                        <?php if(count($states) > 0){
                                foreach($states as $row1){
                                if($loginAgency['ste_code']== $row1['state_code']){
                                  echo $row1['state_name'];
                                }  ?>
                      <?php } } ?>
                      </div>
                    </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Pincode</label>
                        <div class="col-sm-5"> <?php echo $loginAgency['pin_code'];?> </div>
                      </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Phone Number</label>
                        <div class="col-sm-5">
                       <?php if($loginAgency['inst_stdcode']!="")
                        {
                          echo $loginAgency['inst_stdcode'].' -';
                        }else
                        {
                          echo "-";
                        } ?>
                        <?php if($loginAgency['phone']!="")
                        {
                          echo $loginAgency['phone'];
                        }else
                        {
                          echo "-";
                        } ?>
						
                         </div>
                      </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Mobile Number</label>
                        <div class="col-sm-5"> <?php echo $loginAgency['mobile'];?> </div>
                      </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-5"> <?php echo $loginAgency['email'];?> </div>
                      </div>
                    </div><!--/ col-md-6 -->
                  </div><!--.box-body-->
          </div><!--.box-info-->
          <!-- Submit & preview section -->
          <div class="box box-info">
            <div class="box-header with-border"> </div>
              <div class="box-body">
                <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Select to display <span style="color:#090;">'Agency'</span> address on the invoice<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="radio" class="chk1" id="invoice_flag"  name="invoice_flag"  value="AS" <?php echo set_radio('invoice_flag', 'AS'); ?> required >
                     </div>
                </div>
                <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Select to display <span style="color:#090;">'Accreditation Centre'</span> address on the invoice<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="radio" class="chk2" id="invoice_flag"  name="invoice_flag"  value="CS" <?php echo set_radio('invoice_flag', 'CS'); ?> required >
                      </div>
               </div>
              <div class="box-footer">
              
               <div class="box-header with-border">
                   <div class="col-sm-12"> 
                    <h3 class="box-title" style="color:#333">
                      <input name="declaration1" value="1" type="checkbox" >
                      &nbsp;<b>"I Confirm that all the Details Entered are Correct as per my Knowledge." </b></h3>
                  </div>
                  <br>
                  </div>
              
                <div class="col-sm-6 col-sm-offset-3">
                  <div class="col-sm-12"> 
                   <br>
                    <center>
                     <button type="submit" class="btn btn-info"  name="preview" onclick="javascript:return dracentercheckform();" id="preview">Submit And Preview</button>
                      <input type="hidden" name="btnSubmit" value="Save" id="btn_preview"/>
                      <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset">Reset</button>
                    </center>
                  </div>
                </div>
              </div>         
            </div><!--.box-body-->
          </div><!--.box-info-->
        </div>
      </div>
    </section>
  </form>
</div>
<style type="text/css">
.divbox
{     
  display: none;      
}
</style>
<!--<link href="<?php //echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">--> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
 <script src="<?php echo base_url();?>js/validation_dra_center.js?<?php echo time(); ?>"></script>  
<script>

// Check file extention custom code added by Manoj on 3 Apr 2019 suggested by sonal
function validateFileType(filed_id){
	
	var fileName 	= $('#'+filed_id)[0].files[0].name; // document.getElementById(filed_id).value;		
	var idxDot 		= fileName.lastIndexOf(".") + 1;
	var extFile 	= fileName.substr(idxDot, fileName.length).toLowerCase();
	
		 console.log(extFile);
        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png" || extFile=="pdf"){            
			 console.log('ACCEPT EXTENTION');
        }else{
            alert("Upload Image/PDF files only.");
			$('#'+filed_id).val('');
			 console.log('err');
        }
}

// file extension validation for CV upload by Manoj
function validateFileTypeCV(filed_id){
	
	var fileName 	= $('#'+filed_id)[0].files[0].name; // document.getElementById(filed_id).value;		
	var idxDot 		= fileName.lastIndexOf(".") + 1;
	var extFile 	= fileName.substr(idxDot, fileName.length).toLowerCase();
	
		 console.log(extFile);
        if (extFile=="pdf"){            
			 console.log('ACCEPT EXTENTION');
        }else{
            alert("Please upload PDF files only.");
			$('#'+filed_id).val('');
			 console.log('err');
        }
}

$(document).ready(function() 
{
	$('input[name="center_type"]').change(function () {
		var val = $('input[name=center_type]:checked', '#frmDrACenter').val();
		if(val == 'R')
		{
			$('#due_diligence').prop('required', true);
		}
		if(val != ''){
			check_city();	
		}
		
	});
	
	$('.due_diligence').click(function(event){
		var inst_type_val = $('input[name=center_type]:checked', '#frmDrACenter').val();
		var due_diligence_val = $('input[name=due_diligence]:checked', '#frmDrACenter').val();
		if(inst_type_val == 'R' && due_diligence_val == 'No')
		{
			$("#preview").hide();
			$("#btn_preview").show();
			//$("#preview").attr("disabled", "disabled");
			alert("Please carry out Due Diligence and resubmit application");
		}
		else
		{
		  $("#preview").show();
			$("#btn_preview").hide();
			$('#preview').attr('disabled', false);
		}
	});
});
/*hide/show div from regular/temporary radio button*/
$(document).ready(function(){
    $('input[name="center_type"]').click(function(){
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $(".divbox").not(targetBox).hide();
        $(targetBox).show();
		var center_type=$("input[type=radio][name='center_type']:checked").val()
		if(center_type != ''){
			check_city();	
		}
    });
});



/*tick on regular/temporary radio button */
$(document).ready(function(){
    var center_type=$("input[type=radio][name='center_type']:checked").val()

	if(center_type=='T')
	{
		$(".divbox").not(center_type).show();
        $(center_type).hide();		
		if(center_type != ''){
			check_city();	
		}
	}
});

/* Get City From State in Agency tab */
$('#state').on('change',function(){
var state_code = $(this).val();
if(state_code){
  $.ajax({
    type:'POST',
    url: site_url+'iibfdra/center/getCity',
    data:'state_code='+state_code,
    success:function(html){
      $('#city').show();
      $('#city').html(html);
    }
  });
  }else{
    $('#city').html('<option value="">Select State First</option>');
  }
});

$('#city').on('change',function(){
	var center_type=$("input[type=radio][name='center_type']:checked").val()
	//alert(center_type);
	if(center_type != ''){
		check_city();	
	}
});

//$('#city').on('change',function(){
// check city code at add center added by manoj
function check_city(){	
var city_id = $("#city").val();
var center_type=$("input[type=radio][name='center_type']:checked").val()
console.log(center_type);
	if(city_id != '' && center_type !='' && center_type !='undefined'){
		$.ajax({
			type:'POST',
			url: site_url+'iibfdra/Center/validateCity',
			data:'city_id='+city_id+'&center_type='+center_type,
			success:function(data){
				var city_text = $( "#city option:selected" ).text();
				var data = $.trim(data);
				if(data != 'OK'){
					console.log(data);
					alert('The '+city_text+' location already added for agency')
					$("#city").val('');
				}else{
					console.log(data);
					//alert('The '+city_text+' location Already added for Agency')
					//$("#city").val('');
				}				
			}
		});
	}
}

function check_pincode_call(){	
var state = $("#state").val();
var pincode = $("#pincode").val();
var center_type=$("input[type=radio][name='center_type']:checked").val()
console.log(center_type);
	if(state != '' && pincode !=''){
		$.ajax({
			type:'POST',
			url: site_url+'iibfdra/Center/checkpin',
			data:'state='+state+'&pincode='+pincode,
			success:function(data){
				//var city_text = $.trim( $( "#city option:selected" ).text());
				var data = $.trim(data);
				if(data){
					console.log(data);									
				}else{
					console.log(data);					
				}				
			}
		});
	}
}

//});
/*
// commented by Manoj as discuss with sonal on 2 apr 2019
$('#contact_person_name').keyup(function()
{
 var yourInput = $(this).val();
  re = /[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\^\d+$/]/gi;
  var isSplChar = re.test(yourInput);
  if(isSplChar)
  {
    var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\^\d+$/]/gi, '');
    $(this).val(no_spl_char);
  }
});

$('#faculty_name1').keyup(function()
{
 var yourInput = $(this).val();
  re = /[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\^\d+$/]/gi;
  var isSplChar = re.test(yourInput);
  if(isSplChar)
  {
    var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\^\d+$/]/gi, '');
    $(this).val(no_spl_char);
  }
});*/

 $("#contact_person_mobile").keypress(function(event){
     var x = $(this).val();
  
   if(x.indexOf('0')==0){
      //alert('First number not be 0');
      $('#contact_person_mobile').val('');
      return false;
    }
     if (event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46) {
       //event.preventDefault();
       return true;
     }
     else{
      return false;
     }
 });

</script> 
