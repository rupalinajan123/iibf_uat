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
        <h1> DRA examination application edit form </h1>
        <?php //echo $breadcrumb;?>
    </section>
    <form class="form-horizontal" name="draExamEditFrm" id="draExamEditFrm"  method="post"  enctype="multipart/form-data" onsubmit="return dravalidateForm()">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Details</h3>
                             <div class="pull-right">
                           
                            <a href="<?php echo base_url().$_SESSION['reffer'];?>" class="btn btn-warning">Back</a>
                            
                       </div>
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
                            

                        
                          
                             <?php //print_r($examRes); ?> 
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
                                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo ( set_value('addressline1') ) ? set_value('addressline1') : $examRes["address1"];?>" autocomplete="off" data-parsley-maxlength="50" maxlength="30">
                                  <span class="error"><?php //echo form_error('addressline1');?></span>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                                <label for="address_line2" class="col-sm-3 control-label">Address line2</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo ( set_value('addressline2') ) ? set_value('addressline2') : $examRes["address2"];?>"  autocomplete="off" data-parsley-maxlength="50" maxlength="30" >
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                            
                             <div class="form-group">
                                <label for="address_line2" class="col-sm-3 control-label">Address line3</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo ( set_value('addressline3') ) ? set_value('addressline3') : $examRes["address3"];?>"  autocomplete="off" data-parsley-maxlength="50" maxlength="30" >
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                             <div class="form-group">
                                <label for="address_line2" class="col-sm-3 control-label">Address line4</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo ( set_value('addressline4') ) ? set_value('addressline4') : $examRes["address4"];?>"  autocomplete="off" data-parsley-maxlength="50" maxlength="30" >
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="state" class="col-sm-3 control-label">State <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                <select class="form-control" id="ccstate" name="state" required >
                                    <option value="">Select</option>
                                    <?php if(count($states) > 0){
                                            foreach($states as $row1){  ?>
                                    <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code'], ( $examRes["state"] == $row1['state_code'])); ?>><?php echo $row1['state_name'];?></option>
                                    <?php } } ?>
                                  </select>
                                
                                <input hidden="statepincode" id="statepincode" value="">
                  
                                </div>
                               
                            </div>
                            
                            <div class="form-group">
                                <label for="district" class="col-sm-3 control-label">District <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo ( set_value('district') ) ? set_value('district') : $examRes["district"];?>"  autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                  <span class="error"><?php //echo form_error('district');?></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                 <label for="city" class="col-sm-3 control-label">City <span class="mandatory-field">*</span></label>
                                <div class="col-sm-3">
                                   <select class="form-control city" id="city" name="city" required onChange="check_city()" >
                                    <option value="<?php echo $examRes['city'];?>"><?php echo $examRes['city'];?></option>
                                   
                                  </select>
                                  <span class="error"><?php //echo form_error('city');?></span>
                                </div>
                                 <label for="pincode" class="col-sm-2 control-label">Pincode <span class="mandatory-field">*</span></label>
                                 <div class="col-sm-2">
                                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" required value="<?php echo ( set_value('pincode') ) ? set_value('pincode') : $examRes["pincode"];?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dracheckpin data-parsley-type="number" autocomplete="off" data-parsley-trigger-after-failure="focusout"> 
                                     <span class="error"><?php //echo form_error('pincode');?></span>
                                </div>
                             </div>
                             
                            <div class="form-group">
                                <label for="dob" class="col-sm-3 control-label">Date of Birth <span class="mandatory-field">*</span></label>
                                <div class="col-sm-2 example">
                                    <input type="hidden" id="dateofbirth" name="dob" value="<?php echo ( set_value('dob1') ) ? set_value('dob1') : $examRes["dateofbirth"];?>" required />
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
                                        <input type="text" class="form-control" id="stdcode"  name="stdcode" placeholder="STD Code"  value="<?php echo ( set_value('stdcode') ) ? set_value('stdcode') : $examRes["stdcode"];?>"  data-parsley-maxlength="5"  maxlength="5" data-parsley-minlength="4" data-parsley-trigger-after-failure="focusout"/>
                                        <span class="error"><?php //echo form_error('stdcode');?></span>
                                    </div> 
                                    <div class="col-sm-2">
                                        Phone No (Max 8 digits)
                                        <input type="text" class="form-control" id="phone"  name="phone" placeholder="Phone No"  data-parsley-type="number" data-parsley-maxlength="8" data-parsley-minlength="7" value="<?php echo ( set_value('phone') ) ? set_value('phone') : $examRes["phone"];?>" size="8" maxlength="8" data-parsley-trigger-after-failure="focusout"/>
                                        <span class="error"><?php //echo form_error('phone');?></span>
                                    </div> 
                                </div>
                                
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-3 control-label">Candidate Mobile No. <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" maxlength="10" value="<?php echo ( set_value('mobile') ) ? set_value('mobile') : $examRes["mobile"];?>"  required data-parsley-trigger-after-failure="focusout"  autocomplete="off" size="10">
                                        <span class="error"><?php //echo form_error('mobile');?></span>
                                    </div>
                                </div> 
                                
                                 <div class="form-group">
                                    <label for="roleid" class="col-sm-3 control-label">Email ID <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                      <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo ( set_value('email') ) ? set_value('email') : $examRes["email"];?>"  data-parsley-maxlength="45" required autocomplete="off"  data-parsley-trigger-after-failure="focusout">
                                      <span class="error"><?php //echo form_error('email');?></span>
                                    </div>
                                </div>  
                
                            </div><!--.box-body-->
                         </div><!--.box-info-->
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
                                        
                                    </div>
                                </div>
                           
                                <div class="form-group">
                                    <label for="center" class="col-sm-3 control-label">Centre Name </label>
                                    <div class="col-sm-5">
                                          <input type="text" class="form-control" placeholder="Center Name"  value="<?php echo set_value('center_code');?> <?php echo $examRes['city_name'] ;?>" autocomplete="off" readonly>
                                    
                                    </div>
                                </div>
                               
                                
                                <div class="form-group">
                                    <label for="center" class="col-sm-3 control-label">Batch Name </label>
                                    <div class="col-sm-5">
                                          <input type="text" class="form-control" placeholder="Center Code"  value="<?php echo set_value('center_code');?> <?php echo $examRes['batch_name'] ;?>" autocomplete="off" readonly>
                                    
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="training_period" class="col-sm-3 control-label">Training Period  </label>
                                    <div class="col-sm-2">
                                        From
                                        <input type="text" class="form-control" value="<?php echo set_value('training_from');?>  <?php echo $examRes['batch_from_date'] ;?>" autocomplete="off"  readonly="readonly"/>
                                        <span class="error"><?php //echo form_error('training_from');?></span>
                                    </div> 
                                    <div class="col-sm-2">
                                        To
                                        
                                        <input type="text" class="form-control"  placeholder="Training To Date"  value=" <?php echo $examRes['batch_to_date'] ;?><?php echo set_value('training_to');?>" autocomplete="off"  readonly="readonly"/>
                                        <span class="error"><?php //echo form_error('training_to');?></span>
                                      
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
                                  <!--  <li>Allowed Training Certificate image Size - 50 to 100 KB</li>-->
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
                                    <label for="id_proof" class="col-sm-3 control-label">Proof of Identity <span class="required-spn">*</span></label>
                                    <div class="col-sm-5" id="exist_draidproofphoto">
                                        <?php if($examRes["idproofphoto"] == ''){ ?>
                                            <input  type="file" name="draidproofphoto" id="draidproofphoto" autocomplete="off" required>
                                        <?php } ?>
                                        
                                        <input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto" value="<?=$examRes["idproofphoto"]?>">
                                        <div id="error_dob"></div>
                                        <br>
                                        <div id="error_dob_size"></div>
                                        <span class="dob_proof_text" style="display:none;"></span>
                                        <span class="error"><?php //echo form_error('idproofphoto');?></span>
                                    </div>
                                    <img id="idproof_preview" height="100" width="100" src="<?php echo base_url().'uploads/iibfdra/'.$examRes["idproofphoto"];?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label for="quali_certificate" class="col-sm-3 control-label">Qualification Certificate <span class="required-spn">*</span></label>
                                    <div class="col-sm-5" id="exist_qualicertificate">
                                        <?php if($examRes["quali_certificate"] == ''){ ?>
                                          <input  type="file" name="qualicertificate" id="qualicertificate" autocomplete="off" required> 
                                        <?php } ?>
                                       (As per educational qualification selected above)
                                         <input type="hidden" id="hiddenqualicertificate" name="hiddenqualicertificate" value="<?=$examRes["quali_certificate"]?>">
                                        <div id="error_qualicert"></div>
                                     <br>
                                     <div id="error_qualicert_size"></div>
                                     <span class="qualicert_text" style="display:none;"></span>
                                      <span class="error"><?php //echo form_error('qualicertificate');?></span>
                                    </div>
                                    <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["quali_certificate"];?>" id="qualicertificate_preview" height="100" width="100"/>
                                </div>
                                
                                <?php /*?><div class="form-group">
                                    <label for="training_certificate" class="col-sm-3 control-label">Training Certificate <span class="required-spn">*</span></label>
                                    <div class="col-sm-5" id="exist_trainingcertificate">
                                         <?php if($examRes["training_certificate"] == ''){ ?>
                                           <input  type="file" name="trainingcertificate" id="trainingcertificate" autocomplete="off" required>
                                        <?php } ?>
                                       
                                        <input type="hidden" id="hiddentrainingcertificate" name="hiddentrainingcertificate" value="<?=$examRes["training_certificate"]?>">
                                        <div id="error_trainingcert"></div>
                                        <br>
                                        <div id="error_trainingcert_size"></div>
                                        <span class="trainingcert_text" style="display:none;"></span>
                                        <span class="error"><?php //echo form_error('trainingcertificate');?></span>
                                    </div>
                                    <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["training_certificate"];?>" id="trcertificate_preview" height="100" width="100"/>
                                </div><?php */?>
                                
                                
                                
                                 <div class="form-group">
                                    <label for="photograph" class="col-sm-3 control-label">Photograph of the Candidate <span class="required-spn">*</span></label>
                                    <div class="col-sm-5" id="exist_drascannedphoto">
                                         <?php if($examRes["scannedphoto"] == ''){ ?>
                                           <input  type="file" name="drascannedphoto" id="drascannedphoto" autocomplete="off" required>
                                        <?php } ?>
                                       
                                         <input type="hidden" id="hiddenphoto" name="hiddenphoto" value="<?=$examRes["scannedphoto"]?>">
                                        <div id="error_photo"></div>
                                     <br>
                                     <div id="error_photo_size"></div>
                                     <span class="photo_text" style="display:none;"></span>
                                      <span class="error"><?php //echo form_error('drascannedphoto');?></span>
                                    </div>
                                    <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["scannedphoto"];?>"  id="scanphoto_preview" height="100" width="100"/>
                                </div>
                                
                                
                                 <div class="form-group">
                                <label for="roleid" class="col-sm-3 control-label"> Signature of the Candidate <span class="required-spn">*</span></label>
                                    <div class="col-sm-5" id="exist_drascannedsignature">
                                         <?php if($examRes["scannedsignaturephoto"] == ''){ ?>
                                           <input  type="file" name="drascannedsignature" id="drascannedsignature" autocomplete="off" required>
                                        <?php } ?>
                                       
                                        <input type="hidden" id="hiddenscansignature" name="hiddenscansignature" value="<?=$examRes["scannedsignaturephoto"]?>">
                                        <div id="error_signature"></div>
                                        <br>
                                        <div id="error_signature_size"></div>
                                        <span class="signature_text" style="display:none;"></span>
                                        <span class="error"><?php //echo form_error('drascannedsignature');?></span>
                                    </div>
                                    <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["scannedsignaturephoto"];?>" id="signature_preview" height="100" width="100"/>
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
                                <h3 class="box-title">  <input name="declaration1" value="1" type="checkbox" required 
                          <?php if(set_value('declaration1'))
                          {
                              echo set_radio('declaration1', '1');
                             }?>>&nbsp; I Accept Declaration</h3> 
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
                         
                         <!-- <div class="box box-info">
                             <div class="box-header with-border">
                                <h3 class="box-title">  <input name="declaration1" value="1" type="checkbox" required 
                          <?php if(set_value('declaration1'))
                          {
                             // echo set_radio('declaration1', '1');
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
                                         <div id="captcha_img"><?php //echo $image;?></div>
                                         <span class="error"><?php //echo form_error('code');?></span>
                                    </div>
                                    <div class="col-sm-2">
                                          <a href="javascript:void(0);" id="new_captcha" >Change Image</a>
                                         <span class="error"><?php //echo form_error('code');?></span>
                                    </div>
                                </div>
                            </div>
                         </div> --><!--.box-info-->
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
<script src="<?php echo base_url()?>js/validation_dra_batch.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.minimal').removeAttr('disabled');
    $('#draExamEditFrm').parsley('validate');
    //keep idproof and exam mode diabled which are not checked
   // $("input[name='idproof']:not(:checked)").attr('disabled', true);
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
            {   alert('Select valid date'); }
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
            url: site_url+'iibfdra/DraExam/generatecaptchaajax/',
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
     /* Get City From State in Agency tab */
    $('#ccstate').on('change',function(){
    var state_code = $(this).val();
    if(state_code){
      $.ajax({
        type:'POST',
        url: site_url+'iibfdra/TrainingBatches/getCity',
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

    $("body").on("contextmenu",function(e){
        return false;
    });
});
</script>