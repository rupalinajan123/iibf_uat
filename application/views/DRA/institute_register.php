<link href="<?php echo base_url();?>assets/css/wizard.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/js/wizard.js"></script>
<link href="<?php echo base_url();?>assets/css/center_add.css" rel="stylesheet">
<style>
.form-group ul li.parsley-required {
	color: #F00 !important;
	float: left;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<form class="form-horizontal demo-form" name="frmDrA" id="frmDrA" method="post" action="<?php echo base_url();?>DraRegister" enctype="multipart/form-data" data-parsley-validate="parsley">
  <div class="container">
    <section class="content-header">
      <h1 class="register"> Application for DRA Agency </h1>
      <br/>
    </section>
    <div class="stepwizard">
      <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-primary btn-circle" id="step_1"><i class="fa fa-university" aria-hidden="true"></i></a>
          <p class="mb-0">01</p>
          <span class="step_ttl">agency basic details</span> </div>
        <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" disabled="disabled" id="step_2"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
          <p class="mb-0">02</p>
          <span class="step_ttl">Accreditation Details (Centre Details) </span> </div>
        <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" disabled="disabled" id="step_3"><i class="fa fa-search" aria-hidden="true"></i></a>
          <p class="mb-0">03</p>
          <span class="step_ttl">review details</span> </div>

        <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" id="step_4"><i class="fa fa-search" aria-hidden="true"></i></a>
          <p class="mb-0">04</p>
          <span class="step_ttl">Payment details</span> </div>   

      </div>
    </div>
    <section class="content step_form">
      <div class="row">
        <div class="col-md-12"> 
          
          <!-- Validation error flash messages start -->
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
          <!-- End of validation msg--> 
          
          <!-- Basic Details box Start-->
          <div class="box box-info">
            <div class="box-body">
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              <!-- Agency Add Details Start -->
              <div class="form-group form-section">
              
              <div class="box-header with-border">
                  <h3 class="box-title" style="color:#333"><b>Agency Basic Details</b></h3>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Agency Type<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <select id="agency_type" name="agency_type" class="form-control" required>
                      <option value="">Select Agency Type</option>
                      <option value="BANK">Bank</option>
                      <option value="NON-BANK">Non-Bank</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name Of Agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <textarea id="inst_name" name="inst_name" class="form-control" data-parsley-maxlength="300" placeholder="Name Of Agency" required><?php echo set_value('inst_name');?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Year of establishment <span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="estb_year" name="estb_year" placeholder="Year of establishment"  value="<?php echo set_value('estb_year');?>" data-parsley-type="number" onkeypress="return (number(event));" data-parsley-pattern="/^[0-9/ ]+$/" maxlength="4" required>
                  </div>
                </div>
                <!--<div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">
                  <h4 class="title mb-0">Agency Address</h4>
                  </label>
                </div>-->
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line1<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="main_address1" name="main_address1" placeholder="Address line1" required value="<?php echo set_value('main_address1');?>"  data-parsley-maxlength="30" maxlength="30" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line2</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="main_address2" name="main_address2" placeholder="Address line2"  value="<?php echo set_value('main_address2');?>"  data-parsley-maxlength="30" maxlength="30" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line3</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="main_address3" name="main_address3" placeholder="Address line3"  value="<?php echo set_value('main_address3');?>"  data-parsley-maxlength="30" maxlength="30" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line4</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="main_address4" name="main_address4" placeholder="Address line4"  value="<?php echo set_value('main_address4');?>" data-parsley-maxlength="30" maxlength="30" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">District <span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="main_district" name="main_district" placeholder="District" required value="<?php echo set_value('main_district');?>" data-parsley-maxlength="30" maxlength="30" >
                  </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <select class="form-control" id="main_state" name="main_state" required >
                      <option value="">Select</option>
                      <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                      <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('main_state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                      <?php } } ?>
                    </select>
                    <input hidden="statepincode" id="statepincode" value="">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
                  <div class="col-sm-3">
                    <!--<input type="text" class="form-control" id="main_city" name="main_city" placeholder="City" required value="<?php //echo set_value('main_city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >-->
                    
                    <select class="form-control" id="main_city" name="main_city" required >
                      <option value="">Select</option>
                      <?php if(count($cities) > 0){
                                foreach($cities as $row1){ 	?>
                      <option value="<?php echo $row1['id'];?>" <?php echo  set_select('main_city', $row1['id']); ?>><?php echo $row1['city_name'];?></option>
                      <?php } } ?>
                    </select>
                    
                  </div>
                  <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="main_pincode" name="main_pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('main_pincode');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin_main_addr data-parsley-type="number" onkeypress="return (number(event));" data-parsley-trigger-after-failure="focusout" >
                    (Max 6 digits)</div>
                </div>
                
                
                
                <!--<div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Telephone No of the agency <span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inst_phone" name="inst_phone" placeholder="Telephone No of the Institute"  value="<?php echo set_value('inst_phone');?>" onkeypress="return (number(event));" data-parsley-type="number" data-parsley-minlength="10" maxlength="12" data-parsley-maxlength="12" data-parsley-trigger-after-failure="focusout" required>
                  </div>
                  
                </div>-->
                
                
                 <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Telephone No of the agency </label>
                <div class="col-sm-3">
                    STD Code (Max 5 digits)
                         <input type="text" class="form-control" id="inst_stdcode" name="inst_stdcode" placeholder="STD Code" value="<?php echo set_value('inst_stdcode');?>" data-parsley-type="number" data-parsley-maxlength="5" maxlength="5">    
                </div> 
                <div class="col-sm-3">
                    Phone No (Max 8 digits)
                        <input type="text" class="form-control" id="inst_phone" name="inst_phone" placeholder="Office Number" value="<?php echo set_value('inst_phone');?>" data-parsley-pattern="[0-9 _,]*" data-parsley-minlength="7" data-parsley-maxlength="8" data-parsley-check_office_no data-parsley-trigger-after-failure="focusout" maxlength="8">                   
                </div> 
			</div>
                
                
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Fax no of the agency </label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control"  id="inst_fax_no" name="inst_fax_no" placeholder="Fax no of the Institute"  value="<?php echo set_value('inst_fax_no');?>" onkeypress="return (number(event));" data-parsley-pattern="^[0-9 \-\s \( \)]*$" data-parsley-checkinst_fax_no data-parsley-trigger-after-failure="focusout" maxlength="13" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Website address </label>
                  <div class="col-sm-6">
                    <input type="url" class="form-control" id="inst_website" maxlength="150" name="inst_website" placeholder="Website address"  value="<?php echo set_value('inst_website');?>" 
                    //data-parsley-pattern="^(?:https?://)?(?:[a-z0-9-]+\.)*((?:[a-z0-9-]+\.)(co.in|org|com|in)+)" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name of director/head of the agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inst_head_name" maxlength="50" name="inst_head_name" placeholder="Name of director/head of the Institute " value="<?php echo set_value('inst_head_name');?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile no of head of the agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inst_head_contact_no" name="inst_head_contact_no" placeholder="Mobile no of Head of the institute"  value="<?php echo set_value('inst_head_contact_no');?>" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" onkeypress="return (number(event));" data-parsley-trigger-after-failure="focusout" maxlength="10" data-parsley-checkinst_mobile_no required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email id of the head of the agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inst_head_email" maxlength="80" name="inst_head_email" placeholder="Email id of the head of the institute"  data-parsley-type="email" value="<?php echo set_value('inst_head_email');?>"  data-parsley-maxlength="80" required  data-parsley-trigger-after-failure="focusout" data-parsley-checkinst_head_email  >
                  </div>
                </div>


                <!----------------------------------------- Alternate Contact  person Details  -------------------------------------->
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name of Alternate Contact Person of the agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inst_altr_person_name" maxlength="75" name="inst_altr_person_name" placeholder="Name of Alternate Contact Person" value="<?php echo set_value('inst_altr_person_name');?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile No. of the Alternate Contact Person of the agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inst_alter_contact_no" name="inst_alter_contact_no" placeholder="Mobile No. of the Alternate Contact Person"  value="<?php echo set_value('inst_alter_contact_no');?>" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" onkeypress="return (number(event));" data-parsley-trigger-after-failure="focusout" maxlength="10" data-parsley-checkinst_altrmobile_no required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email ID of the Alternate Contact Person of the agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inst_altr_email" maxlength="80" name="inst_altr_email" placeholder="Email ID of the Alternate Contact Person"  data-parsley-type="email" value="<?php echo set_value('inst_altr_email');?>"  data-parsley-maxlength="80" required data-parsley-trigger-after-failure="focusout" data-parsley-checkinst_altr_email  >
                  </div>
                </div>
              </div>
              <!-- Agency Add Details closed --> 
              
              <!-- Add Center Code Start -->
              <div class="form-section"> <!--form-group-->
                <div class="box-header with-border">
                  <h3 class="box-title" style="color:#333"><b>Accreditation Details</b></h3>
                </div>
                <div class="field_wrapper">
                  <div class=""> <!--form-group-->
                   <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <select class="form-control" id="state" name="state" required >
                          <option value="">Select</option>
                          <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                          <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                          <?php } } ?>
                        </select>
                        <input hidden="statepincode" id="statepincode" value="">
                      </div>
                    </div>
                    
                    <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Name of Location (City) <span style="color:#F00">*</span></label>
                      <div class="col-sm-3">
                       <!-- <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php //echo set_value('city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >-->
                      
                       <select class="form-control" id="city" name="city" required >
                          <option value="">Select</option>
                          <?php if(count($cities) > 0){
                                foreach($cities as $row1){ 	?>
                          <option value="<?php echo $row1['id'];?>" <?php echo  set_select('city', $row1['id']); ?>><?php echo $row1['city_name'];?></option>
                          <?php } } ?>
                        </select>
                      </div>
                      
                      <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode');?>"  onkeypress="return (number(event));" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpincode data-parsley-type="number" data-parsley-trigger-after-failure="focusout"  >
                        (Max 6 digits) </div>
                    </div>
                  
                  
                    <div class="form-group" style="display:none">
                      <label for="roleid" class="col-sm-4 control-label">Name of Location<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                      
                       <input type="hidden" class="form-control" id="location_name" name="location_name" placeholder="Name of Location"  value=""  >
                      
                        <!--<input type="text" class="form-control" id="location_name" name="location_name" placeholder="Name of Location"  value="" onkeypress="return(alpha(event));" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"  required>-->
                      </div>
                    </div>
                   <!-- <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">
                      <h4 class="title mb-0">Address of Center</h4>
                      </label>
                    </div>-->
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Address line1<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo set_value('addressline1');?>"  data-parsley-maxlength="30"  maxlength="30" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Address line2</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo set_value('addressline2');?>"  data-parsley-maxlength="30" maxlength="30" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Address line3</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo set_value('addressline3');?>"  data-parsley-maxlength="30" maxlength="30" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Address line4</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo set_value('addressline4');?>" data-parsley-maxlength="30" maxlength="30" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">District <span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district');?>" data-parsley-maxlength="30" maxlength="30" >
                      </div>
                    </div>
                   
                    
                    <!--<div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Office Number<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="office_no" name="office_no" placeholder="Office Number" value="<?php echo set_value('office_no');?>" onkeypress="return (number(event));" data-parsley-pattern="[0-9 _,]*" data-parsley-minlength="10" data-parsley-maxlength="12" maxlength="12"  data-parsley-check_office_no data-parsley-trigger-after-failure="focusout" required>
                      </div>
                    </div>-->
         <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Office Number</label>
                <div class="col-sm-3">
                    STD Code (Max 5 digits)
                     <input type="text" class="form-control" id="stdcode" name="stdcode" placeholder="STD Code" value="<?php echo set_value('stdcode');?>" data-parsley-type="number" data-parsley-minlength="4" data-parsley-maxlength="5" size="5" maxlength="5">    
                </div> 
                <div class="col-sm-3">
                    Phone No (Max 8 digits)
                    <input type="text" class="form-control" id="office_no" name="office_no" placeholder="Office Number" value="<?php echo set_value('office_no');?>" data-parsley-pattern="[0-9 _,]*" data-parsley-minlength="7" data-parsley-maxlength="12" data-parsley-check_office_no data-parsley-trigger-after-failure="focusout" maxlength="8">                   
                </div> 
			</div>
            
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Contact Person Name<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" placeholder="Contact Person Name"  value="<?php echo set_value('contact_person_name');?>" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="contact_person_mobile" name="contact_person_mobile" placeholder="Mobile Number" onkeypress="return (number(event));" value="<?php echo set_value('contact_person_mobile');?>" data-parsley-type="number" data-parsley-minlength="10" maxlength="10" data-parsley-maxlength="10" data-parsley-check_cpmobile data-parsley-trigger-after-failure="focusout" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Email id<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="email_id" name="email_id" placeholder="Email id"  data-parsley-type="email" value="<?php echo set_value('email_id');?>"  data-parsley-maxlength="80" maxlength="80" required  data-parsley-check_cpemail data-parsley-trigger-after-failure="focusout" >
                        (Enter valid and correct email ID to receive communication) </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Center Type <span style="color:#F00">*</span></label>
                      <div class="col-sm-3">
                        <input type="radio" class="minimal cls_gender due_diligence" id="Regular" name="center_type" required value="R" <?php echo set_radio('center_type','Regular'); ?> checked>
                        Regular </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Due diligence </label>
                      <div class="col-sm-3">
                        <input type="radio" class="minimal cls_gender due_diligence" id="due_diligence"   name="due_diligence"  value="Yes" <?php echo set_radio('due_diligence', 'Yes'); ?>>
                        Yes
                        <input type="radio" class="minimal cls_gender due_diligence" id="due_diligence"  name="due_diligence"  value="No" <?php echo set_radio('due_diligence', 'No'); ?> disabled="disabled">
                        No </div>
                    </div>
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">GSTNo</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="Enter GST No"  value="<?php echo set_value('gstin_no');?>"  data-parsley-maxlength="15" maxlength="15" data-parsley-pattern="\d{2}[A-Z]{5}\d{4}[A-Z]{1}\d[Z]{1}[A-Z\d]{1}">
                        <span for="roleid"><b>Note :</b>Mention GST No. if available, of concerned center only otherwise leave the field blank.</span>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Remarks</label>
                      <div class="col-sm-6">
          				<textarea style="width:100%" name="remarks" id="remarks" class="form-group" data-parsley-maxlength="500" maxlength="500" ><?php echo set_value('remarks');?></textarea>
                      </div>
                    </div>
                    
                    <!-- <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Select to display <span style="color:#090;">'Agency'</span> address on the invoice<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="radio" class="chk1" id="invoice_flag"  name="invoice_flag"  value="AS" <?php echo set_radio('invoice_flag', 'AS'); ?> required>
                      </div>
                    </div> -->
                    <!-- <div class="form-group">
                      <label for="roleid" class="col-sm-4 control-label">Select to display <span style="color:#090;">'Accreditation Centre'</span> address on the invoice<span style="color:#F00">*</span></label>
                      <div class="col-sm-6">
                        <input type="radio" class="chk2" id="invoice_flag"  name="invoice_flag"  value="CS" <?php echo set_radio('invoice_flag', 'CS'); ?> required>
                      </div>
                    </div> -->
                  </div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title" style="color:#333">
                      <input name="declaration1" value="1" type="checkbox" required>
                      &nbsp;<b>"I Confirm that all the Details Entered are Correct as per my Knowledge." </b></h3>
                  </div>
                  <div class="form-group m_t_15">
                    <label for="roleid" class="col-sm-3 control-label">Security Code <span style="color:#F00">*</span></label>
                    <div class="col-sm-2">
                      <input type="text" name="code" id="code" required class="form-control " >
                      <span class="error" id="captchaid" style="color:#B94A48;"></span> </div>
                    <div class="col-sm-3">
                      <div id="captcha_img"> <?php echo $image;?> </div>
                    </div>
                    <div class="col-sm-2"> <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a> </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-12 btns_wrap"> 
                      <!--<input type="submit" class="btn btn-default pull-right" name="Preview and Proceed for Payment" value="Preview and Proceed for Payment " onclick="javascript:return dracheckform();" id="preview"/>-->
                      
                      <div class="form-navigation bn_wrap step2_btns">
                        <button type="button" class="previous btn pull-left">Previous</button>
                      </div>
                      <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return dracheckform();" id="preview" name="preview">Preview and Proceed for Payment</a> -->
                      
                      <input type="button" class="btn btn-info" name="btnSubmit" value="Preview and Proceed for Payment" onclick="dracheckform();" id="preview"/>
                      
                      <!--<button type="button" class="btn btn-info"  name="preview" id="btn_preview" style="display:none;" disabled>Preview and Proceed for Payment</button>
                          <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset">Reset</button>--> 
                    </div>
                  </div>
                </div>
              </div>
              <!-- Add Center Code Close --> 
              <!-- Communication Address Details box Closed--> 
              
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="form-navigation bn_wrap step2_btns text-center"> 
      <!--<button type="button" class="previous btn pull-left">Previous</button>-->
      <button type="button" class="next btn">Next</button>
      <span class="clearfix"></span> 
    </div>
        <div class="modal fade" id="confirm"  role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <label style="color:#F00"> <strong>VERY IMPORTANT</strong></label>
            <br>
            <div class="clearfix"></div>
            <p> I confirm that all the details entered are correct as per my knowledge.</p>
          </div>
          <div class="modal-footer">
            <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Confirm" >
          </div>
        </div>
        <!-- /.modal-content --> 
      </div>
      <!-- /.modal-dialog --> 
    </div>

  </div>
