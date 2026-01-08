<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>

<style>
  #listitems_logs_new th { border: 1px solid #ccc !important;  text-align: center; background: #eee; }
  #listitems_logs_new td { border: 1px solid #ccc !important;   }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Training Batch Details </h1>
    <?php echo $breadcrumb;	  
	 //print_r($result);	

	$drauserdata = $this->session->userdata('dra_admin');
	 ?> </section>
  <div class="col-md-12"> <br />
  </div>
  <!-- Main content -->
   <form class="form-horizontal" name="draExamEditFrm" id="draExamEditFrm"  method="post"  enctype="multipart/form-data" onsubmit="return dravalidateForm()">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Details</h3>
                             <div class="pull-right">
                             <?php 
                            $back_url = base_url('iibfdra/batch/candidates_list/');

                            if(isset($url_batch_id) && $url_batch_id > 0)
                            {
                              $back_url = base_url('iibfdra/batch/candidates_list/'.base64_encode($url_batch_id));
                            } ?>
                            <a href="<?php echo $back_url;?>" class="btn btn-warning">Back</a>
                            
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
                                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $examRes["address1"];?>" autocomplete="off" data-parsley-maxlength="50" maxlength="30">
                                  <span class="error"><?php //echo form_error('addressline1');?></span>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                                <label for="address_line2" class="col-sm-3 control-label">Address line2</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $examRes["address2"];?>"  autocomplete="off" data-parsley-maxlength="50" maxlength="30" >
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                            
                             <div class="form-group">
                                <label for="address_line2" class="col-sm-3 control-label">Address line3</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $examRes["address3"];?>"  autocomplete="off" data-parsley-maxlength="50" maxlength="30" >
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                             <div class="form-group">
                                <label for="address_line2" class="col-sm-3 control-label">Address line4</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo$examRes["address4"];?>"  autocomplete="off" data-parsley-maxlength="50" maxlength="30" >
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                            <?php //echo $examRes["state"];?>
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
                                    <option value="">Select City</option>
                                    <?php
                                    foreach ($cities_edit as $key => $city_edit) { ?>
                                      <option value="<?php echo $city_edit['city_name'] ?>" <?php if($city_edit['city_name']==$examRes['city']){echo "selected";} ?> ><?php echo $city_edit['city_name'];?></option>
                                    <?php } ?>  

                                    <?php /*
                                    <option value="<?php echo $examRes['city'];?>">
                                      <?php echo $examRes['city'];?>
                                      </option>
                                   */ ?>
                                  </select>
                                  <span class="error"><?php //echo form_error('city');?></span>
                                </div>
                                 <label for="pincode" class="col-sm-2 control-label">Pincode <span class="mandatory-field">*</span></label>
                                 <div class="col-sm-2">
                                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" required value="<?php echo ( set_value('pincode') ) ? set_value('pincode') : $examRes["pincode"];?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dracheckpin data-parsley-type="number" autocomplete="off" data-parsley-trigger-after-failure="focusout"> 
                                     <span class="error"><?php //echo form_error('pincode');?></span>
                                </div>
                             </div>
                             
                            <!--<div class="form-group">-->
                            <!--    <label for="dob" class="col-sm-3 control-label">Date of Birth <span class="mandatory-field">*</span></label>-->
                            <!--    <div class="col-sm-2 example">-->
                            <!--        <input type="hidden" id="dateofbirth" name="dob" value="<?php echo ( set_value('dob1') ) ? set_value('dob1') : $examRes["dateofbirth"];?>"  />-->
                            <!--        <span id="dob_error" class="error"></span>-->
                            <!--    </div>-->
                            <!--</div>-->
                             
                             
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
                                        <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" maxlength="10" value="<?php echo ( set_value('mobile_no') ) ? set_value('mobile_no') : $examRes["mobile_no"];?>"  required data-parsley-trigger-after-failure="focusout"  autocomplete="off" size="10">
                                        <span class="error"><?php //echo form_error('mobile');?></span>
                                    </div>
                                </div> 
                                
                                 <div class="form-group">
                                    <label for="roleid" class="col-sm-3 control-label">Email ID <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                      <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo ( set_value('email_id') ) ? set_value('email_id') : $examRes["email_id"];?>"  data-parsley-maxlength="45" required autocomplete="off"  data-parsley-trigger-after-failure="focusout">
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
                                    /*$drainstdata = $this->session->userdata('dra_institute');
                                    if( $drainstdata ) {
                                        $institute_name = $drainstdata['institute_name'];   
                                        $institute_code = $drainstdata['institute_code'];
                                    }*/
                                    $institute_name = '';
                                    $institute = $this->master_model->getRecords('dra_accerdited_master',array('institute_code'=>$examRes['inst_code']));
                                    ?>
                                    <label for="inst_name" class="col-sm-3 control-label">Name Of Training Institute </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control"  placeholder="Name Of Training Institute"  value="<?php echo $institute[0]['institute_name'];?>"   autocomplete="off"  readonly="readonly">
                                        
                                    </div>
                                </div>
                           
                                <div class="form-group">
                                    <label for="center" class="col-sm-3 control-label">Centre Name </label>
                                    <div class="col-sm-5">
                                          <input type="text" class="form-control" placeholder="Center Name"  value="<?php echo set_value('center_code');?> <?php echo $examRes['city_name'] ;?>" autocomplete="off" readonly>
                                    
                                    </div>
                                </div>
                               
                                <?php /*?>
                                <div class="form-group">
                                    <label for="center" class="col-sm-3 control-label">Batch Name </label>
                                    <div class="col-sm-5">
                                          <input type="text" class="form-control" placeholder="Center Code"  value="<?php echo set_value('center_code');?> <?php echo $examRes['batch_name'] ;?>" autocomplete="off" readonly>
                                    
                                    </div>
                                </div>
                                <?php */?>
                                
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
                                        <input type="radio" id="tenth" name="edu_quali" required value="tenth" <?php if(isset($examRes['qualification']) && $examRes['qualification'] == 'tenth') { echo 'checked="checked"'; }?>>
                                        <span class="error"><?php //echo form_error('edu_quali');?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edu_qualification" class="col-sm-3 control-label">12th <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                        <input type="radio" id="twelth" name="edu_quali" required value="twelth" <?php if(isset($examRes['qualification']) && $examRes['qualification'] == 'twelth') { echo 'checked="checked"'; }?>>
                                        <span class="error"><?php //echo form_error('edu_quali');?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edu_qualification" class="col-sm-3 control-label">Graduation <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                        <input type="radio" id="graduate" name="edu_quali" required value="graduate" <?php if(isset($examRes['qualification']) && $examRes['qualification'] == 'graduate') { echo 'checked="checked"'; }?>>
                                        <span class="error"><?php //echo form_error('edu_quali');?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                  <label for="edu_qualification" class="col-sm-3 control-label">Post Graduation <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                        <input type="radio" id="post_graduate" name="edu_quali" required value="post_graduate" <?php if(isset($examRes['qualification']) && $examRes['qualification'] == 'post_graduate') { echo 'checked="checked"'; }?>>
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
                                  <label for="id_proof" class="col-sm-3 control-label">Id Proof Number <span class="mandatory-field">*</span></label>
                                  <div class="col-sm-5" id="id_no_proof">
                                      <input type="text" class="form-control" id="idproof_no" name="idproof_no" placeholder="Id Proof Number." value="<?php echo $examRes['idproof_no'];?>" data-parsley-required  >
                                      <span class="note" id="idproof_no_note"></span></br>
                                      <span class="note-error" id="idproof_no_error"></span>
                                  </div> 
                                </div>

                                 <div class="form-group">
                                    <label for="id_proof" class="col-sm-3 control-label">Proof of Identity <span class="required-spn">*</span></label>
                                    <div class="col-sm-5" id="exist_draidproofphoto">
                                        
                                            <input  type="file" name="draidproofphoto" id="draidproofphoto" autocomplete="off" <?Php if($examRes["idproofphoto"] == ''){ echo 'required';} ?>>
                                       
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
                                       
                                          <input  type="file" name="qualicertificate" id="qualicertificate" autocomplete="off" <?Php if($examRes["quali_certificate"] == ''){ echo 'required';} ?> > 
                                       
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
                                         
                                           <input  type="file" name="drascannedphoto" id="drascannedphoto" autocomplete="off" <?Php if($examRes["scannedphoto"] == ''){ echo 'required';} ?>>
                                        
                                       
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
                                         
                                           <input  type="file" name="drascannedsignature" id="drascannedsignature" autocomplete="off" <?Php if($examRes["scannedsignaturephoto"] == ''){ echo 'required';} ?>>
                                       
                                       
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

              <?php if(count($DraCandidateLogs) > 0)
              { ?>
                <div class="box" style="margin:15px 0 0 0">
                  <div class="box-header with-border">
                    <h3 class="box-title">Candidate Logs</h3>
                    <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button></div>
                  </div>
                  
                  <div class="box-body ">
                    <div class="table-responsive">
                      <table id="listitems_logs_new" class="table table-bordered table-striped">
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
                              <td><?php echo date_format(date_create($res_log['created_on']),"d-M-Y h:i:s"); ?></td>
                            </tr>                              
                            <?php $i++; 
                          } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              <?php }  ?>
           </div>
        </div>
      </div>
     
      
      
    </section>
    </form>
