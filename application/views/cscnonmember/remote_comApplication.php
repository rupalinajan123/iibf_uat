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
    
	<!-- <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>NonMember/preview/"> -->
    
    	<form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Remote_CSCNonMember/comApplication/?excode=<?php echo base64_encode($this->session->userdata('examcode'))?>&Exprd=<?php echo base64_encode($this->session->userdata('csc_remote_period'))?>">
    
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('cscnmregid');?>"> 
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
                		<?php echo $user_info[0]['regnumber'];
						  $fee_amount=$grp_code='';?>
                          
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
                      <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-nmeditmobilecheck required data-parsley-trigger-after-failure="focusout" >
                      <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-nmeditemailcheck  type="text" data-parsley-trigger-after-failure="focusout" >
                    
                      
                       <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                        <span style="color:#F00;font-size:small;">(Please check your Email id and Mobile number carefully. In case any change please do the correction here itself. Correct/Active E-mail address is mandatory for the receipt of Admit Letter and other communication/s from the Institute)</span>
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
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5 " id="html_fee_id123">
                        0
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                	<div class="col-sm-5 ">
                        <?php 
								echo "OCT 2020";
									//$month = date('Y')."-".substr($examinfo['0']['exam_month'],4)."-".date('d');
									//$month = date('Y')."-".substr($examinfo['0']['exam_month'],4);
									//echo date('F',strtotime($month))."-".substr($examinfo['0']['exam_month'],0,-2);
           			  ?>
                        <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
                   <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">GSTIN No.&nbsp;</label>
                	<div class="col-sm-5 ">
                         <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="GSTIN No." value="<?php echo set_value('gstin_no');?>"  data-parsley-minlength="15" data-parsley-maxlength="15" data-parsley-trigger-after-failure="focusout">
                     <div id="error_dob"></div>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                 <? if(count($compulsory_subjects) > 0 && $this->session->userdata('examcode')==101){?>
               		 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Examination Date</label>
                	<div class="col-sm-5 ">
                        <?php echo  date('d-M-Y',strtotime($compulsory_subjects[0]['exam_date']));?>
                   <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                <?php }?>
                
                  <?php 
				if(isset($examinfo[0]['app_category']) && ($examinfo[0]['app_category']=='B1' || $examinfo[0]['app_category']=='B2') && (count($caiib_subjects) >0))
				{
					$subject_name=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E','subject_code'=>$examinfo[0]['subject']),'subject_description');?>
				<div class="form-group">
						<label for="roleid" class="col-sm-3 control-label">Elective Subject Name <span style="color:#F00">*</span></label>
							<div class="col-sm-4">
                 	    <?php
					 		if(count($subject_name) > 0)
                            {
                                echo $subject_name[0]['subject_description'];?>
                                 <input type="hidden" name="selSubcode" id="selSubcode" value="<?php echo $examinfo[0]['subject'];?>">
                                 <input type="hidden" name="selSubName1" id="selSubName1" value="<?php echo $subject_name[0]['subject_description'];?>">
						    <?php 
							}
						?>
							</div>
						</div>
				<?php }
				else
				{
						if(count($caiib_subjects) > 0)
						{?>
                            <div class="form-group">
                            <label for="roleid" class="col-sm-3 control-label">Elective Subject Name <span style="color:#F00">*</span></label>
                                <div class="col-sm-4">
                                <select name="selSubName" id="selSubName" class="form-control" required>
                                <option value="">Select</option>
                                <?php 
                                    foreach($caiib_subjects as $srow)
                                    {?>
                                            <option value="<?php echo $srow['subject_code']?>"><?php echo $srow['subject_description']?></option>
                                    <?php 
                                    }?>
                                </select>
                                <input value="Change Subject" name="enab_elect_subj" class="button" id="enab-elect-subj" type="button">
                                </div>
                            </div>
					   <?php }?>
				
				 <input type="hidden" name="selSubcode" id="selSubcode" value="">
                 <input type="hidden" name="selSubName1" id="selSubName1" value="">
			<?php 
				}?>
                
   
			     <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <select name="medium" id="medium" class="form-control" required style="width:250px">
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
                <label for="roleid" class="col-sm-3 control-label">Select any one of the options given below to take the exam <span style="color:#F00">*</span></label>
                
                <div class="col-sm-5">
                <input type="radio" name="csc_venue" id="csc_venue1" value="H" checked="checked" />Self Venue (Home/Office etc.)<br/>
                The candidate to arrange for Desktop/Laptop/Internet connection infrastructure on their own to take the BC/BF exam.
                </div>
                </div>
                
                <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label"></label>
                	<div class="col-sm-5">
                		<input type="radio" name="csc_venue" id="csc_venue2" value="C" />CSC Venue/Centre (Optional) 
               	<br/>
                   If the candidates are not able to arrange infrastructure on their own,  he/she may visit the CSC centre/venue voluntarily on the date/time chosen by them to take the exam
                    </div>
                </div>
                
                <div class="form-group" id="cscohome" >
                
                <label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <select name="selCenterName" id="selCenterName" class="form-control cscohome" required onchange="valCentre(this.value);" style="width:250px">
                  	<option value="">Select</option>
                    
                    <?php if(count($home_center) > 0)
					{
						
						foreach($home_center as $hcrow)
						{?>
								<option value="<?php echo $hcrow['center_code']?>" class=<?php echo $hcrow['exammode'];?>><?php echo $hcrow['center_name']?></option>
						<?php }
					}?>
                    </select>
                    </div>
                   </div>
                
   
  				 <div class="form-group" style="display:none;"id="cscofficial"><!---->
                
                 
                <label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <select name="selCenterName" id="selCenterName" class="form-control cscofficial"  onchange="valCentre(this.value);" style="width:250px">
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
				   if(count($compulsory_subjects) > 0)
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
                	<!--<div class="col-sm-2">
                      <input type="radio" class="minimal" id="optsex1"   name="optmode" value="ON" required>
                     Online
                   <input type="radio" class="minimal" id="optsex2"   name="optmode"   value="OF">
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
					if(is_file(get_img_name($this->session->userdata('cscnmregnumber'),'p')))
					{?>
                     <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" id="image_upload_scanphoto_preview">
					<?php 
                    }
                    else
                    {?>
                    <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                    <?php 
                    }?>
					<input  type="file" class="" name="scannedphoto" id="scannedphoto">
                    <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                    <div id="error_photo" style=" width:250px;; text-align:left;"></div>
                     <br>
                     <div id="error_photo_size" style=" width:250px;; text-align:left;"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                     </label>
                 
				
                
                	
				
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                	<div class="col-sm-2">
                     <label for="roleid" class="col-sm-3 control-label">
					 <?php 
                    if(is_file(get_img_name($this->session->userdata('cscnmregnumber'),'s')))
                    {?>
                         <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'s');?><?php echo '?'.time(); ?>" height="100" width="100" id="image_upload_sign_preview">
					<?php 
                    }
                    else
                    {?>
                    <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                    <?php 
                    }?>
					   <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto" >
                         <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                    <div id="error_signature" style=" width:250px;; text-align:left;"></div>
                     <br>
                     <div id="error_signature_size" style=" width:250px;; text-align:left;"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                     </label>
                 
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
                    
                </div>
                
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Note :</label>
                     <div class="col-sm-8">
													In order to check your profile details, you need to <a href="<?php echo base_url().'/nonmem';?>" target="new"><strong>login</strong></a> to with your membership number and password
                         <span class="error"></span>
                    </div>
                    
                </div>
                
                  <?php 
				   $elective_exam_code= $this->config->item('elective_exam_code');
				 if(count($caiib_subjects) > 0 ||$this->session->userdata('examcode')==$this->config->item('examCodeJaiib') || in_array($this->session->userdata('examcode'),$elective_exam_code))
				  //if(count($caiib_subjects) > 0)
				{?>
                     <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Place of Work <span style="color:#F00">*</span></label>
                        <div class="col-sm-2">
                          <input type="text" name="placeofwork" id="placeofwork" required class="form-control pull-right">
                        </div>
                      </div>
                      
                      
                      <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">State (Place of Work)<span style="color:#F00">*</span></label>
                        <div class="col-sm-2">
                        <select class="form-control" id="state" name="state_place_of_work" required >
                            <option value="">Select</option>
                            <?php if(count($states) > 0){
                                    foreach($states as $row1){ 	?>
                            <option value="<?php echo $row1['state_code'];?>" ><?php echo $row1['state_name'];?></option>
                            <?php } } ?>
                          </select>
                        </div>
                    </div>
                    
                    
                      <div class="form-group">
                     <label for="roleid" class="col-sm-3 control-label">Pin Code (Place of Work)<span style="color:#F00">*</span></label>
                        <div class="col-sm-2">
                         <input class="form-control" id="pincode_place_of_work" name="pincode_place_of_work" placeholder="Pincode/Zipcode" required  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-editcheckpin data-parsley-type="number"  type="text" data-parsley-trigger-after-failure="focusout">
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
                <label for="roleid" class="col-sm-3 control-label"></label>
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>
                
                <?php 
				/*if(!file_exists('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) ||$user_info[0]['scannedphoto']=='' || $user_info[0]['scannedsignaturephoto']=='')
			{*/
			if(!is_file(get_img_name($this->session->userdata('cscnmregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('cscnmregnumber'),'p')))
			{?>
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
                      <img id="image_upload_scanphoto_preview" height="100" width="100"/>
                </div>
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                	<div class="col-sm-5">
                        <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto" required="required">
                         <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                    <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                       <img id="image_upload_sign_preview" height="100" width="100"/>
                </div>
               <?php 
			}?> 
             
               	 <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Scribe required?</label>
                        <div class="col-sm-3">
                           <input type="checkbox" name="scribe_flag" id="scribe_flag" value="Y">
                        </div>
                    </div>
                <div class="form-group">
              <div class="col-sm-12">
            
                   
<!--B) Since the Institute will not be sending the Admit Letter(hard copy) through post, Correct E-mail address is mandatory for receipt of Admit Letter/Hall Ticket through e-mail.-->
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>
                </div>
               
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                     
                     <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return login_nm_checkform();" id="preview">Preview</a>-->
                     
                     <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Preview" onclick="javascript:return login_nm_checkform();">
                     
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                   <a href="<?php echo base_url();?>CSCNonMember/comApplication/" class="btn btn-info" id="Reset">Reset</a>
                     <!--<button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button>-->
                     <a href="<?php echo base_url();?>CSCNonMember/examdetails/?excode2=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>
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
<!--<script type="text/javascript">
<!-- Data Tables -->

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script type="text/javascript">
  function loginusercheckform()
  {$('#member_conApplication').parsley().validate();}
  $('#scribe_flag').on('change', function(e){
   if(e.target.checked){
     $('#myModal').modal();
   }
});
</script>

<script>
$(document).ready(function(){
	
		$('.cscofficial').attr('id','id_new');
		$('.cscofficial').attr('name','id_new');
	
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
	
	$("#csc_venue1").click(function(){
		$("#cscohome").show();
		$("#cscofficial").hide();
		$('#selCenterName').prop('selectedIndex',0);
		
		
		$('.cscofficial').attr('id','id_new');
		$('.cscofficial').attr('name','id_new');
		$('.cscofficial').attr('required',false);
		
		$('.cscohome').attr('id','selCenterName');
		$('.cscohome').attr('name','selCenterName');
		$('.cscohome').attr('required',true);
		
	})
	
	$("#csc_venue2").click(function(){
		$("#cscohome").hide();
		$("#cscofficial").show();
		$('#selCenterName').prop('selectedIndex',0);
		
		
		$('.cscohome').attr('id','id_new');
		$('.cscohome').attr('name','id_new');
		$('.cscohome').attr('required',false);
		
		$('.cscofficial').attr('id','selCenterName');
		$('.cscofficial').attr('name','selCenterName');
		$('.cscofficial').attr('required',true);
		
	})
	
	
	
});
</script>