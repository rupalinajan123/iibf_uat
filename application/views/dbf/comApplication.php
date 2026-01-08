<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> </h1>
    <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>--> 
  </section>
  
  <!-- <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php //echo base_url();?>Dbf/preview/"> -->
  
  <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Dbf/comApplication/">
    <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('dbregid');?>">
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
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } 
			 if(validation_errors()!=''){?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
                <?php echo validation_errors(); ?> </div>
              <?php } 
			 ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                <div class="col-sm-1"> <?php echo $user_info[0]['regnumber'];
					  $fee_amount=$grp_code='';?>
					  <input type="hidden" name="optval" value="<?php if(isset($_GET['optval']) && $_GET['optval']!='') echo $_GET['optval']; else 0; ?>"> <!-- priyanka d 24-01-23 --> 
                  
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
                <div class="col-sm-3"> <?php echo $user_info[0]['firstname'];?> <span class="error">
                  <?php //echo form_error('firstname');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5"> <?php echo $user_info[0]['middlename'];?> 
                  <!--    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $user_info[0]['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >--> 
                  <span class="error">
                  <?php //echo form_error('middlename');?>
                  </span> </div>
                <!--(Max 30 Characters) --> 
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5"> <?php echo $user_info[0]['lastname'];?> 
                  <!--<input type="text" class="form-control" id="middlename" name="lastname" placeholder="Last Name"  value="<?php echo $user_info[0]['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >--> 
                  <span class="error">
                  <?php //echo form_error('lastname');?>
                  </span> </div>
                <!--(Max 30 Characters) --> 
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone : STD Code </label>
                <div class="col-sm-2"> <?php echo $user_info[0]['stdcode'];?> <?php echo $user_info[0]['office_phone'];?> <span class="error">
                  <?php //echo form_error('stdcode');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-dbfeditmobilecheck required data-parsley-trigger-after-failure="focusout" >
                  <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                  <span class="error">
                  <?php //echo form_error('mobile');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-dbfeditemailcheck  type="text" data-parsley-trigger-after-failure="focusout" >
                  <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                  <span style="color:#F00;font-size:small;">(Please check correctness of your Email id and Mobile number. Please change the same here if required. Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail.)</span> <span class="error">
                  <?php //echo form_error('email');?>
                  </span> </div>
              </div>
            </div>
			<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">College/Educational Institute you belong to <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <select name="collage_institute" id="collage_institute" class="form-control" required>
                    <option value="">Select</option>
					<?php
					$dbf_collages            = $this->master_model->getRecords('dbf_collages');
					foreach($dbf_collages as $dbf_collage) {
						?>
						<option value="<?php echo $dbf_collage['collage_name'] ?>"><?php echo $dbf_collage['collage_name'] ?></option>
						<?php 
					}
					?>
                    
					<option value="Others">Others</option>

                  </select>
				 
                </div>
              </div>
			  <div class="form-group" id="other_collage_institute_div" style="display:none;">
                <label for="roleid" class="col-sm-3 control-label">Other Institute <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                 
				  <input class="form-control" id="other_collage_institute" name="other_collage_institute" placeholder="Other" value=""  type="text">
                </div>
              </div>
          </div>
          <!-- Basic Details box closed-->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-5 "> <?php echo $examinfo['0']['description'];?>
                  <div id="error_dob"></div>
                  <br>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
              </div>
              
               
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                <div class="col-sm-5 " id="html_fee_id">
                  <div style="color:#F00">select center first</div>
                  <?php //if($examinfo['0']['fees']==''){echo '-';}else{echo $examinfo['0']['fees'];}?>
                  <div id="error_dob"></div>
                  <br>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
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
                  <br>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
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
                <div class="col-sm-2">
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
					$sql = $this->master_model->getRecords('exam_master','','elearning_flag,sub_el_count'); 
					if($sql[0]['elearning_flag'] == 'Y'){
				?>
                
                <div class="form-group" style="display:none;">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label> 
                        <div class="col-sm-3">
                       
                           <input type="radio" name="elearning_flag" id="elearning_flag_Y" value="Y" >YES
						   <input type="radio" name="elearning_flag" id="elearning_flag_N" value="N" checked="checked">NO
						   
                        </div> 
                 </div>
                 <?php }else{?>
                 <input type="hidden" name="elearning_flag" id="elearning_flag_Y" value="N" >
				 <input type="hidden" name="elearning_flag" id="elearning_flag_N" value="N" >
                 <?php }?>
				 <?php if(in_array($examinfo['0']['exam_code'],$this->config->item('skippedAdmitCardForExams'))) {

					?> 

					<!-- <div class="form-group div_subject "><label for="roleid" class="col-sm-4 control-label">Eligible Subject</span></label>
					<div class="form-group div_subject "><label for="roleid" class="col-sm-1 control-label">Exam Date</span></label> -->
					<div class="col-md-12">
            <div class=" form-group  ">
            <div class="col-md-1">&nbsp;</div>
                        <label for="roleid"  style="text-align: left;" class="control-label col-md-4"><strong>Eligible Subject</strong></span></label>
                        <label for="roleid"  style="text-align: left;" class="control-label col-md-1"><strong>Exam Date</strong></span></label>
                      </div>
					<?php 
					foreach($compulsory_subjects as $subject)
					{ 
					if(in_array($examinfo['0']['exam_code'],$this->config->item('skippedAdmitCardForExams'))) {
						?>
						
						<div class=" form-group  ">
						<div class="col-md-1">&nbsp;</div>
                        <label for="roleid"  style="text-align: left;" class="control-label col-md-4"><?php echo $subject['subject_description']?></span></label>
                        <label for="roleid"  style="text-align: left;" class="control-label col-md-1"><?php echo date('d-m-Y',strtotime($subject['exam_date']))?></span></label>
                      </div>
						<?php 
						} 

					}
					?> </div></div>
					<?php 
					} ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Code <span style="color:#F00">*</span></label>
                <div class="col-sm-2">
                  <input type="text" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly"
                       value="">
                </div>
              </div>
               <?php 
				   if(count($compulsory_subjects) > 0)
				   {
					   $i=1;
					   foreach($compulsory_subjects as $subject)
					   {?>
                            <div class="form-group div_subject" <?php if(in_array($examinfo['0']['exam_code'],$this->config->item('skippedAdmitCardForExams'))) echo'style="display:none;"'?>>
                          	  <label for="roleid" class="col-sm-3 control-label"><?php echo $subject['subject_description']?><span style="color:#F00">*</span></label>
                                <div class="col-sm-2">
                                <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>
                                <select  name="venue[<?php echo $subject['subject_code']?>]" id="venue_<?php echo $i;?>" class="form-control venue_cls" required  onchange="venue(this.value,'date_<?php echo $i;?>','time_<?php echo $i;?>','<?php echo $subject['subject_code']?>','seat_capacity_<?php echo $i;?>');"  attr-data='<?php echo $subject['subject_code']?>'>
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
				 }
				 
				 			
				 
				 ?>
                 
                 
                 <?php 
				 	if($sql[0]['sub_el_count'] == 'Y'){
						$subject_cnt = count($compulsory_subjects);
						$subject_cnt_arr = array('subject_cnt'=>$subject_cnt);
                		$this->session->set_userdata($subject_cnt_arr);
				 ?>
                 <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to select eLearning <br> (Rs. 100/- per subject)</label>
                        <div class="col-sm-3">
                       	<input type="radio" name="elearning_flag" id="subject_elearning_flag_Y" value="Y" checked="checked">YES
						   <input type="radio" name="elearning_flag" id="subject_elearning_flag_N" value="N" >NO
                        </div>
                 </div>
                 <?php foreach($compulsory_subjects as $el_subject){?>
                 
                   <div class="form-group show_el_subject" >
                    <label for="roleid" class="col-sm-3 control-label"><?php echo $el_subject['subject_description']?><span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                       	<input type="checkbox" name="el_subject[<?php echo $el_subject['subject_code']?>]" value="Y" checked="checked" class="el_sub_prop" />
                        </div>
                 </div>
                 <?php }}else{ ?>
				<!--<input type="hidden" name="elearning_flag" value="N" id="subject_elearning_flag_Y" />
                <input type="hidden" name="elearning_flag" value="N" id="subject_elearning_flag_N" />-->
                <input type="hidden" name="el_subject[]" value="N"  class="el_sub_prop" />
				<?php }?>
             
			  

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
              
             
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Photo</label>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('dbregnumber'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" ></label>
                  <span class="error">
                  <?php //echo form_error('gender');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('dbregnumber'),'s');?><?php echo '?'.time(); ?>" height="100" width="100"></label>
                  <span class="error">
                  <?php //echo form_error('gender');?>
                  </span> </div>
              </div>
              <?php if(count($caiib_subjects) > 0)
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
                  <input class="form-control" id="pincode_place_of_work" name="pincode_place_of_work" placeholder="Pincode/Zipcode" required  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-editcheckpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout"  type="text">
                  <span class="error">
                  <?php //echo form_error('pincode');?>
                  </span> </div>
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
              
              
              
              <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"></label>
                If your above Photo/Signature is not clear, Pl upload another Photo/Signature using the below given link. <span class="error">
                <?php //echo form_error('gender');?>
                </span> </div>-->
              <?php 
				/*if(!file_exists('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) ||$user_info[0]['scannedphoto']=='' || $user_info[0]['scannedsignaturephoto']=='')
			{*/
			if(!is_file(get_img_name($this->session->userdata('dbregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('dbregnumber'),'p')))
			{?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Photo</label>
                <div class="col-sm-5">
                  <input  type="file" class="" name="scannedphoto" id="scannedphoto">
                  <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                  <div id="error_photo"></div>
                  <br>
                  <div id="error_photo_size"></div>
                  <span class="photo_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('scannedphoto');?>
                  </span> </div>
                <img id="image_upload_scanphoto_preview" height="100" width="100"/> </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                <div class="col-sm-5">
                  <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto">
                  <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                  <div id="error_signature"></div>
                  <br>
                  <div id="error_signature_size"></div>
                  <span class="signature_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('scannedsignaturephoto');?>
                  </span> </div>
                <img id="image_upload_sign_preview" height="100" width="100"/> </div>
              <?php 
			}?> 
				<?php /*?><div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Are you a person with benchmark disability of 40% or above (PwBD) <span style="color:#F00">*</span></label> 
                        <div class="col-sm-3">
                       
                           <input type="radio" name="scribe_flag_d" id="scribe_flag_d" value="Y" onclick="showSelect();" >YES
						   <input type="radio" name="scribe_flag_d" id="scribe_flag_d" value="N" onclick="hideSelect();" checked="checked">NO
						   
                        </div>
						
                         </div>
						 <div id="disability" style="display:none">
				<div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Type of Disability <span style="color:#F00">*</span></label> 
                        <div class="col-sm-3">
						
								<select id="disability_value" name="disability_value" class="form-control"onChange="getsub_menue(this.value);">
				<option value="">--Select--</option>				
								<?php if(!empty($scribe_disability))
								{
								foreach($scribe_disability as $option)
								{
								?>
								<option value="<?php echo $option['code']?>"><?php echo $option['disability']?></option>
								<?php }}?>
								
								</select> 
								</div>
						 </div>
						  </div>
						       
                                    <div class="form-group" style="display:none;" id="showdept_dropdown">
                                 <label for="roleid" class="col-sm-3 control-label">Sub Type of Disability <span style="color:#F00">*</span></label> 
								  <div class="col-sm-3">
									   <div id="textTraining_type"></div>
                                    </div>
									 </div>
									<?php */?>
					
				
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
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/v_'.$this->session->userdata('dbregnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
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
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/o_'.$this->session->userdata('dbregnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
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
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/c_'.$this->session->userdata('dbregnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
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
                    <label for="roleid" class="col-sm-3 control-label">Do you intend to use <br />the services of a scribe ? <span style="color:#F00">*</span></label> 
					<?php if($benchmark_disability_info[0]['benchmark_disability']=='Y'){ ?>
                        <div class="col-sm-3">
                       
                           <input type="radio" name="scribe_flag" id="scribe_flag" value="Y" onclick="showSelect_scribe_flagY();">YES
						   <input type="radio" name="scribe_flag" id="scribe_flag" value="N" onclick="showSelect_scribe_flagN();" checked="checked">NO
						   
                        </div>
						<?php }  else { ?>
							<div class="col-sm-3">
							<input type="radio" name="scribe_flag" id="scribe_flag" value="N" onclick="showSelect_scribe_flagN();" checked="checked">NO
							</div>
							<?php } ?>
                         </div>
            
              <div class="form-group">
               <div class="col-sm-12">
        <img src="<?php echo base_url()?>assets/images/bullet2.gif">        It is mandatory for a candidate to opt the examination centre being his/her place of work. If there is no centre at his/her place of work, shall have to opt the centre nearest to his/her place of work. Result of candidate violating this rule or giving wrong information is liable for cancellation.<br>
           <br />       <!--B) Since the Institute will not be sending the Admit Letter(hard copy) through post, Correct E-mail address is mandatory for receipt of Admit Letter/Hall Ticket through e-mail.--> 
                  <span class="error">
                  <?php //echo form_error('gender');?>
                  </span> </div>
              </div>
              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-3"> 
                  <!-- <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return login_dbf_checkform();" id="preview">Preview</a> -->
                  
                  <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Preview" onclick="javascript:return login_dbf_checkform();">
                  
                  <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">--> 
                  <a href="<?php echo base_url();?>Dbf/comApplication/" class="btn btn-info" id="Reset">Reset</a> 
                  <!--<button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button>--> 
                  <a href="<?php echo base_url();?>Dbf/examdetails/?excode2=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4></strong></center>
      </div>
      <div class="modal-body">
    <img src="<?php echo base_url()?>assets/images/bullet2.gif"> The candidate should send a scan copy of the DECLARATION as given in the Annexure-I duly completed and to email iibfwzmem@iibf.org.in. Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.<br /><br />
			<p style="color:#F00">Click here to download the declaration form <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_Rev.pdf" download target="_blank">Scribe_Guideliness_Rev.pdf</a></p>	 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
<!-- start jaiib new changes - priyanka D -02-jan-23 -->
<?php
 if(isset($showOptForJaiib) && $showOptForJaiib==1 && !isset($_GET['optval'])) { ?>
	<div class="modal fade " id="myModal_jaiib" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
		<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
			
			<center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00">DBF Exam May/June - 2024</h4></strong></center>
			</div>
			<div class="modal-body">
			
			<button type="button" class="btn btn-info goAsFresher" >Forgo Credits and register de-novo</button>&nbsp;&nbsp;
			<button type="button" class="btn btn-info continueAsOld" >Avail credits(as applicable) with Balance attempts</button>
			<br><i style="color:red;">(Select any one)</i>
		</div>
			
			</div>
		</div>
	</div> 
<?php } ?>
<!--       end jaiib new changes - priyanka D -02-jan-23 --> 

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
 //<span style="color:#F00">*</span> function loginusercheckform()
 // {$('#member_conApplication').parsley().validate();}<span style="color:#F00">*</span>/
</script> 
<script>
$(document).ready(function(){

	
	$("#collage_institute").change(function(){ //priyanka d >> 26-july-23
		if($('#collage_institute').val()=='Others') {
			$('#other_collage_institute').attr('required','required');
			$('#other_collage_institute_div').show();
		}
		else {
			$('#other_collage_institute').removeAttr('required');
			$('#other_collage_institute_div').hide();
		}
			
	});
		if($('#myModal_jaiib').length > 0) {
        	
			$('.goAsFresher').click(function(){
				if (!confirm('You have selected Forgo Credits and register de-novo. Do you want to continue with the same? ')) 
					{
						
					}
					else
						setAsFresherOrOld(1);
				
			
			}); 
			$('.continueAsOld').click(function(){
				if (!confirm('You have selected Avail credits (as applicable) with Balance attempts . Do you want to continue with the same? ')) 
					{
						
					}
					else
						setAsFresherOrOld(2);
			
			}); 
		
			function setAsFresherOrOld(selectedoptVal=2) {

				
				$.ajax({
					type: 'POST',
					url: site_url+'Dbf/getsetAsFresherOrOld/?method=set&optVal='+selectedoptVal,
					success: function(res)
					{	
						//alert(res);
						window.location.href = "<?php echo base_url();?>/Dbf/comApplication/?optval="+res;
					}
				});
			}

			function getAsFresherOrOld() {

				
				$.ajax({
					type: 'POST',
					url: site_url+'Dbf/getsetAsFresherOrOld/?method=get',
					success: function(res)
					{	
						if(res!='')
							window.location.href = "<?php echo base_url();?>/Dbf/comApplication/?optval="+res;
						else
						window.location.href = "<?php echo base_url();?>/Dbf/comApplication/";
					}
				});
			}
			//alert(<?php echo $this->session->userdata('selectedoptVal') ?>);
			<?php 
			if(!isset($_GET['optval']) && $this->session->userdata('selectedoptVal')!='') {
				?>
				
			//	getAsFresherOrOld();
				<?php 
			} else {
				?>
				$('#myModal_jaiib').modal({
					backdrop: 'static'
				});
				<?php
			} ?>
		} //priyanka d - 23-feb-23

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
	
	
	
	//priyanka d - 08-feb-23 >> changeFeeFromElarningY >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated
	if($("#subject_elearning_flag_Y").length > 0 && $('#subject_elearning_flag_Y').is(':checked') && getCookie('sotredPreivousValues')!=1) {
		changeFeeFromElarningY();
	}
	$("#subject_elearning_flag_Y").click(function(){
		changeFeeFromElarningY();
	});
	function changeFeeFromElarningY(){
		$(".loading").show();
		$(".show_el_subject").show();
		$(".el_sub_prop").prop('checked', true);
		
		var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
		var datastring_1='subject_cnt='+el_subject_cnt;
		
		$.ajax({
				url:site_url+'Dbf/set_dbf_elsub_cnt/',
				data: datastring_1,
				type:'POST',
				async: false,
				success: function(data) {
				}
			});
		
		
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
		var Eval = 'N'; 
		
		if(document.getElementById('elearning_flag_Y').checked){
			var Eval = document.getElementById('elearning_flag_Y').value;
		}

		if(document.getElementById('elearning_flag_N').checked){
			var Eval = document.getElementById('elearning_flag_N').value;
		}
		
		var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;;
		
		

		$.ajax({
				url:site_url+'Fee/getFee/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				if(data){
					document.getElementById('fee').value = data ;
					document.getElementById('html_fee_id').innerHTML =data;
				}
			}
		});
		
		$(".loading").hide();
	}
	//priyanka d - 08-feb-23 >> changeFeeFromElarningN >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated
	if($("#subject_elearning_flag_N").length > 0 && $('#subject_elearning_flag_N').is(':checked') && getCookie('sotredPreivousValues')!=1) {
		changeFeeFromElarningN();
	}
	$("#subject_elearning_flag_N").click(function(){
		changeFeeFromElarningN();
	});
	function changeFeeFromElarningN(){ 
		$(".loading").show();
		$(".show_el_subject").hide();
		$(".el_sub_prop").prop('checked', false);
		
		var el_subject_cnt = 0;
		
		var datastring_1='subject_cnt='+el_subject_cnt;
		
		$.ajax({
				url:site_url+'Dbf/set_dbf_elsub_cnt/',
				data: datastring_1,
				type:'POST',
				async: false,
				success: function(data) {
				}
			});
		
		
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
		var Eval = 'N'; 
		
		if(document.getElementById('elearning_flag_Y').checked){
			var Eval = document.getElementById('elearning_flag_Y').value;
		}

		if(document.getElementById('elearning_flag_N').checked){
			var Eval = document.getElementById('elearning_flag_N').value;
		}
		
		var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;;
		
		

		$.ajax({
				url:site_url+'Fee/getFee/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				if(data){
					document.getElementById('fee').value = data ;
					document.getElementById('html_fee_id').innerHTML =data;
				}
			}
		});
		$(".loading").hide();
	}
	//priyanka d - 08-feb-23 >> el_sub_prop >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated
	if(getCookie('sotredPreivousValues')!=1)
		el_sub_prop();

	$(".el_sub_prop").click(function(){
		el_sub_prop();
	});

	function el_sub_prop() {
		$(".loading").show();
		var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
		var datastring_1='subject_cnt='+el_subject_cnt;
		
		$.ajax({
				url:site_url+'Dbf/set_dbf_elsub_cnt/',
				data: datastring_1,
				type:'POST',
				async: false,
				success: function(data) {
				}
			});
		
		
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
		var Eval = 'N'; 
		
		if(document.getElementById('elearning_flag_Y').checked){
			var Eval = document.getElementById('elearning_flag_Y').value;
		}

		if(document.getElementById('elearning_flag_N').checked){
			var Eval = document.getElementById('elearning_flag_N').value;
		}
		
		var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;;
		
		

		$.ajax({
				url:site_url+'Fee/getFee/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				if(data){
					document.getElementById('fee').value = data ;
					document.getElementById('html_fee_id').innerHTML =data;
				}
			}
		});
		$(".loading").hide();
	}
	//priyanka d - 08-feb-23 >> setCookie,getCookie >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated
	$( "form#member_conApplication" ).submit(function() {
		if (!confirm('Please check your application details carefully before proceeding for payment ')) 
					{
						
						return false;
					}
		setCookie('sotredPreivousValues',1);
		//alert(getCookie('sotredPreivousValues'));
		var currform=$(this);
		currform.find('input').each(function() {
			
			var setcookiename=$( this ).attr('name');
			var setcookieval=$(this).val();
			setCookie(setcookiename,setcookieval);
		});
		currform.find('input[type="radio"]:checked').each(function() {
			//if($(this).is(':checked')) {
				var setcookiename=$( this ).attr('name');
				var setcookieval=$(this).val();
				setCookie(setcookiename,setcookieval);
			//}
		//	alert(setcookiename+'=='+getCookie(setcookiename));
		});
		currform.find('select').each(function() {
			
			var setcookiename=$( this ).attr('name');
			var setcookieval=$(this).val();
			setCookie(setcookiename,setcookieval);
			
		});
		currform.find('input[type="checkbox"]').each(function() {
			var setcookiename=$( this ).attr('name');
			setCookie(setcookiename,'');
			if($(this).is(':checked')) {
				
				var setcookieval=$(this).val();
				setCookie(setcookiename,setcookieval);
				//alert(setcookiename+'=='+getCookie(setcookiename));
			}
		});
	});
	setTimeout(function(){
		if(getCookie('sotredPreivousValues')==1 ) {
			if($('#excd').val()==getCookie('excd') && $('#reg_no').val()==getCookie('reg_no')) {
				//$('.content').attr('style','opacity: 0.5;');
				var currform=$( "form#member_conApplication" );
				currform.find('input[type="text"]:visible').each(function() {
					
					var getcookiename=$( this ).attr('name');
					//alert(getcookiename+'=='+getCookie(getcookiename));
					$(this).val(getCookie(getcookiename));
					setCookie(getcookiename,'');
				});
				currform.find('input[type="tel"]:visible').each(function() {
					
					var getcookiename=$( this ).attr('name');
					//alert(getcookiename+'=='+getCookie(getcookiename));
					$(this).val(getCookie(getcookiename));
					setCookie(getcookiename,'');
				});
				currform.find('input[type="radio"]:visible').each(function() {
					
					$(this).prop( "checked", false ); 
					var getcookiename=$( this ).attr('name');
					
					if(getCookie(getcookiename)!='') {
						
						if($(this).attr('value')==getCookie(getcookiename)) {
						//	alert(getcookiename+'=='+getCookie(getcookiename));
							$(this).prop( "checked", true ).trigger('click');
						//	alert(getcookiename+'=='+getCookie(getcookiename));
							setCookie(getcookiename,'');
						}
					}
					
						
				});
				currform.find('select:visible').each(function() {
					
					var getcookiename=$( this ).attr('name');
					//alert(getcookiename+'=='+getCookie(getcookiename));
					$(this).val(getCookie(getcookiename)).trigger('change');

					setCookie(getcookiename,'');
				});
				setTimeout(function(){
					currform.find('input[type="checkbox"]:visible').each(function() {
						
						var getcookiename=$( this ).attr('name');

						if(getCookie(getcookiename)!='') {

							if($(this).attr('value')==getCookie(getcookiename))
								$(this).prop( "checked", true );
								
							
						}
						else
						$(this).prop( "checked", false );
						//alert(getcookiename+'=='+getCookie(getcookiename));
						setCookie(getcookiename,'');
						//alert(getcookiename+'=='+getCookie(getcookiename));
					});
					//alert('here');
					el_sub_prop();
				}, 2000);
			}
			setCookie('sotredPreivousValues',0);
			$('.content').attr('style','opacity: 1;');
		}
	}, 50);
	
	
});