</form>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/validation_dra_register.js?<?php echo time(); ?>"></script> 
<script>


/* Get City From State in Agency tab */
$('#main_state').on('change',function(){
var state_code = $(this).val();
if(state_code){
	$.ajax({
		type:'POST',
		url: site_url+'DraRegister/getCity',
		data:'state_code='+state_code,
		success:function(html){
			$('#main_city').show();
			$('#main_city').html(html);
		}
	});
}else{
	$('#main_city').html('<option value="">Select State First</option>');
}
});


/* Get City From State in Center tab */
$('#state').on('change',function(){
var state_code = $(this).val();
if(state_code){
	$.ajax({
		type:'POST',
		url: site_url+'DraRegister/getCity',
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



/*$(document).ready(function(){
	$('.chk1').click(function(){
		if($(this).prop("checked") == true){
			$( ".chk2" ).prop( "disabled", true );
		}
		else if($(this).prop("checked") == false){
			$( ".chk2" ).prop( "disabled", false );
		}
	});
});

$(document).ready(function(){
	$('.chk2').click(function(){
		if($(this).prop("checked") == true){
			$( ".chk1" ).prop( "disabled", true );
		}
		else if($(this).prop("checked") == false){
			$( ".chk1" ).prop( "disabled", false );
		}
	});
});*/


$('#new_captcha').click(function(event){ 
	event.preventDefault();
	$.ajax({
		type: 'POST',
		url: site_url+'DraRegister/generatecaptchaajax/',
		success: function(res)
		{	
			if(res!='')
			{$('#captcha_img').html(res);
			}
		}
	});
});



</script> 
<script>
$(function () {
  var $sections = $('.form-section');

  function navigateTo(index) {
    // Mark the current section with the class 'current'
    $sections
      .removeClass('current')
      .eq(index)
        .addClass('current');
    // Show only the navigation buttons that make sense for the current section:
    $('.form-navigation .previous').toggle(index > 0);
    var atTheEnd = index >= $sections.length - 1;
    $('.form-navigation .next').toggle(!atTheEnd);
    $('.form-navigation [type=submit]').toggle(atTheEnd);
	}

  function curIndex() {
    // Return the current index by looking at which section has the class 'current'
	return $sections.index($sections.filter('.current'));
	
  }
  
  function previous()
  {
	 	$('#step_2').removeClass('btn-primary');
		$('#step_1').addClass('btn-primary');
 }
 
    // Previous button is easy, just go back
  $('.form-navigation .previous').click(function() {
   previous()
	navigateTo(curIndex() - 1);
  });

  // Next button goes forward iff current block validates
  $('.form-navigation .next').click(function() {
    $('.demo-form').parsley().whenValidate({
      group: 'block-' + curIndex()
    }).done(function() {
		$('#step_1').removeClass('btn-primary').addClass('btn-default');
		$('#step_2').addClass('btn-primary');
      navigateTo(curIndex() + 1);
    });
  });

  // Prepare sections by setting the `data-parsley-group` attribute to 'block-0', 'block-1', etc.
  $sections.each(function(index, section) {
    $(section).find(':input').attr('data-parsley-group', 'block-' + index);
  });
  navigateTo(0); // Start at the beginning
});

/* accepts charecters,white space and dot*/

/* comment by Manoj
$('#inst_head_name').keyup(function()
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

 $("#inst_head_contact_no").keypress(function(event){
     var x = $(this).val();
  
   if(x.indexOf('0')==0){
      //alert('First number not be 0');
      $('#inst_head_contact_no').val('');
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
<script>
/*window.location.hash="";
window.location.hash="";//again because google chrome don't insert first hash into history
window.onhashchange=function(){window.location.hash="";}*/
</script>