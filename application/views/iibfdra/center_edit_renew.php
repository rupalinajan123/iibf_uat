<style>
.control-label {
	font-weight: bold !important;
}
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> Renew Center Form </h1>
  </section>
  <?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
  <form class="form-horizontal" name="frmDrACenter" id="frmDrACenter"  method="post" action="<?php echo base_url();?>iibfdra/CenterRenew/edit/<?php if($edit_id != ''){ echo $edit_id; }else{ echo '';} ?>" enctype="multipart/form-data" data-parsley-validate="parsley">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Renew Center</h3>
             <!-- <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/CenterRenew" class="btn btn-warning">Back</a> </div>-->
              <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/Center/listing" class="btn btn-warning">Center Listing</a> </div>
            </div>
            <div class="box-body">
              <?php if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
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
              <?php 
    } ?>
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>

             <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                <div class="col-sm-6" disabled >
                  <select class="form-control" id="state" name="state" required >                   
                    <?php if(count($states) > 0){
                               foreach($states as $row1){ 
								   if($row1['state_code'] == $centerResult[0]['state']){
									echo '<option value="'.$row1['state_code'].'">'.$row1['state_name'].'</option>';   
								   }
							   }
							}
							   ?>
                  </select>
                  <input hidden="statepincode" id="statepincode" value="">
                </div>               
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Name of Location (City)<span style="color:#F00">*</span></label>
                <div class="col-sm-3" >
                
                  <input type="hidden"  id="city" name="city"  required value="<?php if(count($cities) > 0){
                  foreach($cities as $row){   
                      if($centerResult[0]['location_name'] == $row['id']){
                          echo  $row['city_name'];
                      } 	
                  } 
              }else{
				  	echo $centerResult[0]['location_name'];
			  }?>" maxlength="30" >
                    
                    
                    
                  <input type="hidden" id="location_name" name="location_name" required value="<?php if(count($cities) > 0){
                  foreach($cities as $row){   
                      if($centerResult[0]['location_name'] == $row['id']){
                          echo  $row['city_name'];
                      } 	
                  } 
              }else{
				  	echo $centerResult[0]['location_name'];
			  }?>" maxlength="30" >
                  
                  
                 <input type="text" class="form-control" id="location_name_view" name="location_name_view"  required value="<?php if(count($cities) > 0){
                  foreach($cities as $row){   
                      if($centerResult[0]['location_name'] == $row['id']){
                          echo  $row['city_name'];
                      } 	
                  }
              }else{
				  	echo $centerResult[0]['location_name'];
			  }?>" maxlength="30" readonly >
                  
                
                <?php /*?>  <input type="text" class="form-control" id="location_name_view" name="location_name_view"  required value="<?php if(count($cities)){ echo $cities[0]['city_name'];}else{echo $centerResult[0]['location_name'];}
                 ?>"  maxlength="30" readonly ><?php */?>
                 	
                 <?php /*?>  <select class="form-control" id="location_name" name="location_name" required  >
                    <option value="<?php echo $cities[0]['id']; ?>"><?php 
                  if(!empty($cities))
                    {
                      echo $cities[0]['city_name'];
                    }else{
						echo $centerResult[0]['location_name'];
					}
                 ?></option>
                    </select>   <?php */?> 
                                 
                </div>
                 <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo $centerResult[0]['pincode'];?>"  onkeypress="return event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46 "   data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-check_center_pincode  data-parsley-type="number" data-parsley-trigger-after-failure="focusout"  >
                  (Max 6 digits) </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line1<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $centerResult[0]['address1'];?>"  data-parsley-maxlength="75" maxlength="75" >
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line2</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $centerResult[0]['address2'];?>"  data-parsley-maxlength="75" maxlength="75" >
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line3</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $centerResult[0]['address3'];?>"  data-parsley-maxlength="75" maxlength="75" >
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Address line4</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $centerResult[0]['address4'];?>" data-parsley-maxlength="75" maxlength="75" >
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">District <span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo $centerResult[0]['district'];?>" data-parsley-maxlength="30" maxlength="30" >
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
                <div class="col-sm-6">            
               
                  <input type="text" class="form-control" id="city_name_display" name="city_name_display" placeholder="city_name_display" required value="<?php if(count($cities) > 0){
                  foreach($cities as $row){   
                      if($centerResult[0]['location_name'] == $row['id']){
                          echo  $row['city_name'];
                      }   
                  } 
              }else{
            echo $centerResult[0]['location_name'];
        }?>"  data-parsley-maxlength="30" maxlength="30" readonly >
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Office Number<span style="color:#F00">*</span></label>
                <div class="col-sm-2">
                    STD Code (Max 5 digits)
                     <input type="text" class="form-control" id="stdcode" name="stdcode" placeholder="STD Code" value="<?php echo $centerResult[0]['stdcode'];?>" data-parsley-type="number" data-parsley-maxlength="5" maxlength="5" required>    
                </div> 
                <div class="col-sm-2">
                    Phone No (Max 8 digits)
                    <input type="text" class="form-control" id="office_no" name="office_no" placeholder="Office Number" value="<?php echo $centerResult[0]['office_no'];?>" data-parsley-pattern="[0-9 _,]*" data-parsley-minlength="7" data-parsley-maxlength="12" data-parsley-check_office_no data-parsley-trigger-after-failure="focusout" maxlength="8" required>                   
                </div> 
			</div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Contact Person Name<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" placeholder="Contact Person Name"  value="<?php echo $centerResult[0]['contact_person_name'];?>"  required>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="contact_person_mobile" name="contact_person_mobile" placeholder="Mobile Number"  value="<?php echo $centerResult[0]['contact_person_mobile'];?>"  onkeypress="return event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46 "   data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" maxlength="10" data-parsley-check_cpmobile data-parsley-trigger-after-failure="focusout" required>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Email id<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="email_id" name="email_id" placeholder="Email id"  data-parsley-type="email" value="<?php echo $centerResult[0]['email_id'];?>"  data-parsley-maxlength="80" maxlength="80" required  data-parsley-check_cpemail data-parsley-trigger-after-failure="focusout" >
                  (Enter valid and correct email ID to receive communication) </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Center Type<span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input type="radio" class="minimal cls_gender due_diligence" id="Regular"   name="center_type" required value="R" <?php if($centerResult[0]['center_type'] == "R"){ ?> checked="checked" <?php } ?> readonly disabled>
                  Regular
                  <input type="radio" class="minimal cls_gender due_diligence" id="Temporary"  name="center_type" required value="T" <?php if($centerResult[0]['center_type'] == "T"){ ?> checked="checked" <?php } ?> readonly >
                  Temporary </div>
              </div>
              
              <div class="T divbox">
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">1. Faculty Name<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name1" name="faculty_name1" placeholder="1. Faculty Name"  value="<?php echo $centerResult[0]['faculty_name1'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">1. Faculty Qualification<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_qualification1" name="faculty_qualification1" placeholder="1. Faculty Qualification"  value="<?php echo $centerResult[0]['faculty_qualification1'];?>" >
                  </div>
                </div>
                 <div class="form-group">
                  <label for="cv1" class="col-sm-4 control-label">1.CV (PDF only)<span style="color:#F00">*</span></label>
                  <div class="col-sm-5" >
                  <input type="file" name="cv1" id="cv1" value="<?php echo $centerResult[0]['cv1'];?>"  onChange="validateFileTypeCV('cv1')" >
                  <?php if($centerResult[0]['cv1']!='')
                    {?>
                     <a href="<?php echo base_url()?>/uploads/iibfdra/agency_center/faculty_cv/<?php echo $centerResult[0]['cv1'];?>" target="_blank"><?php echo $centerResult[0]['cv1'];?></a>
                    <?php 
                     }else{
                     }?>
                  <input type="hidden" name="hiddencv1" id="hiddencv1" value="<?php echo $centerResult[0]['cv1'];?>"  >
                    <div id="error_file1"></div>
                   <!--  <span class="photo_text" style="display:none;"></span>  -->
                    <span class="error"><?php echo form_error('cv1');?></span> 
                  </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">2. Faculty Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name2" name="faculty_name2" placeholder="2. Faculty Name"  value="<?php echo $centerResult[0]['faculty_name2'];?>" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">2. Faculty Qualification</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_qualification2" name="faculty_qualification2" placeholder="2. Faculty Qualification"  value="<?php echo $centerResult[0]['faculty_qualification2'];?>" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="cv2" class="col-sm-4 control-label">2.CV (PDF only)</label>
                  <div class="col-sm-5" >
                  <input type="file" name="cv2" id="cv2" value="<?php echo $centerResult[0]['cv2'];?>"  onChange="validateFileTypeCV('cv2')" >
                  <?php if($centerResult[0]['cv2']!='')
                    {?>
                     <a href="<?php echo base_url()?>/uploads/iibfdra/agency_center/faculty_cv/<?php echo $centerResult[0]['cv2'];?>" target="_blank"><?php echo $centerResult[0]['cv2'];?></a>
                    <?php 
                     }?>
                  <input type="hidden" name="hiddencv2" id="hiddencv2" value="<?php echo $centerResult[0]['cv2'];?>"  ><div id="error_file1"></div>
                   <!--  <span class="photo_text" style="display:none;"></span>  -->
                    <span class="error"><?php echo form_error('cv2');?></span> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">3. Faculty Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name3" name="faculty_name3" placeholder="3. Faculty Name"  value="<?php echo $centerResult[0]['faculty_name3'];?>" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">3. Faculty Qualification</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_qualification3" name="faculty_qualification3" placeholder="3. Faculty Qualification"  value="<?php echo $centerResult[0]['faculty_qualification3'];?>" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="cv3" class="col-sm-4 control-label">3.CV (PDF only)</label>
                  <div class="col-sm-5" id="">
                  <input type="file" name="cv3" id="cv3" value="<?php echo $centerResult[0]['cv3'];?>" onChange="validateFileTypeCV('cv3')" > 
                  <?php if($centerResult[0]['cv3']!='')
                    {?>
                     <a href="<?php echo base_url()?>/uploads/iibfdra/agency_center/faculty_cv/<?php echo $centerResult[0]['cv3'];?>" target="_blank"><?php echo $centerResult[0]['cv3'];?></a>
                    <?php 
                     }?>
                  <input type="hidden" name="hiddencv3" id="hiddencv3" value="<?php echo $centerResult[0]['cv3'];?>"  ><div id="error_file1"></div>
                   <!--  <span class="photo_text" style="display:none;"></span>  -->
                    <span class="error"><?php echo form_error('cv3');?></span> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">4. Faculty Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name4" name="faculty_name4" placeholder="4. Faculty Name"  value="<?php echo $centerResult[0]['faculty_name4'];?>" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">4. Faculty Qualification</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_qualification4" name="faculty_qualification4" placeholder="4. Faculty Qualification"  value="<?php echo $centerResult[0]['faculty_qualification4'];?>" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="cv4" class="col-sm-4 control-label">4.CV (PDF only)</label>
                  <div class="col-sm-5" >
                  <input type="file" name="cv4" id="cv4" value="<?php echo $centerResult[0]['cv4'];?>" onChange="validateFileTypeCV('cv4')"  >  
                  <?php if($centerResult[0]['cv4']!='')
                    {?>
                     <a href="<?php echo base_url()?>/uploads/iibfdra/agency_center/faculty_cv/<?php echo $centerResult[0]['cv4'];?>" target="_blank"><?php echo $centerResult[0]['cv4'];?></a>
                    <?php 
                     }?>
                  <input type="hidden" name="hiddencv4" id="hiddencv4" value="<?php echo $centerResult[0]['cv4'];?>"  ><div id="error_file1"></div>
                   <!--  <span class="photo_text" style="display:none;"></span>  -->
                    <span class="error"><?php echo form_error('cv4');?></span> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">5. Faculty Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_name5" name="faculty_name5" placeholder="5. Faculty Name"  value="<?php echo $centerResult[0]['faculty_name5'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">5. Faculty Qualification</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="faculty_qualification5" name="faculty_qualification5" placeholder="5. Faculty Qualification"  value="<?php echo $centerResult[0]['faculty_qualification5'];?>" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="cv5" class="col-sm-4 control-label">5.CV (PDF only)</label>
                  <div class="col-sm-5" >
                  <input type="file" name="cv5" id="cv5" value="<?php echo $centerResult[0]['cv5'];?>" onChange="validateFileTypeCV('cv5')"  >  
                  <?php if($centerResult[0]['cv5']!='')
                    {?>
                     <a href="<?php echo base_url()?>/uploads/iibfdra/agency_center/faculty_cv/<?php echo $centerResult[0]['cv5'];?>" target="_blank"><?php echo $centerResult[0]['cv5'];?></a>
                    <?php 
                     }?>
                  <input type="hidden" name="hiddencv5" id="hiddencv5" value="<?php echo $centerResult[0]['cv5'];?>"  ><div id="error_file1"></div>
                   <!--  <span class="photo_text" style="display:none;"></span>  -->
                    <span class="error"><?php echo form_error('cv5');?></span> 
                  </div>
                </div>
                 <div class="form-group">
                  <label for="upload_file1" class="col-sm-4 control-label">Request Letter from Accredited Institute<span style="color:#F00">*</span></label>
                  <div class="col-sm-5" id="">
                  <input type="file" name="upload_file1" id="upload_file1" value="<?php echo $centerResult[0]['upload_file1'];?>" onChange="validateFileType('upload_file1')" >  <?php if($centerResult[0]['upload_file1']!='')
				   {?>
                     <a href="<?php echo base_url()?>/uploads/iibfdra/agency_center/<?php echo $centerResult[0]['upload_file1'];?>" target="_blank"><?php echo $centerResult[0]['upload_file1'];?></a>
				<?php 
				 }?>
                  <input type="hidden" name="upload_file1_hidden" id="upload_file1_hidden" value="<?php echo $centerResult[0]['upload_file1'];?>"  >
                 
                
                    <div id="error_file1"></div>
                   <!--  <span class="photo_text" style="display:none;"></span>  -->
                    <span class="error"><?php echo form_error('upload_file1');?></span> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="upload_file2" class="col-sm-4 control-label">Letter From Sponsoring Agency</label>
                  <div class="col-sm-5" id="">
                    <input  type="file" name="upload_file2" id="upload_file2" value="<?php echo $centerResult[0]['upload_file2'];?>" onChange="validateFileType('upload_file2')" accept=".jpg,.jpeg,.png,.pdf" > <?php if($centerResult[0]['upload_file2']!='')
				            {?>
                   <a href="<?php echo base_url()?>/uploads/iibfdra/agency_center/<?php echo $centerResult[0]['upload_file2'];?>" target="_blank"><?php echo $centerResult[0]['upload_file2'];?></a>
          				<?php 
          				 }?>
                    <input type="hidden" name="upload_file2_hidden" id="upload_file2_hidden" value="<?php echo $centerResult[0]['upload_file2'];?>"  >
                    <div id="error_file2"></div>
                   <!--  <span class="photo_text" style="display:none;"></span>  -->
                    <span class="error"><?php echo form_error('upload_file2');?></span> 
                  </div>
                </div>
              </div>
              <!-- divbox -->
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Due diligence </label>
                <div class="col-sm-3">
                  <input type="radio" class="minimal cls_gender due_diligence" id="due_diligence" name="due_diligence"  value="Yes" <?php if($centerResult[0]['due_diligence'] == "Yes"){ ?> checked="checked" <?php } ?>>
                  Yes
                  <input type="radio" class="minimal cls_gender due_diligence" id="due_diligence" name="due_diligence"  value="No" <?php if($centerResult[0]['due_diligence'] == "No"){ ?> checked="checked" <?php } ?>>
                  No  </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">GSTIN No</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="Enter GSTN No"  value="<?php echo $centerResult[0]['gstin_no'];?>"  data-parsley-maxlength="15" maxlength="15" data-parsley-pattern="\d{2}[A-Z]{5}\d{4}[A-Z]{1}\d[Z]{1}[A-Z\d]{1}">
                </div>
              </div>
              
               <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Remarks</label>
                      <div class="col-sm-6">
          				<textarea style="width:100%; text-align:left;" name="remarks" id="remarks" class="control-label" data-parsley-maxlength="500" maxlength="500" ><?php echo $centerResult[0]['remarks'];?></textarea>
                      </div>
               </div>
            </div>
          </div><!-- box info -->
          <!-- agency address section -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Agency Registered Office Address</h3>
                </div>
                  <div class="box-body">
                    <?php 
                       $loginAgency  = $this->session->userdata('dra_institute');
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
                      </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">State</label>
                        <div class="col-sm-5">   
                      <?php if(count($states) > 0){
                          foreach($states as $row1){
                          if($loginAgency['ste_code']== $row1['state_code']){
                            echo $row1['state_name'];
                          }  ?>
                      <?php } } ?> </div>
                      </div>
                    </div><!-- /col-md-6 -->
                    <div class="col-md-6">
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
                          }?>    
                          </div>

                    </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Pincode</label>
                        <div class="col-sm-5"> <?php echo $loginAgency['pin_code'];?> </div>
                      </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Phone Number</label>
                        <div class="col-sm-5"> <?php if($loginAgency['phone']!="")
                        {
                          echo $loginAgency['phone'];
                        }else
                        {
                          echo "-";
                        } ?> </div>
                      </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Mobile Number</label>
                        <div class="col-sm-5"> <?php echo $loginAgency['mobile'];?> </div>
                      </div>
                      <div class="form-group">
                        <label for="roleid" class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-5"> <?php echo $loginAgency['email'];?> </div>
                      </div>
                    </div><!-- col-md-6 -->
                  </div><!--.box-body-->
          </div><!--.box-info-->
          <div class="box box-info">
            <div class="box-header with-border"> </div>
              <div class="box-body">
               <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Tick to display <span style="color:#090;">'Agency'</span> address on the invoice<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="radio" class="chk1" id="invoice_flag"  name="invoice_flag"  value="AS" <?php if($centerResult[0]['invoice_flag'] == "AS"){ ?> checked="checked"<?php } ?> required>
                     </div>
                </div>
                <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Tick to display <span style="color:#090;">'Accreditation Centre'</span> address on the invoice<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="radio" class="chk2" id="invoice_flag"  name="invoice_flag"  value="CS" <?php if($centerResult[0]['invoice_flag'] == "CS"){ ?> checked="checked" <?php } ?> required>
                      </div>
               </div>
               
              <div class="box-footer">
                <div class="col-sm-6 col-sm-offset-3">
                  <div class="col-sm-12">
                    <center>
                   <!-- <button type="submit" class="btn btn-info"  name="preview" onclick="javascript:return dracentercheckform();" id="update">Update</button>-->
                   
                     <button type="submit" class="btn btn-info"  name="preview" onclick="javascript:return dracentercheckform();" id="update"> Renew </button>
                      <!--<button type="button" class="btn btn-info"  name="preview" id="btn_preview" style="display:none;" disabled>Preview and Proceed for Payment</button>-->
                      <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset">Reset</button>
                      <input type="hidden" name="btnSubmit" value="Save" id="btn_preview"/>
                      <input type="hidden" name="center_id" value="<?php  echo $centerResult[0]['center_id'];?>"/>
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
  
  $('input[name="inst_type"]').change(function () {
    var val = $('input[name=inst_type]:checked', '#frmDrA').val();
    if(val == 'R')
    {
      $('#due_diligence').prop('required', true);
    }
    
  });
  
  $('.due_diligence').click(function(event){
    var inst_type_val = $('input[name=inst_type]:checked', '#frmDrA').val();
    var due_diligence_val = $('input[name=due_diligence]:checked', '#frmDrA').val();
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
</script> 
<script type="text/javascript">
$(document).ready(function(){
	$(document).ready(function(){
    var center_type=$("input[type=radio][name='center_type']:checked").val()
	if(center_type=='T')
	{
		$(".divbox").not(center_type).show();
        $(center_type).hide();
	}
});

    $('input[name="center_type"]').click(function(){
	  var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
      $(".divbox").not(targetBox).hide();
        $(targetBox).show();
    });
	
	$('input[name="center_type"]').load(function(){
		alert('on  load');
	});
	
});

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