if(getCookie('sotredPreivousValues')==1) {
			$('.content').attr('style','opacity: 0.5;');
}
function setCookie(cname, cvalue, exdays) {
	const d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	let expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

function getCookie(cname) {
	let name = cname + "=";
	let ca = document.cookie.split(';');
	for(let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
		c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
		return c.substring(name.length, c.length);
		}
	}
	return "";
	}

function showSelect_scribe_flagY() {

$('#myModal').modal('show');
}

function showSelect_scribe_flagN() {

$('#myModal').modal('hide');
}
/*function showSelect() {

$("#disability").show();

//$('#disability').attr("required","true");

$("#Sub_menue").attr("required","true");
$("#disability_value").attr("required","true");
$("#Sub_menue").show();
$("#scribe_flag").removeAttr("disabled");
 
}
function hideSelect() {

$("#disability").hide();
$("#Sub_menue").hide();
$("#showdept_dropdown").hide();
$("#disability_value").removeAttr("required"); 
$("#Sub_menue").removeAttr("required");
$("#disability_value").css('display','block');
$("#Sub_menue").val("");
$("#disability_value").val("");
$("#scribe_flag").attr("disabled","true");
$("#scribe_flag").attr('checked',false);
$("#scribe_flag").attr("required","true");
//$('#disability').removeAttr("required");
//$('#Sub_menue').removeAttr("required")


}*/

 var base_url = '<?php echo base_url();?>'
   function getsub_menue(deptid)
   {
   			$.ajax({
   		type:"POST",
   		url: base_url+"Dbf/getsub_menue",
   		data:{deptid:deptid},
   		success:function(data){
   			if(data != "")
   			{   
   					$("#showdept_dropdown").show();
   					$("#textTraining_type").text('');
   					$("#textTraining_type").append(data);
				    $("#Sub_menue").attr("required","true");
   				    $("#showdept").hide();
				
   			}
   			else{
				$("#Sub_menue").removeAttribute("required"); 
   				$("#showdept_dropdown").hide();
   				$("#showdept").show();
				
			
   			}
   		}	
   	},"json");
   }
</script>