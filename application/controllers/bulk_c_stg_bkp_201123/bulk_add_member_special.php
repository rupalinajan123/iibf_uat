<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1> Examination application form</h1>
		
    </section>
	 
	
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
		
          <!-- Horizontal Form -->
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
			 <?php if(!empty($user_info))
			{   ?>
		<form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApply/comApplication/">
        <div class="box box-info">
            <div class="box-header with-border">
				<h3 class="box-title">Basic Details</h3>    
						 <a  class="btn btn-warning" href="<?php echo base_url();?>bulk/BulkApply/add_member/"  style="float:right">Refresh</a>
			</div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
			<div class="form-group">
                            	<label for="roleid" class="col-sm-3 control-label">Membership No :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control"  name="regnumber" placeholder="Registration no"     value="<?php echo $user_info[0]['regnumber'];?>" readonly="readonly" >
                                </div>
			</div>
			
		
			        <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>"> 
            
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
                    <?php echo $user_info[0]['mobile'];?>
                      <input type="hidden" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>" data-parsley-trigger-after-failure="focusout" >
                      <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['email'];?>
                    <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" type="hidden" data-parsley-trigger-after-failure="focusout">
                    
                      
                       <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                       <br>
                         <span style="color:#F00;font-size:small;">(For correction/updation of your Email id and Mobile no., use your Edit Profile available under Member Login.)</span>
                    <!--  (For correction/updation of your Email id and Mobile no., use your Edit Profile available under Member Login.)-->
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
				
				<?php if(!$user_info[0]['bank_emp_id']){?>
										<div class="form-group">
                                            <label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="bank_emp_id" name="bank_emp_id" placeholder="Employee Id"  value="<?php echo set_value('bank_emp_id');?>"  data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required >
                                                <span class="error">
											       <?php //echo form_error('city');?>
											    </span> 
											</div>
										</div>
										<?php }else{ ?>
										    <div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00">*</span></label>
											<div class="col-sm-4">
											<?php echo $user_info[0]['bank_emp_id'];?>
											<input type="hidden" name="bank_emp_id" value="<?php echo $user_info[0]['bank_emp_id'];?> ">
											</div>
											</div>
										<?php } ?>
			
			<?php if(count($bulk_payment_scale_master) > 0){
										$is_flag = 1; ?>
										<?php if(!$user_info[0]['bank_scale']){?>
										<div class="form-group">
                                            <label for="roleid" class="col-sm-3 control-label">Pay Scale <span style="color:#F00">*</span></label>
                                            <div class="col-sm-4">
                                                <select class="form-control" id="bank_scale" name="bank_scale" required>
                                                    <option value="">Select</option>
                                                    <?php if(count($bulk_payment_scale_master) > 0){
                                foreach($bulk_payment_scale_master as $row1){ 	?>
                                                        <option value="<?php echo $row1['id'];?>" <?php echo set_select( 'bank_scale', $row1['id']); ?>>
                                                            <?php echo $row1['pay_scale'];?>
                                                        </option>
                                                        <?php } } ?>
                                                </select>
                                            </div>
										</div>
										<?php }else{ ?>
										<div class="form-group">
												<label for="roleid" class="col-sm-3 control-label">Pay Scale <span style="color:#F00">*</span></label>
													<div class="col-sm-4">
													<?php if(count($bulk_payment_scale_master) > 0){foreach($bulk_payment_scale_master as $row1){ ?><?php if($user_info[0]['bank_scale']==$row1['id']){echo $row1['pay_scale'];}?><?php } } ?>
													 <input type="hidden" name="bank_scale" value="<?php echo $user_info[0]['bank_scale']; ?>" >
													</div>
										</div>
										<?php } ?>
									<?php } else { ?>
										<input type="hidden" name="bank_scale" value="" >
									<?php } ?>
									
				<?php if(count($bulk_zone_master) > 0){
										$is_flag = 1; ?>
										<?php if(!$user_info[0]['bank_zone']){?>
										<div class="form-group">
                                            <label for="roleid" class="col-sm-3 control-label">Bank Zone <span style="color:#F00">*</span></label>
                                            <div class="col-sm-4">
                                                <select class="form-control" id="bank_zone" name="bank_zone" required>
                                                    <option value="">Select</option>
                                                    <?php if(count($bulk_zone_master) > 0){
                                foreach($bulk_zone_master as $row1){ 	?>
                                                        <option value="<?php echo $row1['zone_id'];?>" <?php echo set_select( 'bank_zone', $row1['zone_id']); ?>>
                                                            <?php echo $row1['zone_code'];?>
                                                        </option>
                                                        <?php } } ?>
                                                </select>
                                            </div>
										</div>
									<?php }else{ ?>
										<div class="form-group">
												<label for="roleid" class="col-sm-3 control-label">Bank Zone <span style="color:#F00">*</span></label>
													<div class="col-sm-4">
													<?php if(count($bulk_zone_master) > 0){foreach($bulk_zone_master as $row1){ ?><?php if($user_info[0]['bank_zone']==$row1['zone_id']){echo  $row1['zone_code'];}?>
														<?php } } ?>
													<input type="hidden" name="bank_zone" value="<?php echo $user_info[0]['bank_zone']; ?>" >
													</div>
										</div>
										<?php } ?>
									<?php } else { ?>
									<input type="hidden" name="bank_zone" value="" >
									<?php } ?>	
				
               </div> <!-- Basic Details box closed-->
		</div>
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
          
          
                <div class="form-group">
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
                </div>
                
                <div class="form-group">
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
                </div>
                
                
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
                       <button type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit"  onclick="javascript:return spl_member_apply_exam()">Preview</button>
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                     <button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button>
                     <a href="<?php echo base_url();?>ApplySplexamM/examdetails/?ExId=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>
                    </div>
              </div>
            </div>
     </div>
  
   
 
  
     </form>
	 <?php }else{ ?>
	 <div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Basic Details</h3>		
					</div>
					<div class="box-body">
					<form class="form-horizontal"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApply/add_member/">
  
					<div class="form-group">
                            	<label class="col-sm-3 control-label">Membership No :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control"  name="regnumber" placeholder="Registration no"  value="" required>
								</div><button name="getdata" >Get Details</button>
					</div>                                    
    </form>    
</div>	
</div>	
	 <?php } ?>
		 </div>
                
               
		</div>
	  </section>
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