</div>
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<!-- Data Tables --> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script>
<style>
.report_tag{
display:none;
clear: both;
padding: 17px 8px;
border: 1px solid #ccc;
margin: 5px;
max-width: 408px;
text-align: center;
}
.inspec{
 max-width:80%;	
}
.red{
 color:red;	
}
.err{
 border:1px solid #F00;	
}
.rejection{
 display:none;	
}
#center_validity{
 width:230px;	
}
#center_validity_to_date{
 width:230px;	
}
.box-header > .box-tools {
    top: 0px !important;
}
table.dataTable th{
	/*text-align:center;*/
	text-transform:capitalize;	
}
table.dataTable thead > tr > th{
	padding-right:4px !important;
}
.table-responsive{
/* overflow-x:hidden !important; */
}

/* .table-responsive > .dataTables_wrapper, .table-responsive > .table
{
	max-width: 96%;
	margin: 0 auto;
} */

td {
	word-wrap: anywhere;
	white-space: unset !important;
}

.DTTT_button_print{
	display:block;
}
#batch_active_period{
 width:220px;	
 float:left;
}
.batch_active_period_btn{
 float:right;	
}
.act_msg{
 font-size:12px;
 font-style:italic;
 color:#900;
 widows:100%;	
}
#inspector_id{
 width:210px;	
}
.rejection{
 width:85%;
 margin:4px;
 clear:both;	
}
</style>
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 
<script src="<?php echo base_url()?>js/validation_dra_batch.js"></script>

