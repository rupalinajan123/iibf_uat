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
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1> DRA examination application form</h1>
        <?php //echo $breadcrumb;?>
    </section>
	<form class="form-horizontal" autocomplete="off" name="draExamEditFrm" id="draExamEditFrm"  method="post"  enctype="multipart/form-data" onsubmit="return dravalidateForm()">
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
                            	<label for="roleid" class="col-sm-3 control-label">Registration no</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="Registration no"  value="<?php echo set_value('reg_no');?>" readonly="readonly" />
                                    <input type="hidden" autocomplete="false" name="memtype" value="" id="memtype" />
                                    <input type="hidden" autocomplete="false" name="membertype" value="normal_member" id="membertype" />
           							<input type="hidden" autocomplete="false" name="dmemexam_id" value="<?php echo $dramemexam_id;?>" />                         
                                    <span class="error"><?php //echo form_error('reg_no');?></span>
                                </div>(Only for re-exam)
                            </div>
                            
                            <div class="form-group">
                                <label for="roleid" class="col-sm-3 control-label">Medium of Examination <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="exam_medium" name="exam_medium" required >
                                        <option value="">Select</option>
                                        <?php if(count($medium_master) > 0){
                                                foreach($medium_master as $medium){ 	?>
                                                    <option value="<?php echo $medium['medium_code'];?>" <?php echo  set_select('exam_medium', $medium['medium_code'], ($examRes["exam_medium"] == $medium['medium_code']) ); ?>><?php echo $medium['medium_description'];?></option>
                                    <?php } } ?>
                                    </select>
                            	</div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="first_name" class="col-sm-3 control-label">First Name <span class="mandatory-field">*</span></label>
                                <div class="col-sm-2">
                                    <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                                    <option value="" <?php echo  set_select('sel_namesub', '', ($examRes["namesub"] == '') ); ?>>Select</option>
                                    <option value="Mr." <?php echo  set_select('sel_namesub', 'Mr.', ($examRes["namesub"] == 'Mr.') ); ?>>Mr.</option>
                                    <option value="Mrs." <?php echo  set_select('sel_namesub', 'Mrs.', ($examRes["namesub"] == 'Mrs.' ) ); ?>>Mrs.</option>
                                    <option value="Ms." <?php echo  set_select('sel_namesub', 'Ms.', ($examRes["namesub"]) == 'Ms.' ); ?>>Ms.</option>
                                    <option value="Dr." <?php echo  set_select('sel_namesub', 'Dr.', ($examRes["namesub"]) == 'Dr.' ); ?>>Dr.</option>
                                    <option value="Prof." <?php echo  set_select('sel_namesub', 'Prof.', ($examRes["namesub"]) == 'Prof.' ); ?>>Prof.</option>
                                    
                                   </select>
                                </div>
                                
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo ( set_value('firstname') ) ? set_value('firstname') : $examRes["firstname"];?>" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-maxlength="30" autocomplete="off" maxlength="30">
                                     <span class="error"><?php //echo form_error('firstname');?></span>
                                </div>(Max 30 Characters) 
                         	</div>
                            
                            <div class="form-group">
                            	<label for="middle_name" class="col-sm-3 control-label">Middle Name</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo ( set_value('middlename') ) ? set_value('middlename') : $examRes["middlename"];?>" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-maxlength="30" autocomplete="off" maxlength="30">
                                    <span class="error"><?php //echo form_error('middlename');?></span>
                                </div>(Max 30 Characters) 
                            </div>
                            
                            <div class="form-group">
                            	<label for="last_name" class="col-sm-3 control-label">Last Name</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo ( set_value('lastname') ) ? set_value('lastname') : $examRes["lastname"];?>" data-parsley-pattern="/^[a-zA-Z-. ]+$/" data-parsley-maxlength="30" autocomplete="off" maxlength="30">
                                  <span class="error"><?php //echo form_error('lastname');?></span>
                                </div>(Max 30 Characters) 
                            </div>
                            
                            <div class="form-group">
                            	<label for="address_heading" class="col-sm-3 control-label">Candidate Address for communication</label>
                            </div>
                            
                            <div class="form-group">
                            	<label for="address_line1" class="col-sm-3 control-label">Address line1 <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo ( set_value('addressline1') ) ? set_value('addressline1') : $examRes["address1"];?>"  autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                  <span class="error"><?php //echo form_error('addressline1');?></span>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                            	<label for="address_line2" class="col-sm-3 control-label">Address line2</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo ( set_value('addressline2') ) ? set_value('addressline2') : $examRes["address2"];?>"  autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="city" class="col-sm-3 control-label">City <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo ( set_value('city') ) ? set_value('city') : $examRes["city"];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/"  autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                  <span class="error"><?php //echo form_error('city');?></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="district" class="col-sm-3 control-label">District <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo ( set_value('district') ) ? set_value('district') : $examRes["district"];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                  <span class="error"><?php //echo form_error('district');?></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="state" class="col-sm-3 control-label">State <span class="mandatory-field">*</span></label>
                                <div class="col-sm-2">
                                <select class="form-control" id="state" name="state" required >
                                    <option value="">Select</option>
                                    <?php if(count($states) > 0){
                                            foreach($states as $row1){ 	?>
                                    <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code'], ( $examRes["state"] == $row1['state_code'])); ?>><?php echo $row1['state_name'];?></option>
                                    <?php } } ?>
                                  </select>
                                
                                <input hidden="statepincode" id="statepincode" value="">
                  
                                </div>(Max 6 digits) 
                                 <label for="pincode" class="col-sm-2 control-label">Pincode <span class="mandatory-field">*</span></label>
                                 <div class="col-sm-2">
                                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" required value="<?php echo ( set_value('pincode') ) ? set_value('pincode') : $examRes["pincode"];?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dracheckpin data-parsley-type="number" autocomplete="off"> 
                                     <span class="error"><?php //echo form_error('pincode');?></span>
                                </div>
                             </div>
                             
                            <div class="form-group">
                            	<label for="dob" class="col-sm-3 control-label">Date of Birth <span class="mandatory-field">*</span></label>
                                <div class="col-sm-2 example">
            						<input type="hidden" autocomplete="false" id="dateofbirth" name="dob" value="<?php echo ( set_value('dob1') ) ? set_value('dob1') : $examRes["dateofbirth"];?>" required />
									<span id="dob_error" class="error"></span>
                                </div>
                            </div>
                             
                             
                             <div class="form-group">
                            	<label for="gender" class="col-sm-3 control-label">Sex (M/F) <span class="mandatory-field">*</span></label>
                                <div class="col-sm-2">
                                	<input type="radio" class="minimal" id="female"  checked="checked" name="gender"  required value="female" <?php if(set_value('gender')){echo set_radio('gender', 'female');}else{echo set_radio('gender', 'female', ($examRes["gender"] == 'female'));}?>>
                                 Female
                               		<input type="radio" class="minimal" id="male"   name="gender"  required value="male" <?php if(set_value('gender')){echo set_radio('gender', 'male');}else{echo set_radio('gender', 'male', ($examRes["gender"] == 'male'));}?>>
                                 Male
                                  	<span class="error"><?php //echo form_error('gender');?></span>
                                </div>
                            </div>
                         </div><!--.box-body-->
                         </div><!--.box-info-->
                         <div class="box box-info">
                            <div class="box-header with-border">
                          		<h3 class="box-title">Contact Details</h3>
                        	</div>
                            <div class="box-body">
                            	<div class="form-group">
                                	<label for="phone" class="col-sm-3 control-label">Phone </label>
                                    <div class="col-sm-2">
                                    	STD Code (Max 5 digits)
                                    	<input type="text" class="form-control" id="stdcode"  name="stdcode" placeholder="STD Code"  value="<?php echo ( set_value('stdcode') ) ? set_value('stdcode') : $examRes["stdcode"];?>" data-parsley-maxlength="5" autocomplete="off" size="5" maxlength="5" />
                                    	<span class="error"><?php //echo form_error('stdcode');?></span>
                                    </div> 
                                    <div class="col-sm-2">
                                    	Phone No (Max 8 digits)
                                    	<input type="text" class="form-control" id="phone"  name="phone" placeholder="Phone No"  data-parsley-type="number" data-parsley-maxlength="8" value="<?php echo ( set_value('phone') ) ? set_value('phone') : $examRes["phone"];?>" autocomplete="off" size="8" maxlength="8" />
                                    	<span class="error"><?php //echo form_error('phone');?></span>
                                    </div> 
                                </div>
                                
                                <div class="form-group">
                                	<label for="roleid" class="col-sm-3 control-label">Candidate Mobile No. <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                    	<input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" maxlength="10" value="<?php echo ( set_value('mobile') ) ? set_value('mobile') : $examRes["mobile"];?>"  required  autocomplete="off" size="10">
                                    	<span class="error"><?php //echo form_error('mobile');?></span>
                                    </div>
                                </div> 
                                
                                 <div class="form-group">
                                	<label for="roleid" class="col-sm-3 control-label">Email ID <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                      <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo ( set_value('email') ) ? set_value('email') : $examRes["email"];?>"  data-parsley-maxlength="45" required autocomplete="off" readonly="readonly">
                                      <span class="error"><?php //echo form_error('email');?></span>
                                    </div>
                                </div>  
                
                            </div><!--.box-body-->
                         </div><!--.box-info-->
            			 <div class="box box-info">
                            <div class="box-header with-border">
                          		<h3 class="box-title">Institute And Center Details</h3>
                        	</div>
                            <div class="box-body">
                            
                            	<div class="form-group">
                                	<label for="center" class="col-sm-3 control-label">Centre Name <span class="mandatory-field">*</span></label>
                                	<div class="col-sm-5">
                                    	<select class="form-control" id="exam_center" name="exam_center" required >
                                        	<option value="">Select</option>
                                        	<?php if(count($center_master) > 0){
                                                foreach($center_master as $center){ 	?>
                                                    <option value="<?php echo $center['center_code'];?>" <?php echo  set_select('exam_center', $center['center_code'], ($examRes["exam_center_code"] == $center['center_code'])); ?>><?php echo $center['center_name'];?></option>
                                    <?php } } ?>
                                    	</select>
                                        <input type="hidden" autocomplete="false" class="form-control" id="center_code" name="center_code" placeholder="Center Code"  value="<?php echo ( set_value('exam_center_code') ) ? set_value('exam_center_code') : $examRes["exam_center_code"];?>"  required  autocomplete="off" readonly="readonly">
                            		</div>
                            	</div>
                                                           
                                <div class="form-group">
                                    <label for="exam_mode" class="col-sm-3 control-label">Mode of Exam <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2 exam-mode-wrap">
                                        <input type="radio" class="minimal" id="ON"  checked="checked" name="exam_mode"  required value="ON" <?php if(set_value('exam_mode')){echo set_radio('exam_mode', 'ON');}else{echo set_radio('exam_mode', 'ON', ($examRes["exam_mode"] == 'ON'));}?>>
                                     	Online
                                        <input type="radio" class="minimal" id="OF"   name="exam_mode"  required value="OF" <?php if(set_value('exam_mode')){echo set_radio('exam_mode', 'OF');}else{echo set_radio('exam_mode', 'OF', ($examRes["exam_mode"] == 'OF'));}?>>
                                     	Offline
                                        <span class="error"><?php //echo form_error('exam_mode');?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                	<?php
									$institute_name = '';
                                    $drainstdata = $this->session->userdata('dra_institute');
									if( $drainstdata ) {
										$institute_name = $drainstdata['institute_name'];	
										$institute_code = $drainstdata['institute_code'];
									}
									?>
                                	<label for="inst_name" class="col-sm-3 control-label">Name Of Training Institute <span class="mandatory-field">*</span></label>
                                	<div class="col-sm-5">
                                    	<input type="text" class="form-control" id="inst_name" name="inst_name" placeholder="Name Of Training Institute"  value="<?php echo $institute_name;?>"  required  autocomplete="off"  readonly="readonly">
                                        <input type="hidden" autocomplete="false" class="form-control" id="institute_code" name="institute_code" placeholder="Institute Code"  value="<?php echo $institute_code;?>"  autocomplete="off"  readonly="readonly">
                            		</div>
                            	</div>
                                
                                <div class="form-group">
                                	<label for="training_period" class="col-sm-3 control-label">Training Period  <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                    	From
                                    	<input type="text" class="form-control" id="training_from"  name="training_from" placeholder="Training From Date" required value="<?php echo ( set_value('training_from') ) ? set_value('training_from') : $examRes["training_from"];?>" autocomplete="off" data-date-minDate="<?php echo date('m/d/Y'); ?>" data-parsley-mindate="<?php echo date('m/d/Y');?>" />
                                    	<span class="error"><?php //echo form_error('training_from');?></span>
                                    </div> 
                                    <div class="col-sm-2">
                                    	To
                                        <?php
                                        $sendstring = $examdt."*".$traininglimit;
										?>
                                    	<input type="text" class="form-control" id="training_to"  name="training_to" placeholder="Training To Date" required value="<?php echo ( set_value('training_to') ) ? set_value('training_to') : $examRes["training_to"];?>" autocomplete="off" data-date-minDate="<?php echo date('m/d/Y'); ?>" data-parsley-trainingtoval="<?php echo $sendstring;?>" />
                                    	<span class="error"><?php //echo form_error('training_to');?></span>
                                        <input type="hidden" autocomplete="false" name="examdate" value="<?php echo $examdt;?>" />
                                        <input type="hidden" autocomplete="false" name="traininglimit" value="<?php echo $traininglimit;?>" />
                                    </div> 
                                </div>
                                
                            </div><!--.box-body-->
                         </div><!--.box-info-->
                         
                         <div class="box box-info">
                            <div class="box-header with-border">
                          		<h3 class="box-title">Educational Qualification</h3>
                        	</div>
                            <div class="box-body">
                            
                            	<div class="form-group">
                                    <label for="edu_qualification" class="col-sm-3 control-label">10th <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                    	<input type="radio" id="tenth" name="edu_quali" required value="tenth" <?php if(set_value('edu_quali')){echo set_radio('edu_quali', 'tenth');}else{echo set_radio('edu_quali', 'tenth', ($examRes["qualification"] == 'tenth'));}?>>
                                     	<span class="error"><?php //echo form_error('edu_quali');?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edu_qualification" class="col-sm-3 control-label">12th <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                        <input type="radio" id="twelth" name="edu_quali" required value="twelth" <?php if(set_value('edu_quali')){echo set_radio('edu_quali', 'twelth');}else{echo set_radio('edu_quali', 'twelth', ($examRes["qualification"] == 'twelth'));}?>>
                                     	<span class="error"><?php //echo form_error('edu_quali');?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edu_qualification" class="col-sm-3 control-label">Graduation <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                    	<input type="radio" id="graduate" name="edu_quali" required value="graduate" <?php if(set_value('edu_quali')){echo set_radio('edu_quali', 'graduate');}else{echo set_radio('edu_quali', 'graduate', ($examRes["qualification"] == 'graduate'));}?>>
                                     	<span class="error"><?php //echo form_error('edu_quali');?></span>
                                    </div>
                                </div>
                                
                            </div><!--.box-body-->
                         </div><!--.box-info-->
                         
                         <div class="box box-info">
                            <div class="box-header with-border">
                          		<h3 class="box-title">Photograph and Signature</h3>
                        	</div>
                            <div class="box-body">
                            	
                            	<h4>Note</h4>
                                <ol>
                                	<li>Allowed Proof of Identity image Size - 10 to 25 KB</li>
                                    <li>Allowed Degree Certificate image Size - 50 to 100 KB</li>
                                    <li>Allowed Training Certificate image Size - 50 to 100 KB</li>
                                    <li>Allowed Photo Size - 10 to 20 KB</li>
                                    <li>Allowed Signature Size - 10 to 20 KB</li>
                                </ol>
                                
                                <div class="form-group idproof-wrap">
                                	<label for="id_proof" class="col-sm-3 control-label">Select Id Proof <span class="mandatory-field">*</span></label>                                    <div class="col-sm-5">
										<?php if(count($idtype_master) > 0)
                                        {
                                            $i=1;
                                            foreach($idtype_master as $idrow)
                                            {?>
                                               <input name="idproof" value="<?php echo $idrow['id'];?>" type="radio" class="minimal" 
                                               <?php if(set_value('idproof')){echo set_radio('idproof', $idrow['id']);}else{echo set_radio('idproof', $idrow['id'], ($examRes["idproof"] == $idrow['id']));}?>><?php echo $idrow['name'];?><br>
                                           <?php 
                                           $i++;}
                                           }?>
                                          <span class="error"><?php //echo form_error('idproof');?></span>
                                	</div>
                                </div>
                                
                                <div class="form-group">
                                	<label for="roleid" class="col-sm-3 control-label">Aadhar Card No.</label>
                                    <div class="col-sm-3">
                                      <input type="text" class="form-control" id="aadhar_no" name="aadhar_no" placeholder="Aadhar card No." data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" value="<?php echo ( set_value('aadhar_no') ) ? set_value('aadhar_no') : $examRes["aadhar_no"];?>" data-parsley-trigger-after-failure="focusout">
                                      <span class="error"><?php //echo form_error('aadhar_no');?></span>
                                    </div>
                                </div>
                                
                            </div><!--.box-body-->
						</div><!--.box-info-->
                        <div class="box box-info">
                            <div class="box-header with-border">
                          		<h3 class="box-title">Image Uploaded</h3>
                        	</div>
                            <div class="box-body">       
                            	 <div class="form-group idproof-wrap">
                                	 <div class="col-sm-3">
										 <?php if( !empty( $examRes["scannedphoto"] ) ) { ?>
                                         	<img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["scannedphoto"];?>" style="width: 100%;height: auto;"/>
                                         <?php } ?>
                                     </div> 
                                     <div class="col-sm-3">
                                     	<?php if( !empty( $examRes["scannedsignaturephoto"] ) ) { ?>
                                         	<img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["scannedsignaturephoto"];?>" style="width: 100%;height: auto;" />
                                         <?php } ?>
                                     </div>
                                     <div class="col-sm-2">
                                     	<?php if( !empty( $examRes["idproofphoto"] ) ) { ?>
                                         	<img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["idproofphoto"];?>" style="width: 100%;height: auto;" />
                                         <?php } ?>
                                     </div>
                                     <div class="col-sm-2">
                                     	<?php if( !empty( $examRes["training_certificate"] ) ) { ?>
                                         	<img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["training_certificate"];?>" style="width: 100%;height: auto;" />
                                         <?php } ?>
                                     </div>
                                     <div class="col-sm-2">
                                     	<?php if( !empty( $examRes["quali_certificate"] ) ) { ?>
                                         	<img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["quali_certificate"];?>" style="width: 100%;height: auto;" />
                                         <?php } ?>
                                     </div>
                                 </div>
                        	</div><!--.box-body-->
						</div><!--.box-info-->
                                                 
                        <div class="box box-info">
                            <div class="box-header with-border">
                          		<h3 class="box-title">Declaration</h3>
                        	</div>
                            <div class="box-body">
                            	<div class="form-group">
                                	<div class="col-sm-12">
                                    <p>
                                    I Wish to enroll as a candidate for the above mentioned examination. I confirm having read Rules and Regulations and other instructions governing the above examination of the institute. I hereby agree to abide by all the said Rules and Regulations and other instructions of the institute. I declare that i have not been debarred/disqualified from appearing at the institute's examination/s at the time of submitting this application. I further declare that in case i am desirous of instituting any legal proceedings against the institute, I hereby agree that such legal proceedings shall be instituted only in Courts at New Delhi, Kolkata, Mumbai & Chennai as the case may be, in whose jurisdiction the application is submitted by me and not in any other Court.
                                    </p>
                                    </div>
                            	</div>
                            </div><!--.box-body-->
                         </div><!--.box-info-->
                         
                         <div class="box box-info">
                             <div class="box-header with-border">
                          		<h3 class="box-title">  <input name="declaration1" value="1" type="checkbox" required="required" 
                          <?php if(set_value('declaration1'))
                          {
                              echo set_radio('declaration1', '1');
                             }?>>&nbsp; I Accept</h3>
                       		 </div>
                       		<div class="box-body">
                                <div class="form-group">
                                	<label for="roleid" class="col-sm-3 control-label">Security Code <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                      <input type="text" name="code" id="code" required class="form-control" >
                                         <span class="error" id="captchaid" style="color:#B94A48;"></span>
                                    </div>
                                     <div class="col-sm-2">
                                         <div id="captcha_img"><?php echo $image;?></div>
                                         <span class="error"><?php //echo form_error('code');?></span>
                                    </div>
                                    <div class="col-sm-2">
                                          <a href="javascript:void(0);" id="new_captcha" >Change Image</a>
                                         <span class="error"><?php //echo form_error('code');?></span>
                                    </div>
                                </div>
             				</div><!--.box-body-->
                         </div><!--.box-info-->
              <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                     <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">  
                    </div>
              </div>
           </div>
        </div>
      </div>
     
      
      
    </section>
    </form>
  </div>
  
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url()?>js/validation_dra.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#draExamEditFrm').parsley('validate');
	//keep idproof and exam mode diabled which are not checked
	$("input[name='idproof']:not(:checked)").attr('disabled', true);
	$("input[name='exam_mode']:not(:checked)").attr('disabled', true);
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
	// change gender - 
	$("input[name='gender']:not(:checked)").attr('disabled', true);
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
	//change captcha
	$('#new_captcha').click(function(event){
        event.preventDefault();
		var sdata = {'captchaname':'draexamedtcaptcha'};
		$.ajax({
			type: 'POST',
			data: sdata,
			url: site_url+'iibfdra/Version_2/DraExam/generatecaptchaajax/',
			success: function(res)
			{	
				if(res!='')
				{$('#captcha_img').html(res);
				}
			}
		});
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
	$("body").on("contextmenu",function(e){
        return false;
    });
});
</script>