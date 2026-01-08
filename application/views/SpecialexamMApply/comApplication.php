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
	<form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>ApplySplexamM/comApplication/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>"> 
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
            <?php
					  $fee_amount=$grp_code='';?>
                     <input type="hidden" name="reg_no" id="reg_no" value="<?php echo $user_info[0]['regnumber'];?>">
                      <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type'];?>">
                      <input type="hidden" id="exname" name="exname"  value=" <?php echo $examinfo[0]['description'];?>">
                       <input type="hidden" id="excd" name="excd"  value="<?php echo base64_encode($this->session->userdata('examcode'));?>">
                         <input id="examcode" name="examcode" type="hidden" value="<?php echo $this->session->userdata('examcode');?>">
                         <input id="eprid" name="eprid" type="hidden" value="<?php echo $examinfo[0]['exam_period'];?>">
                         <input id="fee" name="fee" type="hidden" value="">   
                          <input type='hidden' name='mtype' id='mtype' value="<?php echo $this->session->userdata('memtype')?>">  
             
             <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	 <?php 
					 	 echo $user_info[0]['regnumber'];?>
                    </div>
                </div>-->
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
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['lastname'];?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Note :</label>
                     <div class="col-sm-8">
													In order to check your profile details, you need to <a href="<?php echo base_url();?>" target="new"><strong>login</strong></a> with your membership number and password
                         <span class="error"></span>
                    </div>
                    
                </div>
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone : STD Code </label>
                	<div class="col-sm-2">
                     <?php echo $user_info[0]['stdcode'];?>
                     <?php echo $user_info[0]['office_phone'];?>
                      <span class="error"><?php //echo form_error('stdcode');?></span>
                    </div>
                    
                </div>-->
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['mobile'];?>
                      <input type="hidden" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>" data-parsley-trigger-after-failure="focusout" >
                      <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>-->
                <input type="hidden" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>" data-parsley-trigger-after-failure="focusout" >
                      <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                      
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['email'];?>
                    <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" type="hidden" data-parsley-trigger-after-failure="focusout">
                      <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                       <br>
                         <span style="color:#F00;font-size:small;">(For correction/updation of your Email id and Mobile no., use your Edit Profile available under Member Login.)</span>
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>-->
                
                <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" type="hidden" data-parsley-trigger-after-failure="focusout">
                <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                      
                
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
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">GSTIN No.&nbsp;</label>
                	<div class="col-sm-5 ">
                         <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="GSTIN No." value="<?php echo set_value('gstin_no');?>"  data-parsley-minlength="15" data-parsley-maxlength="15" data-parsley-trigger-after-failure="focusout">
                     <div id="error_dob"></div>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>-->
                
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5 " id="html_fee_id">
                    <div style="color:#F00">select center first</div>
                        <?php //echo $examinfo['0']['fees'];?>
                        <?php //if($examinfo['0']['fees']==''){echo '-';}else{echo $examinfo['0']['fees'];}?>
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                 <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                	<div class="col-sm-5 ">
                    <?php 
                  //  $month = date('Y')."-".substr($examinfo['0']['exam_month'],4)."-".date('d');
                   // echo date('F',strtotime($month))."-".substr($examinfo['0']['exam_month'],0,-2);
           			  ?>
                        <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
                   <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>-->
                
               
                
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Examination Date<span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <select name="splexamdate" id="splexamdateM" class="form-control" required>
                  	<option value="">Select</option>
                    <?php /*if(count($special_exam_dates) > 0)
					{
						$exam_period= $this->db->userdata['enduserinfo']['eprid'];
						
						foreach($special_exam_dates as $mrow)
						{							
							  // $examination_total_cnt=$this->master_model->getRecordCount('special_exam_apply',array('examination_date'=>$mrow['examination_date']));
							   $examination_total_cnt=$this->master_model->getRecordCount('member_exam',array('examination_date'=>$mrow['examination_date'],'pay_status'=>'1','exam_period'=>$exam_period)); 
							    ?>
                            
								<option value="<?php echo $mrow['examination_date']?>" <?php if(in_array($mrow['examination_date'],$specialdateapply)){echo 'disabled="disabled"';}elseif($examination_total_cnt==108){echo 'disabled="disabled"';}?>><?php echo date('d-M-Y',strtotime($mrow['examination_date']));?></option>
						<?php }
					}*/?>
                    </select>
                    </div>
                </div>-->
                
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
               		$this->db->where('exam_code',$examinfo['0']['exam_code']);
					$sql = $this->master_model->getRecords('exam_master','','elearning_flag'); 
					if($sql[0]['elearning_flag'] == 'Y'){
				?>
                
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label> 
                        <div class="col-sm-3">
                       
                           <input type="radio" name="elearning_flag" id="elearning_flag_Y" value="Y" >YES
						   <input type="radio" name="elearning_flag" id="elearning_flag_N" value="N" checked="checked">NO
						   
                        </div>
                 </div>
                 <?php }else{?>
                 <input type="hidden" name="elearning_flag" id="elearning_flag_Y" value="Y" >
				 <input type="hidden" name="elearning_flag" id="elearning_flag_N" value="N" >
                 <?php }?>
                   
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
             
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"></label>
                    <div style="color:#F00">Registration for  Exam on 25-Feb-2017 for MUMBAI centre has been closed Select any other dates.</div>
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>-->
                
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
          
          
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Photo</label>
                	<div class="col-sm-2">
                     <label for="roleid" class="col-sm-3 control-label">
                    <?php 
					if(is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')))
					{?>
                     <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('mregnumber_applyexam'),'p');?>" height="100" width="100" >
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
                </div>-->
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                	<div class="col-sm-2">
                     <label for="roleid" class="col-sm-3 control-label">
                     <?php 
					if(is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')))
					{?>
                     <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('mregnumber_applyexam'),'s');?>" height="100" width="100">
               
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
                </div>-->
                
                
                
                  <?php 
				/*if(!file_exists('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) ||$user_info[0]['scannedphoto']=='' || $user_info[0]['scannedsignaturephoto']=='')
			{*/
			if(!is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')) || !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')))
			{
				?>
            	   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"></label>
                    If your above Photo/Signature is not clear, Pl upload another Photo/Signature using the below given link.
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>
                
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
                        <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto" required="required">
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
                
               
				
					 <input type="hidden" name="elected_exam_mode" id="elected_exam_mode" value="C">
                        <input type="hidden" name="placeofwork" id="placeofwork" value="">
                       	   <input type="hidden" name="state_place_of_work" id="state" value="">
                        	 <input type="hidden" name="pincode_place_of_work" id="pincode_place_of_work" value="">
				
				
				
				<!-- Benchmark Disability Code Start -->
             <div class="form-group">
			  <label for="roleid" class="col-sm-3 control-label">Person with Benchmark Disability</label>
			  <div class="col-sm-5">
				<input value="Y" name="benchmark_disability" id="benchmark_disability" type="radio" <?php if($benchmark_disability_info[0]['benchmark_disability']=='Y'){echo  'checked="checked"';} ?> class="benchmark_disability_y" disabled="disabled">
				Yes
				<input value="N" name="benchmark_disability" id="benchmark_disability" type="radio" <?php if($benchmark_disability_info[0]['benchmark_disability']=='N'){echo  'checked="checked"';} ?> class="benchmark_disability_n" disabled="disabled">
				No <span class="error"></span> </div>
			</div>
			<?php 
			  if($benchmark_disability_info[0]['benchmark_disability']=='Y')
			  {
			  ?>
              <div id="benchmark_disability_div">
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Visually impaired</label>
				  <div class="col-sm-5">
					<input value="Y" name="visually_impaired" id="visually_impaired" type="radio" class="visually_impaired_y" <?php if($benchmark_disability_info[0]['visually_impaired']=='Y'){echo  'checked="checked"';} ?> disabled="disabled">
					Yes
					<input value="N" name="visually_impaired" id="visually_impaired" type="radio" class="visually_impaired_n" <?php if($benchmark_disability_info[0]['visually_impaired']=='N'){echo  'checked="checked"';} ?> disabled="disabled">
					No </div>
				</div>
				 <?php 
				  if($benchmark_disability_info[0]['visually_impaired']=='Y')
				  {
				  ?>
				  <div class="form-group"  id="vis_imp_cert_div">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5">
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/v_'.$this->session->userdata('mregnumber_applyexam').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  </div>
					</div>
				  <?php
				  }
				  ?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Orthopedically handicapped</label>
				  <div class="col-sm-5">
					<input value="Y" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio"  <?php if($benchmark_disability_info[0]['orthopedically_handicapped']=='Y'){echo  'checked="checked"';} ?> class="orthopedically_handicapped_y"  disabled="disabled">
					Yes
					<input value="N" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio"  <?php if($user_info[0]['orthopedically_handicapped']=='N'){echo  'checked="checked"'; } ?> class="orthopedically_handicapped_n" disabled="disabled">
					No <span class="error"></span> </div>
				</div>
				  <?php 
				  if($benchmark_disability_info[0]['orthopedically_handicapped']=='Y')
				  {
				  ?>
				  <div class="form-group" id="orth_han_cert_div">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5">
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/o_'.$this->session->userdata('mregnumber_applyexam').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  </div>
				</div>
				  <?php
				  }
				  ?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Cerebral palsy</label>
				  <div class="col-sm-5">
					<input value="Y" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php if($benchmark_disability_info[0]['cerebral_palsy']=='Y'){echo  'checked="checked"';} ?>  class="cerebral_palsy_y"  disabled="disabled">
					Yes
					<input value="N" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php if($benchmark_disability_info[0]['cerebral_palsy']=='N'){echo  'checked="checked"';} ?>  class="cerebral_palsy_n"  disabled="disabled">
					No <span class="error"></span> </div>
				</div>
				  <?php 
				  if($benchmark_disability_info[0]['cerebral_palsy']=='Y')
				  {
				  ?>
				  <div class="form-group" id="cer_palsy_cert_div">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5">
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/c_'.$this->session->userdata('mregnumber_applyexam').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  </div>
				</div>
				  <?php
				  }
				  ?>
			</div>
			<?php 
			}
			?>
			<!-- Benchmark Disability Code Close -->
				
                    <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Scribe required?</label>
                        <div class="col-sm-3">
                           <input type="checkbox" name="scribe_flag" id="scribe_flag" value="Y">
                        </div>
                    </div>
                <div class="form-group">
              <div class="col-sm-12">
                <label for="roleid" class="col-sm-0 control-label"></label>
                  <img src="<?php echo base_url()?>assets/images/bullet2.gif"> The candidate should send a separate application along with the DECLARATION as given in the  Scribe Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination 
                         (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt 
                         of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.<br />
                     
<br />
               
                 <!--     A) It is mandatory for a candidate to opt the examination centre being his/her place of work. If there is no centre at his/her place of work, shall have to opt the centre nearest to his/her place of work. Result of candidate violating this rule or giving wrong information is liable for cancellation)--><br>

