<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>ApplyexamMK/comApplication/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('eregid');?>"> 
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
            <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php } 
			 ?> 
             
             <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	 <?php 
					 	 echo $user_info[0]['regnumber'];
						  $fee_amount=$grp_code='';
						  ?>
                     <input type="hidden" name="reg_no" id="reg_no" value="<?php echo $user_info[0]['regnumber'];?>">
                      <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type'];?>">
                      <input type="hidden" id="exname" name="exname"  value=" <?php echo $examinfo[0]['description'];?>">
                       <input type="hidden" id="excd" name="excd"  value="<?php echo base64_encode($this->session->userdata('examcode'));?>">
                         <input id="examcode" name="examcode" type="hidden" value="<?php echo $this->session->userdata('examcode');?>">
                         <input id="eprid" name="eprid" type="hidden" value="<?php echo $examinfo[0]['exam_period'];?>">
                         <input id="fee" name="fee" type="hidden" value="">
                          <input type='hidden' name='mtype' id='mtype' value="<?php echo $this->session->userdata('memtype')?>">  
                         
                         <?php 
							if(isset($examinfo[0]['app_category']))
							{
								$grp_code=$examinfo[0]['app_category'];
							}
							else
							{
								$grp_code='B1_1';
							};
                        ?>
                        	 <input id="grp_code" name="grp_code" type="hidden" value="<?php echo trim($grp_code);?>">
                                                  
                    </div>
                </div>
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name </label>
                     <div class="col-sm-3">
					<?php echo $user_info[0]['firstname'];?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['middlename'];?>
                  <!--    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $user_info[0]['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['lastname'];?>
                      <!--<input type="text" class="form-control" id="middlename" name="lastname" placeholder="Last Name"  value="<?php echo $user_info[0]['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone : STD Code </label>
                	<div class="col-sm-2">
                     <?php echo $user_info[0]['stdcode'];?>
                     <?php echo $user_info[0]['office_phone'];?>
                      <span class="error"><?php //echo form_error('stdcode');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-editmobilecheckexamapply required data-parsley-trigger-after-failure="focusout" >
                      <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-editemailcheckexamapply  type="text" data-parsley-trigger-after-failure="focusout" >
                    
                      
                       <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                      <!--(For correction/updation of your Email id and Mobile no., use your Edit Profile available under Member Login.)-->
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
                </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            
         

            <div class="box-body">
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                	<div class="col-sm-5 ">
                        <?php echo $examinfo['0']['description'];?>
                     <div id="error_dob"></div>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5" id="html_fee_id">
                    <div style="color:#F00">select center first</div>
                        <?php //echo $examinfo['0']['fees'];?>
                        <?php //if($examinfo['0']['fees']==''){echo '-';}else{echo $examinfo['0']['fees'];}?>
                     <div id="error_dob"></div>
                   
                   <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                	<div class="col-sm-5 ">
                    <?php 
                    //$month = date('Y')."-".substr($examinfo['0']['exam_month'],4)."-".date('d');
					$month = date('Y')."-".substr($examinfo['0']['exam_month'],4);
                    echo date('F',strtotime($month))."-".substr($examinfo['0']['exam_month'],0,-2);
           			  ?>
                        <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
                   <div id="error_dob"></div>
                 
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                <?php if(count($compulsory_subjects) > 0 && $this->session->userdata('examcode')==101){?>
               	<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Examination Date</label>
                	<div class="col-sm-5 ">
                    <?php echo  date('d-M-Y',strtotime($compulsory_subjects[0]['exam_date']));?>
                   <div id="error_dob"></div>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                <?php }
				$elective_sub=0;
				if(isset($examinfo[0]['app_category']) &&(count($caiib_subjects) >0))
				{
					$subject_name=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E','subject_code'=>$examinfo[0]['subject']),'subject_description');?>
				<div class="form-group">
					<!--	<label for="roleid" class="col-sm-3 control-label">Elective Subject Name <span style="color:#F00">*</span></label>-->
							<div class="col-sm-4">
                 	    <?php
					 		if(count($subject_name) > 0)
                            {
                               // echo $subject_name[0]['subject_description'];?>
                                 <input type="hidden" name="selSubName" id="" value="<?php echo $examinfo[0]['subject'];?>">
                                 <input type="hidden" name="selSubcode" id="selSubcode" value="<?php echo $examinfo[0]['subject'];?>">
                                 <input type="hidden" name="selSubName1" id="selSubName1" value="<?php echo $subject_name[0]['subject_description'];?>">
						    <?php 
							}
							else
							{?>
                            	 <input type="hidden" name="selSubcode" id="selSubcode" value="">
				                 <input type="hidden" name="selSubName1" id="selSubName1" value="">
							<?php }
						?>
							</div>
						</div>
				<?php }
				else
				{?>
				 <input type="hidden" name="selSubcode" id="selSubcode" value="">
                 <input type="hidden" name="selSubName1" id="selSubName1" value="">
			<?php 
				}?>
                
                
                
			     <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <select name="medium" id="medium" class="form-control" required>
                  	<option value="">Select</option>
                    <?php if(count($medium) > 0)
					{
						foreach($medium as $mrow)
						{?>
								<option value="<?php echo $mrow['medium_code']?>"><?php echo $mrow['medium_description']?></option>
						<?php }
					}?>
                    </select>
                    </div>
                </div>
  				 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>
                	<div class="col-sm-4">
                    <select name="selCenterName" id="selCenterName" class="form-control" required onchange="valCentre(this.value);">
                  	<option value="">Select</option>
                    <?php if(count($center) > 0)
					{
						foreach($center as $crow)
						{?>
								<option value="<?php echo $crow['center_code']?>" class=<?php echo $crow['exammode'];?>><?php echo $crow['center_name']?></option>
						<?php }
					}?>
                    </select>
                    </div>
                   </div>
                   
                 <?php 
				   if(count($compulsory_subjects) > 0 && $this->session->userdata('examcode')!=101)
				   {
					   $i=1;
					   foreach($compulsory_subjects as $subject)
					   {?>
                            <div class="form-group">
                          	  <label for="roleid" class="col-sm-3 control-label"><?php echo $subject['subject_description']?><span style="color:#F00">*</span></label>
                                <div class="col-sm-2">
                                <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>
                                <select name="venue[<?php echo $subject['subject_code']?>]" id="venue_<?php echo $i;?>" class="form-control venue_cls" required  onchange="venue(this.value,'date_<?php echo $i;?>','time_<?php echo $i;?>','<?php echo $subject['subject_code']?>','seat_capacity_<?php echo $i;?>');" attr-data='<?php echo $subject['subject_code']?>'>
                                <option value="">Select</option>
                                </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <label for="roleid" class="col-sm-3 control-label">Date<span style="color:#F00">*</span></label>
                                <select name="date[<?php echo $subject['subject_code']?>]" id="date_<?php echo $i;?>" class="form-control date_cls" required  onchange="date(this.value,'venue_<?php echo $i;?>','time_<?php echo $i;?>');">
                                <option value="">Select</option>
                                </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <label for="roleid" class="col-sm-3 control-label">Time<span style="color:#F00">*</span></label>
                                <select name="time[<?php echo $subject['subject_code']?>]" id="time_<?php echo $i;?>" class="form-control time_cls" required onchange="time(this.value,'venue_<?php echo $i;?>','date_<?php echo $i;?>','seat_capacity_<?php echo $i;?>');">
                                <option value="">Select</option>
                                </select>
                                </div>
                                
                               
                                <label for="roleid" class="col-sm-0 control-label">Seat(s) Available<span style="color:#F00">*</span></label>
                                <div id="seat_capacity_<?php echo $i;?>">
                              	-
                                </div>
                               </div>
                               
                               
                <?php 
				$i++;}
						
						if(!isset($examinfo[0]['app_category']) &&(count($caiib_subjects) >0))  
						{?>
                                <div class="form-group">
                                <label for="roleid" class="col-sm-12 control-label">
                                    <div class="col-sm-12 control-label">
                                      <div class="col-sm-3">
                                    <select name="selSubName" id="selSubName" class="form-control" required>
                                    <option value="">Elective Subject</option>
                                    <?php 
                                        foreach($caiib_subjects as $srow)
                                        {?>
                                                <option value="<?php echo $srow['subject_code']?>"><?php echo $srow['subject_description']?></option>
                                        <?php 
                                        }?>
                                    </select>
                                   <div class="text-center">
                                    <input value="Change Subject" name="enab_elect_subj" class="button" id="enab-elect-subj" type="button">
                                    </div>
                                      </div>
                                     <div class="col-sm-2">
                                    <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>
                                    <select  name="venue_caiib" id="venue_id" class="form-control venue_cls" required  onchange="caiib_venue(this.value,'date_id','time_id','seat_capacity_id');" >
                                    <option value="">Select</option>
                                    </select>
                                    </div>
                                    
                                    <div class="col-sm-2">
                                    <label for="roleid" class="col-sm-3 control-label">Date<span style="color:#F00">*</span></label>
                                    <select name="date_caiib" id="date_id" class="form-control date_cls" required  onchange="date(this.value,'venue_id','time_id');">
                                    <option value="">Select</option>
                                    </select>
                                    </div>
                                    
                                    <div class="col-sm-2">
                                    <label for="roleid" class="col-sm-3 control-label">Time<span style="color:#F00">*</span></label>
                                    <select name="time_caiib" id="time_id" class="form-control time_cls" required onchange="time(this.value,'venue_id','date_id','seat_capacity_id');">
                                    <option value="">Select</option>
                                    </select>
                                    </div>
                                    
                                      <label for="roleid" class="col-sm-2 control-label" style="text-align:left;">Seat(s) Available<span style="color:#F00">*</span><div id="seat_capacity_id">
                                    -
                                    </div></label>
                                    
                                  
                                    </div>
                                   </label>
                                </div>
			  <?php }
					   
				 }?>
             
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Code <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                      <input type="text" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly"
                       value="">
                    </div>
                  </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode <span style="color:#F00">*</span></label>
              
                
              
                	<!--<div class="col-sm-2"  style="display:none" id="electiverealmode">
                      <input type="radio" class="minimal" id="optsex1"   name="optmode" value="ON" required>
                     Online
                   <input type="radio" class="minimal" id="optsex2"   name="optmode"   value="OF" required>
                     Offline
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>-->
               
                  <div name="optmode1" id="optmode1" style="display: none;">Exam will be in ONLINE mode only, Read Important Instructions on the website.</div>
                  <div name="optmode2" id="optmode2" style="display: none;">Exam will be in OFFLINE mode only, Read Important Instructions on the website.</div>
                  <input id="optmode" name="optmode" value="" type="hidden">
               
                </div>
         	  <?php if($this->session->userdata('examcode')!=101)
			   {
			   ?>
        		  
             <?php 
			   }?>     
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Photo</label>
                	<div class="col-sm-2">
                     <label for="roleid" class="col-sm-3 control-label">
               <?php 
			    if(is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')))
				{?>
                     <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('mregnumber_applyexam'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" >
				<?php 
                }
                else
                {?>
          		      <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                <?php 	
				}?>
                     </label>
                 
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
                     <img id="image_upload_scanphoto_preview" height="100" width="100"/>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                	<div class="col-sm-2">
                     <label for="roleid" class="col-sm-3 control-label">
                 <?php 
			   	if(is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')))
				{?>
                     <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('mregnumber_applyexam'),'s');?><?php echo '?'.time(); ?>" height="100" width="100">
                   <?php 
				}
				else
				{?>
                	 <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                <?php 
				}?>   
                     </label>
                 
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
                       <img id="image_upload_sign_preview" height="100" width="100"/>
                </div>
                
                
                  <?php 
				/*if(!file_exists('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) ||$user_info[0]['scannedphoto']=='' || $user_info[0]['scannedsignaturephoto']=='')
			{*/
			if(!is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')) || !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')))
			{?>
            	   <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"></label>
                    If your above Photo/Signature is not clear, Pl upload another Photo/Signature using the below given link.
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>-->
                
                   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Photo</label>
                	<div class="col-sm-5">
                        <input  type="file" class="" name="scannedphoto" id="scannedphoto" required="required">
                    	 <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                    	<div id="error_photo"></div>
                     <br>
                     <div id="error_photo_size"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                    </div>
                </div>
                
                   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                	<div class="col-sm-5">
                        <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto"  required="required">
                         <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                    <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                </div>
        <?php 
		}?>        
                
                 <?php 
				 $elective_exam_code= $this->config->item('elective_exam_code');
				 if(count($caiib_subjects) > 0 ||$this->session->userdata('examcode')==$this->config->item('examCodeJaiib') || in_array($this->session->userdata('examcode'),$elective_exam_code))
				{?>
                     <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Place of Work <span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                          <input type="text" name="placeofwork" id="placeofwork" required class="form-control" data-parsley-maxlength="30" maxlength="30">
                        </div>
                        (Max 30 Characters)  
                      </div>
                      
                      
                      <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">State (Place of Work)<span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                        <select class="form-control" id="state" name="state_place_of_work" required >
                            <option value="">Select</option>
                            <?php if(count($states) > 0){
                                    foreach($states as $row1){ 	?>
                            <option value="<?php echo $row1['state_code'];?>" ><?php echo $row1['state_name'];?></option>
                            <?php } 
							} ?>
                          </select>
                        </div>
                    </div>
                    
                    
                      <div class="form-group">
                     <label for="roleid" class="col-sm-3 control-label">Pin Code (Place of Work)<span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                         <input class="form-control" id="pincode_place_of_work" name="pincode_place_of_work" placeholder="Pincode/Zipcode" required  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-editcheckpinexamapply data-parsley-type="number"  type="text" data-parsley-trigger-after-failure="focusout">
                             <span class="error"><?php //echo form_error('pincode');?></span>
                        </div>
                      </div>
           
          			 <input type="hidden" name="elected_exam_mode" id="elected_exam_mode" value="E">
           			
            <?php 
			}
				else
				{?>
					 <input type="hidden" name="elected_exam_mode" id="elected_exam_mode" value="C">
                        <input type="hidden" name="placeofwork" id="placeofwork" value="">
                       	   <input type="hidden" name="state_place_of_work" id="state" value="">
                        	 <input type="hidden" name="pincode_place_of_work" id="pincode_place_of_work" value="">
				<?php }?>    
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Scribe required?</label>
                        <div class="col-sm-3">
                           <input type="checkbox" name="scribe_flag" id="scribe_flag" value="Y">
                        </div>
                    </div>
                <div class="form-group">
              <div class="col-sm-12">
               <img src="<?php echo base_url()?>assets/images/bullet2.gif"> The candidate should send a separate application along with the DECLARATION as given in the  Scribe Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination 
                         (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt 
                         of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.<br />
              <img src="<?php echo base_url()?>assets/images/bullet2.gif"> It is mandatory for a candidate to opt the examination centre being his/her place of work. If there is no centre at his/her place of work, shall have to opt the centre nearest to his/her place of work. Result of candidate violating this rule or giving wrong information is liable for cancellation.<br>
<br /><!--B) Since the Institute will not be sending the Admit Letter(hard copy) through post, Correct E-mail address is mandatory for receipt of Admit Letter/Hall Ticket through e-mail.-->
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>
                </div>
               
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                     
                     <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return member_apply_exam();" id="preview">Preview</a>-->
                     
                       <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Preview" onclick="javascript : return  member_apply_exam();">
                     
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                     <button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button>
                     <a href="<?php echo base_url();?>ELearning/examdetails/?ExId=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>
                    </div>
              </div>
             </div>
     </div>
  </div>
     
      
      </div>
    </section>
 
  
     </form>
     </div>
         <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4></strong></center>
      </div>
      <div class="modal-body" style="color:#F00">
            The facility of scribe ,on request, is provided to the person with Disability only.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
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





<script>
$(document).ready(function(){
		var cCode=$('#selCenterName').val();
		if(cCode!='')
		{
			document.getElementById('txtCenterCode').value = cCode ;
			var examType = document.getElementById('extype').value;
			var examCode = document.getElementById('examcode').value;
			var temp = document.getElementById("selCenterName").selectedIndex;
			selected_month = document.getElementById("selCenterName").options[temp].className;
			if(selected_month == 'ON')
			{
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "block";
					document.getElementById('optmode').value= 'ON';
				}
					
				if(document.getElementById("optmode2"))
				{
					document.getElementById("optmode2").style.display = "none";	
				}
				
			}	
			else if(selected_month == 'OF')
			{
				if(document.getElementById("optmode2")){
					document.getElementById("optmode2").style.display = "block";
					document.getElementById('optmode').value= 'OF';
				}
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "none";
				}	
			}
			else{
					if(document.getElementById("optmode1")){
						document.getElementById("optmode1").style.display = "none";
					}
					if(document.getElementById("optmode2")){
						document.getElementById("optmode2").style.display = "none";
					}
			}
		
		}
		
	if($('#hiddenphoto').val()!='')
	{
		   $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
	}
	if($('#hiddenscansignature').val()!='')
	{
		   $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
	}
	
});
</script> 

<script>
$(function(){
    $('#new_captcha').click(function(event){
        event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'Register/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{$('#captcha_img').html(res);
 			}
 		}
    });
	});
	

 $("#datepicker,#doj").keypress(function(event) {event.preventDefault();});

if($('#selSubcode').val()!=0 && $('#selSubcode').val()!='')
{
	$('#selSubName').attr("disabled", true);
}

});


</script>
 
<script>
$('#scribe_flag').on('change', function(e){
   if(e.target.checked){
     $('#myModal').modal();
   }
});
</script>