<script>
$(function () {

	var dateToday = new Date();	
	//var validity_to_ck =  $('#batch_to_date_val').val(); //2019-02-26
	var validity_to_ck = $('#batch_to_date_val').val();
 
	$('#batch_active_period').datepicker({
			autoclose: true, 
			format: 'dd-mm-yyyy', 												        
			dateFormat: 'dd-mm-yyyy',
			todayHighlight: true			
	}).attr('readonly', 'readonly');
	
   $('#batch_active_period').datepicker('setStartDate', dateToday);
  
  // Remove this code to allow user to add bach active period more than to date // on 25 may 2019 by MM 
   //if(validity_to_ck != ''){
  	//$('#batch_active_period').datepicker('setEndDate', validity_to_ck);
  // }
   
   
	//batch_active_period_btn is button add to add active period
	$('.batch_active_period_btn').click(function(){
		//AP = Active period		
		$('#action_status').val('AP');		
		var active_period = $('#batch_active_period').val();		
		if(active_period == ''){
		  $('#batch_active_period').addClass('err');
		  return false;	
		}
		var batch_status = $('#batch_status').val();
		if(batch_status != 'A'){
		  alert('Please approve batch before assign batch active period');		
		  return false;	
		}
		
		//alert(batch_status);				
  		if (confirm('Are you sure you want to assign Batch Active Period?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}		
	});
	
	$('.inspec').on('change', function() {
		var curr_insp = $('.inspec').val();	
	    var insp_new =  $('#new_inspector_id').val(curr_insp);	
	});
	
	
	$('.approve_batch').click(function(){ 
	$('#action_status').val('A');
		
		var insp_old 	=  $('#old_inspector_name').val();
		var add_reason 	= 1;
			
		if ($(".inspec").length > 0) {		
			var curr_insp = $('.inspec').val();			
			if(curr_insp != ''){
				var insp_new =  $('#new_inspector_id').val(curr_insp);	
			}
	  	}
		
		var insp_new =  $('#new_inspector_id').val();		
		
		if(insp_new != '' && insp_new != 0 ){			
			add_reason = 0;
			console.log('in_1');
		}
		if(insp_old != '' && insp_old != 0 && add_reason != 0){
			add_reason = 0;
			console.log('in_2');
		}
		
		//alert(insp_new+''+insp_new+''+add_reason);
		console.log(insp_new+''+insp_new+''+add_reason);
		if(add_reason == 1){
			
			$('.rejection').attr("placeholder", "Describe not assigned inspector reason here");	
			$('.rejection').show();	
			var reject_reason = $.trim($('.rejection').val());
			if(reject_reason == ''){
			  $('.rejection').addClass('err');
			  return false;	
			}
		}else{
			
			$('.rejection').hide();
			$('.rejection').val('');
		}
	
/*
// Removed inpector assgin validation as per excel doc share by sonal on 4 march 2019 
var insp =  $('#inspector_id').val();
if(insp == ''){
	$('#inspector_id').addClass('err');
	alert('Please assign inspector for batch');			
	return false;	
}else{
	$('#inspector_id').removeClass('err');
	$('#new_inspector_id').val(insp);
}

var insp_new =  $('#new_inspector_id').val();
if(insp_new == ''){
	alert('Please assign inspector for batch');
	return false;	
}
*/
			 		
		if (confirm('Are you sure you want to Approve Center?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}			
	});
	
	$('.update_inspector').click(function(){ 
		$('#action_status').val('UPDATE_INSPECTOR');
		var insp =  $('#inspector_id').val();
		if(insp == ''){
			$('#inspector_id').addClass('err');
			alert('Please assign inspector for batch');			
		  	return false;	
		}else{
			$('#inspector_id').removeClass('err');
			$('#new_inspector_id').val(insp);
		}	
				
		var insp_old =  $('#old_inspector_name').val();
		var insp_new =  $('#new_inspector_id').val();
	
		if(insp_new == ''){
			alert('Please assign inspector for batch');
		  	return false;	
		}else if(insp_new == insp_old ){			
			alert('Selected inspector already assign for this batch');
		  	return false;	
		}			 		
		if (confirm('Are you sure you want to update inspector?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}			
	});
	
	/* Get City From State in Agency tab */
    $('#ccstate').on('change',function(){
    var state_code = $(this).val();
    
    //alert(state_code);
    if(state_code){
      $.ajax({
        type:'POST',
        url: '<?php echo base_url(); ?>iibfdra/Batch/getCity',
        data:'state_code='+state_code,
        success:function(html){
            //alert('swati');
          $('#city').show();
          $('#city').html(html);
        }
      });
      }else{
          //alert('swa');
        $('#city').html('<option value="">Select State First</option>');
      }
    });
	
	
	$('.reject_batch').click(function(e){
		$('#action_status').val('R');
		$('.rejection').show();		
		$('.rejection').attr("placeholder", "Describe rejection reason here");	
		var reject_reason = $.trim($('.rejection').val());
		
		if(reject_reason == ''){
		  $('.rejection').addClass('err');
		  return false;	
		}
		
  		if (confirm('Are you sure you want to Reject Center?')) {
			e.preventDefault();
			$('#approve_from').submit();
				
		} else {
			return false;
		}		
	});
	
	$('.cancel_batch').click(function(e){
		$('#action_status').val('C');
		$('.rejection').attr("placeholder", "Describe cancel batch reason here");	
		$('.rejection').show();	
		var reject_reason = $.trim($('.rejection').val());
		
		if(reject_reason == ''){
		  $('.rejection').addClass('err');
		  return false;	
		}
		
  		if (confirm('Are you sure you want to Cancel Center?')) {
			e.preventDefault();
			$('#approve_from').submit();	
		} else {
			return false;
		}		
	});
	
	$('.submit_report').click(function(e){
		$('#action_status').val('REPORT');
		//$('.rejection').show();		
		var inspector_report = $('.inspector_report').val();
		//alert('inspector_report'+inspector_report)
		if(inspector_report == ''){
		  $('.inspector_report').addClass('err');
		  return false;	
		}
		
		var ext = $('.inspector_report').val().split('.').pop().toLowerCase();
		if($.inArray(ext,['pdf','doc','docx','jpg','png','jpeg']) == -1) {
			//pdf|PDF|doc|DOC|docx|DOCX|txt|TXT|jpg|png|jpeg|JPG|PNG|JPEG
			alert('invalid extension!');
			return false;	
		}
		
  		if (confirm('Are you sure you want to submit Inspection report?')) {
			e.preventDefault();
			$('#approve_from').submit();	
			
		} else {
			return false;
		}		
	});
	
	$('.add_report').click(function(){
		$('.report_tag').slideDown('slow');
	});
	
	$("#listitems").DataTable();
	//$("#listitems_logs").DataTable();
		
	//$("#listitems_logs_filter").show();		
	$("#listitems_filter").show();
	
});

var table = jQuery('#listitems_logs').DataTable( {  
	buttons: [
        'print'
    ],
    "paging": true,	
    "ordering": true,
    "autoWidth": false,   
    "columnDefs": [
      { "width": "7%", "targets": 0 },
      { "width": "25%", "targets": 1 },
      { "width": "17%", "targets": 2 },
      { "width": "50%", "targets": 3 }     
    ],
    "rowCallback": function( row, data, index ) {
      //console.log(index, data);
      for(n=6;n<55;n++){
          var color = (data[n] == 1) ? 'green' : ((data[n] == 2) ? 'yellow': ((data[n] == 3) ? 'red' : 'grey'));
          jQuery('td:eq('+n+')', row).css('background-color', color);
          jQuery('td:eq('+n+')', row).css('color', color);
      }
    },
    "headerCallback": function( thead, data, start, end, display ) {
      jQuery(thead).find('th').eq(0).css('width', '300px');
    },
    "drawCallback": function( settings ) {
        var api = new jQuery.fn.dataTable.Api( settings ); 
        // Output the data for the visible rows to the browser's console
        // You might do something more useful with it!
        //console.log( api.rows( {page:'current'} ).data() );
    }
       
} );


</script>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>
<?php $this->load->view('iibfdra/admin/includes/footer');?>