<!--B) Since the Institute will not be sending the Admit Letter(hard copy) through post, Correct E-mail address is mandatory for receipt of Admit Letter/Hall Ticket through e-mail.-->
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>
                </div>
               
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
               <?php /*?>      <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return spl_member_apply_exam();" id="preview">Preview</a><?php */?>
                       <button type="submit" class="btn btn-info" name="preview" id="preview"  onclick="javascript:return spl_member_apply_exam()">Preview</button>
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                     <button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button>
                     <a href="<?php echo base_url();?>ApplySplexamM/examdetails/?ExId=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>
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

<script type="text/javascript">
  function loginusercheckform()
  {$('#member_conApplication').parsley().validate();}
</script>

<script>
$('#scribe_flag').on('change', function(e){
   if(e.target.checked){
     $('#myModal').modal();
   }
});
$(document).ready(function(){
	$("#elearning_flag_Y").click(function(){
		var cCode =  document.getElementById('txtCenterCode').value;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var selected_month = document.getElementById("selCenterName").options[temp].className;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		var grp_code = document.getElementById('grp_code').value;
		var extype= document.getElementById('extype').value;
		var mtype= document.getElementById('mtype').value;
		
		if(document.getElementById('elearning_flag_Y').checked){
			var Eval = document.getElementById('elearning_flag_Y').value;
		}
		
		if(document.getElementById('elearning_flag_N').checked){
			var Eval = document.getElementById('elearning_flag_N').value;
		}
		
		if(cCode != ''){
			var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;
				$.ajax({
						url:site_url+'Fee/getFee/',
						data: datastring,
						type:'POST',
						async: false,
						success: function(data) {
						 if(data)
						{
							document.getElementById('fee').value = data ;
							document.getElementById('html_fee_id').innerHTML =data;
							//response = true;
						}
					}
				});
		}
	});
	
	$("#elearning_flag_N").click(function(){
		var cCode =  document.getElementById('txtCenterCode').value;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var selected_month = document.getElementById("selCenterName").options[temp].className;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		var grp_code = document.getElementById('grp_code').value;
		var extype= document.getElementById('extype').value;
		var mtype= document.getElementById('mtype').value;
		
		if(document.getElementById('elearning_flag_Y').checked){
			var Eval = document.getElementById('elearning_flag_Y').value;
		}
		
		if(document.getElementById('elearning_flag_N').checked){
			var Eval = document.getElementById('elearning_flag_N').value;
		}
		
		if(cCode != ''){
			var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;
				$.ajax({
						url:site_url+'Fee/getFee/',
						data: datastring,
						type:'POST',
						async: false,
						success: function(data) {
						 if(data)
						{
							document.getElementById('fee').value = data ;
							document.getElementById('html_fee_id').innerHTML =data;
							//response = true;
						}
					}
				});
		}
	});
	
});

</script>

