<style>
  /*Cropper Image Editor*/
    #optionsModal > .modal-dialog, #cropModal > .modal-dialog { max-width: 600px; }
    #optionsModal > .modal-dialog h4.modal-title, #GuidelinesModal > .modal-dialog h4.modal-title, #cropModal > .modal-dialog h4.modal-title { text-align: center; }

    #GuidelinesModal > .modal-dialog { max-width: 800px; }
  /*Cropper Image Editor*/
</style>

<style>
.bordered-container {
    width: 960px;
    margin: 0 auto;
}
.bordered-row {
    display: inline-block;
    align-items: center;
    width: 100%;
    border: 1px solid #ddd;
    margin-bottom: 0;
}
    .bordered-cell {
        flex: 1;
        padding: 8px;
        text-align: left;
    }
    .bordered-row:last-child .bordered-cell {
        border-bottom: none;
    }
    .bordered-cell:last-child {
        border-right: none;
    }
    .header {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .bordered-container {
    width: 960px;
    margin: 0 auto;
}

.blink-highlight {
  background-color: #ffb300;
  animation: blink 1s step-start 0s infinite;
}

@keyframes blink {
  50% {
    background-color: transparent;
  }
}
</style>

<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>Eligible Subjects
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
    
      <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>NonMember/comApplication/">
    
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('nmregid');?>"> 
    <section class="content">
      <div class="row">
       
        <!-- start exemption new changes - priyanka D -26-june-24 -->
        <?php
        if(isset($showExemptionOption) && $showExemptionOption==1 ) { ?>
          <div class="col-md-12" id="myModal_exemption1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-bottom: 3%;     font-size: 0px;">
            <div class="">
              <div class="modal-content">
              <div class="modal-header">
              
              <strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00">Please refer to the Syllabus and Rules pertaining to the Certificate in Risk and Financial Services Level 1. In case you satisfy the eligibility criteria for exemption from Level 1 Examination  </h4></strong>
              </div>
              <div class="modal-body">
              &nbsp;&nbsp;
              <center>
              <button type="button" class="btn btn-info takenexemption" >Continue with Exemption </button>
              <!--<button type="button" class="btn btn-info continuetoapplication" >Continue with application</button>-->
              </center>
            </div>
              
              </div>
            </div>
          </div> 
        <?php } ?>
        <!--       end exemption new changes - priyanka D -26-june-24 --> 
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
              if(isset($examinfo[0]['app_category']) && $examinfo[0]['app_category'] != '' && $examinfo[0]['app_category'] != null)
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
                
                <?php  if($this->session->userdata('nm_without_pass') && ($this->session->userdata('nm_without_pass') == 1)){
                  ?><div class="col-sm-12">
                  <b>Note : In order to check your profile details, you need to login  with your membership number and password</b> </div>
                  <input type="hidden" name="mobile" value="<?php echo $user_info[0]['mobile'];?>">
                  <input type="hidden" name="email" value="<?php echo $user_info[0]['email'];?>">

                  <?php  
                 }
                else { ?>
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
                        <span style="color:#F00;font-size:small;">(Please check correctness of your Email id and Mobile number. Please change the same here if required. Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail.)</span>
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>

                <?php } ?>
                
                </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            
         

            <div class="box-body">

              <!-- start and add the below field institute name by gaurav -->

              <input type="hidden" name="is_fedai_emp_declaration_valid"  value="<?php echo $is_fedai_emp_declaration_valid; ?>">

                <?php if( isset($AssociateInstituteId) && $AssociateInstituteId !='' && $AssociateInstituteId != null && count($sel_institute_data) > 0 && $this->session->userdata('examcode') == 1009 ) { ?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Institute name</label>
                    <div class="col-sm-5 ">
                      <?php echo $sel_institute_data[0]['name'];?>
                      <div id="error_dob"></div>
                    </div>
                  </div>
                  <input type="hidden" name="institutionworking" value="<?php echo $AssociateInstituteId; ?>">


                  <!-- <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Employee Id proof</label>
                    <div class="col-sm-2">
                      <label for="roleid" class="col-sm-3 control-label">
                      <?php 
                        if(is_file(get_img_name($this->session->userdata('nmregnumber'),'empr')))
                        {?>
                          <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('nmregnumber'),'empr');?><?php echo '?'.time(); ?>" height="100" width="100" >
                        <?php 
                        }
                        else
                        {?>
                        <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                        <?php 
                        }?>
                      </label>
                      <span class="error"></span>
                    </div>
                  </div>
                
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Declaration</label>
                    <div class="col-sm-2">
                     <label for="roleid" class="col-sm-3 control-label">
                      <?php 
                        if(is_file(get_img_name($this->session->userdata('nmregnumber'),'declaration')))
                      {?>
                        <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('nmregnumber'),'declaration');?><?php echo '?'.time(); ?>" height="100" width="100">
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
                  </div> -->


                <?php } elseif ( ($AssociateInstituteId == '' || $AssociateInstituteId == null) && $this->session->userdata('examcode') == 1009 ) { ?>

                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Institute name</label>
                    <div class="col-sm-5">
                      <select id="institutionworking" name="institutionworking" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php if (count($fedai_institute_data)) {
                          foreach ($fedai_institute_data as $fedai_institution_row) {   ?>
                            <option value="<?php echo $fedai_institution_row['institude_id']; ?>" <?php echo set_select('institutionworking', $fedai_institution_row['institude_id']); ?>> <?php echo $fedai_institution_row['name']."(".$fedai_institution_row['institude_id'].")"; ?>  
                            </option>
                      <?php }
                        } 
                      ?>
                      </select>
                    </div>
                  </div>

                  <?php }
                  else
                  {
                  if($this->session->userdata('examcode') == 1009) {
                ?>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Institute name</label>
                  <div class="col-sm-5 ">
                    Institute Not found
                    <div id="error_dob"></div>
                  </div>
                </div>
                </div>
                <?php } } ?> 
                <!-- End the code -->


                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                  <div class="col-sm-5 ">
                        <?php 
                        $cust_exam_name = '';
                        /*if($this->session->userdata('examcode') == "1046"){
                          $cust_exam_name = '(BCBF Advanced)';
                        }else if($this->session->userdata('examcode') == "1047"){
                          $cust_exam_name = '(BCBF Basic)';
                        }*/ 
                        echo $examinfo['0']['description']." ".$cust_exam_name; 
                        ?>
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
                        <?php 
            //echo $fee_amount;
            //if($examinfo['0']['fees']==''){echo '-';}else{echo $examinfo['0']['fees'];}?>
                        
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>

                <!-- START: FOR IMAGE EDITOR -->
              <?php $data_lightbox_title_common = "Non Member Registration"; ?>
              <input type="hidden" name="form_value" id="form_value" value="form_value">
              <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
              <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
              <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">
              <!-- END: FOR IMAGE EDITOR -->

                <?php
              $file_size = '300kb';
              if($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047){
                $file_size = '100kb';
              }
              ?>

                <?php
                if(($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047 || $this->session->userdata('examcode') == 991 || $this->session->userdata('examcode') == 997))
                {
                  ?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Name of Bank where working as BC</label>
                    <div class="col-sm-5">
                      <select id="name_of_bank_bc" onchange="check_bank_bc_id_no();" name="name_of_bank_bc" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php if (count($old_bcbf_institute_data)) {
                          foreach ($old_bcbf_institute_data as $res) {   ?>
                            <option value="<?php echo $res['institute_id']; ?>" <?php echo set_select('name_of_bank_bc', $res['institute_id']); ?>> <?php echo $res['institute_name']; ?>  
                            </option>
                      <?php }
                        } 
                      ?>
                      </select>
                    </div>
                  </div>
                   

                  <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Date of commencement of operations/joining as BC <span style="color:#F00">*</span></label>
                  <input type="hidden" id="dob1" name="dob" required value="<?php echo $user_info[0]['dateofbirth']; ?>">
                  <input type="hidden" id="exam_date_exist" name="exam_date_exist" value="<?php echo date('Y-m-d',strtotime($compulsory_subjects[0]['exam_date']));?>">
                  <div class="col-sm-4 doj">
                    <div class="col-sm-2 example" style="width: 100%;padding-left: 0px;">
                      <input type="hidden" id="doj1" name="date_of_commenc_bc" value="<?php echo $user_info[0]['date_of_commenc_bc']; ?>">
                    </div>
                    <span id="doj_error" class="error"></span>
                  </div>
                </div>

                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Bank BC ID No <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input required type="text" class="form-control" id="ippb_emp_id" name="ippb_emp_id" placeholder="Bank BC ID No" onchange="check_bank_bc_id_no();" value="<?php echo $user_info[0]['ippb_emp_id']; ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                  <span id="ippb_emp_id_error" class="error"></span>
                </div>
              </div>

                <?php
                }
                if ( ($this->session->userdata('examcode') == 1009 || $this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047) ) { ?>
                  <?php /* <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label"><?php echo ( ($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); ?> <span style="color:#f00">**</span></label>
                    <!-- <div class="col-sm-5">
                      <input  type="file" class="" name="empidproofphoto" id="empidproofphoto" required onchange="validateFile(event, 'error_empidproofphoto_size', 'image_upload_empidproof_preview', '300kb')">
                      <input type="hidden" id="hiddenempidproofphoto" name="hiddenempidproofphoto">
                      <span class="note">Please Upload only .jpg, .jpeg Files upto 300KB</span>
                      <div id="error_dob" class="error"></div>
                      <br>
                      <div id="error_empidproofphoto_size" class="error"></div>
                      <span class="dob_proof_text" style="display:none;"></span> 
                      <span class="error">
                        <?php //echo form_error('idproofphoto');?>
                      </span> 
                    </div> -->
                    <img class="mem_reg_img" id="image_upload_empidproof_preview" height="100" width="100" src="/assets/images/default1.png" />
                  </div> */ ?>

                  <div class="form-group"><?php // Upload Your Upload Bank BC ID Card / Employee Id proof  ?>
                    <?php 
                      $image_nm_emp_bank = (($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); 
                      $field_nm_emp_bank = (($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047) ? 'bank_bc_id_card' : 'empidproofphoto');

                      //echo $this->session->userdata('examcode')."==".$AssociateInstituteId;
                    ?>
                    <label for="empidproofphoto" class="col-sm-3 control-label"><?php echo (($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); ?> <span style="color:#F00">*</span></label>
                    <div class="col-sm-5">
                      <div class="img_preview_input_outer pull-left">
                        <input type="file" name="empidproofphoto" id="empidproofphoto" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#empidproofphotoError" />

                        <div class="image-input image-input-outline image-input-circle image-input-empty">
                          <div class="profile-progress"></div>
                          <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('<?php echo $field_nm_emp_bank; ?>', 'member_registration', 'Edit <?php echo $image_nm_emp_bank; ?>');" onblur="validate_form_images('empidproofphoto')"><?php echo $image_nm_emp_bank; ?></button>
                        </div>
                        <note class="form_note" id="empidproofphoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
                        <span id="empidproofphotoError"></span>

                        <input type="hidden" name="<?php echo $field_nm_emp_bank; ?>_cropper" id="<?php echo $field_nm_emp_bank; ?>_cropper" value="<?php echo set_value($field_nm_emp_bank.'_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                        <?php if (form_error('empidproofphoto') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('empidproofphoto'); ?></label> <?php } ?>
                      </div>

                      <div id="<?php echo $field_nm_emp_bank; ?>_preview" class="upload_img_preview pull-right">
                        <?php
                        $preview_empidproofphoto = '';
                        if (set_value($field_nm_emp_bank.'_cropper') != "")
                        {
                          $preview_empidproofphoto = set_value($field_nm_emp_bank.'_cropper');
                        }

                        if ($preview_empidproofphoto != "")
                        { ?>
                          <a href="<?php echo $preview_empidproofphoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo $image_nm_emp_bank; echo $data_lightbox_title_common;?>">
                            <img src="<?php echo $preview_empidproofphoto . "?" . time(); ?>">
                          </a>

                          <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="<?php echo $field_nm_emp_bank; ?>" data-db_tbl_name="member_registration" data-title="Edit <?php echo $image_nm_emp_bank; ?>" title="Edit <?php echo $image_nm_emp_bank; ?>" alt="Edit <?php echo $image_nm_emp_bank; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                        <?php }
                        else
                        {
                          echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                        } ?>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>


                  <?php } 
                    //echo $this->session->userdata('examcode')."==".$AssociateInstituteId;
                  if ( ($AssociateInstituteId == '' || $AssociateInstituteId == null) && $this->session->userdata('examcode') == 1009 ) { 

                ?>

                  <div class="form-group">
                    <label class="col-sm-3 control-label box-title">Declaration Form :</label></span>
                    <div class="col-sm-8">  
                      </span>Mandatorily upload the Declaration form signed(with stamped) by Branch Manager/HOD.</span>
                      <div><a style='color:#FF0000;' href=" <?php echo base_url() ?>uploads/declaration/DECLARATION_1.pdf" target="_blank"><strong style="color:#F00; text-decoration:underline">Please click here to PRINT.</strong></a></div>
                    </div>
                  </div>
                  
                  <?php /* <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Upload your Declaration Form <span style="color:#F00">**</span></label>
                    <div class="col-sm-5">
                      <input type="file" name="declarationform" id="declarationform" required onchange="validateFile(event, 'error_declaration', 'image_upload_declarationform_preview', '300kb')">
                      <input type="hidden" id="hiddendeclarationform" name="hiddendeclarationform">
                      <span class="note">Please Upload only .jpg, .jpeg Files upto 300KB</span></br>
                      <span class="note-error" id="error_declaration" style="color:#b94a48"></span>
                      <br>
                      <div id="error_declaration_size"></div>
                      <span class="declaration_proof_text" style="display:none;"></span> <span class="error">
                        <?php //echo form_error('declarationform');
                        ?>
                      </span>
                    </div>
                    <img class="mem_reg_img" id="image_upload_declarationform_preview" height="100" width="100" src="/assets/images/default1.png" />
                    <div class="col-sm-12"></div>
                  </div> */ ?>

                  <div class="form-group"><?php // Upload Your Declaration Form ?>
                    <label for="declarationform" class="col-sm-3 control-label">Upload Your Declaration Form <span style="color:#F00">*</span></label>
                    <div class="col-sm-5">
                      <div class="img_preview_input_outer pull-left">
                        <input type="file" name="declarationform" id="declarationform" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#declarationformError" />

                        <div class="image-input image-input-outline image-input-circle image-input-empty">
                          <div class="profile-progress"></div>
                          <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('declarationform', 'member_registration', 'Edit Declaration Form');" onblur="validate_form_images('declarationform')">Upload Declaration Form</button>
                        </div>
                        <note class="form_note" id="declarationform_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
                        <span id="declarationformError"></span>

                        <input type="hidden" name="declarationform_cropper" id="declarationform_cropper" value="<?php echo set_value('declarationform_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                        <?php if (form_error('declarationform') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declarationform'); ?></label> <?php } ?>
                      </div>

                      <div id="declarationform_preview" class="upload_img_preview pull-right">
                        <?php
                        $preview_declarationform = '';
                        if (set_value('declarationform_cropper') != "")
                        {
                          $preview_declarationform = set_value('declarationform_cropper');
                        }

                        if ($preview_declarationform != "")
                        { ?>
                          <a href="<?php echo $preview_declarationform . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Upload Declaration Form - '; echo $data_lightbox_title_common;?>">
                            <img src="<?php echo $preview_declarationform . "?" . time(); ?>">
                          </a>

                          <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="declarationform" data-db_tbl_name="member_registration" data-title="Edit Declaration Form" title="Edit Declaration Form" alt="Edit Declaration Form"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                        <?php }
                        else
                        {
                          echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                        } ?>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>


                <?php } ?>
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                  <div class="col-sm-5 ">
                        <?php 
                  //$month = date('Y')."-".substr($examinfo['0']['exam_month'],4)."-".date('d');
                  $month = date('Y')."-".substr($examinfo['0']['exam_month'],4);
                  echo date('F',strtotime($month))."-".substr($examinfo['0']['exam_month'],0,-2); 
                  //echo 'OCT-NOV 2020';
                  ?>
                        <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
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
                
                <? if(count($compulsory_subjects) > 0 && ($this->session->userdata('examcode')==101 || $this->session->userdata('examcode')==1046 || $this->session->userdata('examcode')==1047)){?>
                   <div class="form-group">
                <label style="font-weight: bold;" for="roleid" class="col-sm-3 control-label"><span style="font-weight: bold;background-color: #ffb300;padding: 5px;" class="blink-highlight">Examination Date</span></label>
                  <div class="col-sm-5 ">
                        <span style="font-weight: bold;background-color: #ffb300;padding: 5px;"><?php echo  date('d-M-Y',strtotime($compulsory_subjects[0]['exam_date']));?></span>
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
                
                <label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>
                  <div class="col-sm-2">
                    <select name="selCenterName" id="selCenterName" class="form-control" required onchange="valCentre(this.value);" style="width:250px">
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
        <?php
        $styleForELearning="display:none;";
         if($this->session->userdata('examcode')==1002 || $this->session->userdata('examcode')==1003 || $this->session->userdata('examcode')==1004 || $this->session->userdata('examcode')==1005 || $this->session->userdata('examcode')==1014 || $this->session->userdata('examcode')==1006 || $this->session->userdata('examcode')==1007 || $this->session->userdata('examcode')==1011) {
         // $styleForELearning='';
          
           if($this->session->userdata('examcode')==1002 || $this->session->userdata('examcode')==1003 || $this->session->userdata('examcode')==1004 || $this->session->userdata('examcode')==1005 || $this->session->userdata('examcode')==1006 || $this->session->userdata('examcode')==1007 || $this->session->userdata('examcode')==1011 || $this->session->userdata('examcode')==1014){
            $checked = 'checked="checked"';
            $checked1 = '';
          }
          else{
            $checked = '';
            $checked1 = 'checked="checked"';
          }
        }
        //echo '---'.$this->session->userdata('examcode').'--'.$styleForELearning;
        ?>
        <div class="form-group" style="<?php echo $styleForELearning; ?>"> <!-- priyanka d - 02-feb-23 display none this field as it's duplicate  -->
          <label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label> 
          <div class="col-sm-3">
            <input type="radio" name="" id="elearning_flag_Y" value="Y" <?php echo $checked; ?>>YES
            <input type="radio" name="" id="elearning_flag_N" value="N" <?php echo $checked1; ?>>NO
          </div>
        </div>
                 <?php }else{?>
                 <input type="hidden" name="elearning_flag" id="elearning_flag_Y" value="N" >
         <input type="hidden" name="elearning_flag" id="elearning_flag_N" value="N" >
                 <?php }?>
                
                <!-- skipadmit -->
                <?php
            if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) {    
            ?> 

                <!-- skipadmit -->
                <div class="col-md-12">&nbsp;</div>
                <div class="bordered-container">    
                  <div class="bordered-row header">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="bordered-cell col-md-5"><label for="roleid" style="text-align: left;" class="control-label "><b>Eligible Subjects</b></span></label></div>
                    <div class="bordered-cell col-md-4"><label for="roleid" style="text-align: left;" class="control-label "><b>Exam Date</b></span></label></div>
                  </div>
                  <!-- end skipadmit -->

                <?php 
                    foreach($compulsory_subjects as $subject)
                    { 
                    
                      ?>
                      <div class="bordered-row">
                      <div class="col-md-1">&nbsp;</div>
                        <div class="bordered-cell col-md-5"><label for="roleid" style="text-align: left;" class="control-label "><?php echo $subject['subject_description']?></span></label></div>
                        <div class="bordered-cell col-md-4"><label for="roleid" style="text-align: left;" class="control-label"><?php echo date('d-m-Y',strtotime($subject['exam_date'])) ?></span></label></div>
                      </div>
                        <?php 
                      

                    }
                    ?>
                    </div>
                    <?php 
                    } ?>

            <?php   
           if(count($compulsory_subjects) > 0 && $this->session->userdata('examcode')!=101 && $this->session->userdata('examcode')!=1046 && $this->session->userdata('examcode')!=1047)
           {
             $i=1;
             foreach($compulsory_subjects as $subject)
             {?>
                            <div class="form-group" <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) echo'style="display:none;"'?>>
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
         } ?>

         
             
             <?php 
            //    echo'<pre>';print_r($sql);exit;
          if($sql[0]['sub_el_count'] == 'Y') { 
            /// priyanka d - added this if else both for e-learning field on non member exam form
            $subject_cnt = count($compulsory_subjects);
            $subject_cnt_arr = array('subject_cnt'=>$subject_cnt);
                    $this->session->set_userdata($subject_cnt_arr);
         ?>
                 <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to select eLearning</label>
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
                 <?php }
                  } else { ?>
                <input type="hidden" name="elearning_flag" value="N" id="subject_elearning_flag_Y" />
                <input type="hidden" name="elearning_flag" value="N" id="subject_elearning_flag_N" />
                <input type="hidden" name="el_subject[]" value="N"  class="el_sub_prop" />
        <?php } ?>
             
        

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
              <?php if($this->session->userdata('examcode')!=101 && $this->session->userdata('examcode')!=1046 && $this->session->userdata('examcode')!=1047)
         {
         ?>
                
              <?php 
         }?>          
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Photo</label>
                  <div class="col-sm-2">
                     <label for="roleid" class="col-sm-3 control-label">
                    <?php 
          if(is_file(get_img_name($this->session->userdata('nmregnumber'),'p')))
          {?>
                     <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('nmregnumber'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" >
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
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                  <div class="col-sm-2">
                     <label for="roleid" class="col-sm-3 control-label">
           <?php 
                    if(is_file(get_img_name($this->session->userdata('nmregnumber'),'s')))
                    {?>
                         <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('nmregnumber'),'s');?><?php echo '?'.time(); ?>" height="100" width="100">
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
                                    foreach($states as $row1){  ?>
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
      if(!is_file(get_img_name($this->session->userdata('nmregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('nmregnumber'),'p')))
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
             <!-- Benchamrk Disability Code Start -->
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
          <img src="<?php echo base_url();?><?php echo '/uploads/disability/v_'.$this->session->userdata('nmregnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
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
          <img src="<?php echo base_url();?><?php echo '/uploads/disability/o_'.$this->session->userdata('nmregnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
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
          <img src="<?php echo base_url();?><?php echo '/uploads/disability/c_'.$this->session->userdata('nmregnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
          </div>
        </div>
          <?php
          }
          ?>
      </div>
      <?php 
      }
      ?>
      <!-- Benchamrk Disability Code Close -->
                 <div class="form-group scribe_div" <?php 
        if($benchmark_disability_info[0]['benchmark_disability']=='N')
        {
        ?> style="display:none;"<?php } ?>>
                    <label for="roleid" class="col-sm-3 control-label">Scribe required?</label>
                        <div class="col-sm-3">
                           <input type="checkbox" name="scribe_flag" id="scribe_flag" value="Y">
                        </div>
                    </div>
                <div class="form-group">
              <div class="col-sm-12">
            
                 
                 <?php 
              $exam_code_chk = $this->session->userdata('examcode');
              $exam_arr = array(1001,1002,1003,1004,1005,1009,1013,1014,1006,1007,1008,1011,1012,2027,1019,1020,1058); // 1002,1003,1004,1005,1009,1013,1014
              if(!in_array($exam_code_chk, $exam_arr)){ 
              ?>  
                <img src="<?php echo base_url()?>assets/images/bullet2.gif">    It is mandatory for a candidate to opt the examination centre being his/her place of work. If there is no centre at his/her place of work, shall have to opt the centre nearest to his/her place of work. Result of candidate violating this rule or giving wrong information is liable for cancellation.<br>
              <?php } ?>
<br />
<!--B) Since the Institute will not be sending the Admit Letter(hard copy) through post, Correct E-mail address is mandatory for receipt of Admit Letter/Hall Ticket through e-mail.-->
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>
                </div>
               
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                     
                     <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return login_nm_checkform();" id="preview">Preview</a>-->
                     <?php if( $ButtonDisable ) { ?>
                     <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Preview" onclick="javascript:return login_nm_checkform();">
                     
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                   <a href="<?php echo base_url();?>NonMember/comApplication/" class="btn btn-info" id="Reset">Reset</a>
                   <?php } ?> 
                     <!--<button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button>-->
                     <a href="<?php echo base_url();?>NonMember/examdetails/?excode2=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>
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
<div class="modal fade" id="myModal_EL" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4></strong></center>

      </div>

      <div class="modal-body">
    <img src="<?php echo base_url()?>assets/images/bullet2.gif"> You have opted for e-learning. Login credentials will be provided to you. In case, you do not receive the credentials within three days, please also check your spam folder. If you have still not received the said credentials within three days after registering for the e-learning, please send a mail to care@iibf.org.in.<br /><br />
      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

       

      </div>

    </div>

  </div>

</div>     
     
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4></strong></center>

      </div>

      <div class="modal-body">

    <!--<img src="<?php //echo base_url()?>assets/images/bullet2.gif"> 

  The candidate should send a scan copy of the DECLARATION as given in the Annexure-I duly completed and to email iibfwzmem@iibf.org.in. Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.

<br /><br />

      <p style="color:#F00">Click here to download the declaration form <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_Rev.pdf" download target="_blank">Scribe_Guideliness_Rev.pdf</a></p>-->
      
      Dear Candidate,<br><br>
    <p>You have opted for the services of a scribe for the above mentioned examination under <strong>Remote Proctored mode.</strong> Please note the following - </p> 
    <ul>  
    <li>Candidates desirous of availing scribe facility need to apply online on the IIBF website by clicking on <u>Apply Now> Apply for scribe.</u></li>
    <li>Only the candidates who have applied Online & obtained prior approval for scribe from IIBF will be allowed to appear with the scribe on the day of the examination.</li> 
    <li>Candidates are advised to apply online for scribe well in advance, not later than 3 days before the examination</li>
    <li>Please ensure that the scribe fulfils the eligibility criteria as prescribed in the rules/guidelines before applying</li>
    <li>Please note that, in case, it is found later that the scribe does not fulfil the eligibility criteria, candidature of the applicant will stand cancelled</li>
    <li>Please read the rules/guidelines for availing the facility of scribe carefully before applying for Scribe</li>
    <li>Your application for scribe will be scrutinized and an email will be sent 1-2 days before the exam date, mentioning the status of acceptance of scribe</li>
    <li>You will be required to produce the print out of permission granted, required documents along with the Admit Letter to the test conducting authority (proctor)</li>
    <li>For the Scribe Guidelines Click Here -</li> 
    </ul> 
    <p style="color:#F00"><a href="https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf" target="_blank">https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf</a><br> 
     </p>
    Regards,<br>
    IIBF Team.<br>
      
      

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
      
      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00">SOB Exam July - 2023</h4></strong></center>
      </div>
      <div class="modal-body">
      <button type="button" class="btn btn-info goAsFresher">Forgo Credits and register de-novo</button>&nbsp;&nbsp;
      <button type="button" class="btn btn-info continueAsOld">Avail credits(as applicable) with Balance attempts</button>
      <br><i style="color:red;">(Select any one)</i>
      </div>
      
      </div>
    </div>
  </div> 
<?php } ?>
<!--       end jaiib new changes - priyanka D -02-jan-23 --> 
      
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


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>

<script>$(document).ready(function() { $('#institutionworking').select2(); }); </script>

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

  if($('#myModal_exemption1').length > 0) {
        	
					$('.takenexemption').click(function(){
						if (!confirm(' Do you want to continue with the selection? ')) 
							{
							
							}
							else
								window.location.href = "<?php echo base_url();?>exemption/process/";
					}); 
					$('.continuetoapplication').click(function(){
							$('#myModal_exemption').modal('hide');
					}); 
				
					$('#myModal_exemption').modal({
							backdrop: 'static'
						});
				} //priyanka d - 26-june-24
  
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
              url: site_url+'NonMember/getsetAsFresherOrOld/?method=set&optVal='+selectedoptVal,
              success: function(res)
              { 
                //alert(res);
                window.location.href = "<?php echo base_url();?>/NonMember/comApplication/?optval="+res;
              }
            });
          }
    
          function getAsFresherOrOld() {
    
            
            $.ajax({
              type: 'POST',
              url: site_url+'NonMember/getsetAsFresherOrOld/?method=get',
              success: function(res)
              { 
                if(res!='')
                  window.location.href = "<?php echo base_url();?>/NonMember/comApplication/?optval="+res;
                else
                window.location.href = "<?php echo base_url();?>/NonMember/comApplication/";
              }
            });
          }
          //alert(<?php echo $this->session->userdata('selectedoptVal') ?>);
          <?php 
          if(!isset($_GET['optval']) && $this->session->userdata('selectedoptVal')!='') {
            ?>
            
          //  getAsFresherOrOld();
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
    $('#myModal_EL').modal('show');
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

  //priyanka d- 02-feb-23 >> start to get proper fee when select-deselect e-learning
    
  
      function changeFeeFromElarningY() {
        $(".loading").show();
        $(".show_el_subject").show();
        $(".el_sub_prop").prop('checked', true);
        
        var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
        var datastring_1='subject_cnt='+el_subject_cnt;
        
        $.ajax({
            url:site_url+'NonMember/set_nonmem_elsub_cnt',
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
        
        if(document.getElementById('subject_elearning_flag_Y').checked){
          var Eval = document.getElementById('subject_elearning_flag_Y').value;
        }

        if(document.getElementById('subject_elearning_flag_N').checked){
          var Eval = document.getElementById('subject_elearning_flag_N').value;
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
      
      function changeFeeFromElarningN() { 
        $(".loading").show();
        $(".show_el_subject").hide();
        $(".el_sub_prop").prop('checked', false);
        
        var el_subject_cnt = 0;
        
        var datastring_1='subject_cnt='+el_subject_cnt;
        
        $.ajax({
            url:site_url+'NonMember/set_nonmem_elsub_cnt',
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
        
        if(document.getElementById('subject_elearning_flag_Y').checked){
          var Eval = document.getElementById('subject_elearning_flag_Y').value;
        }

        if(document.getElementById('subject_elearning_flag_N').checked){
          var Eval = document.getElementById('subject_elearning_flag_N').value;
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
            url:site_url+'NonMember/set_nonmem_elsub_cnt',
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
        
        if(document.getElementById('subject_elearning_flag_Y').checked){
          var Eval = document.getElementById('subject_elearning_flag_Y').value;
        }

        if(document.getElementById('subject_elearning_flag_N').checked){
          var Eval = document.getElementById('subject_elearning_flag_N').value;
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
      //  alert(setcookiename+'=='+getCookie(setcookiename));
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
                //  alert(getcookiename+'=='+getCookie(getcookiename));
                  $(this).prop( "checked", true ).trigger('click');
                //  alert(getcookiename+'=='+getCookie(getcookiename));
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

  $("#dob1").change(function() {
      var sel_dob = $("#dob1").val();
      if (sel_dob != '') {
        var dob_arr = sel_dob.split('-');
        if (dob_arr.length == 3) {
          chkage(dob_arr[2], dob_arr[1], dob_arr[0]);
        } else {
          alert('Select valid date');
        }
      }
    });
  $(function() {
      $("#doj1").dateDropdowns({
        submitFieldName: 'doj1',
        minAge: 0,
        maxAge: 59
      });
    });

    $(document).ready(function() {
      $("#doj1").change(function() {
        var sel_doj = $("#doj1").val();
        if (sel_doj != '') {
          var doj_arr = sel_doj.split('-');
          if (doj_arr.length == 3) {
            CompareMaxDate(doj_arr[2], doj_arr[1], doj_arr[0]);
          } else {
            alert('Select valid date');
          }
        }
      });
    })

  function CompareMaxDate(day,month,year)
  {
    var exam_date_exist = $("#exam_date_exist").val();
    //var check_start_date = "2023-07-01"; 
    var check_start_date = "1964-01-01"; 
    var check_start_date = new Date(check_start_date);
    var check_end_date = "2024-03-31"; 
    var check_end_date = new Date(check_end_date);
    check_end_date.setDate(check_end_date.getDate() + 1);
    //alert(exam_date_exist);
    var flag = 0;
    if(day!='' && month!='' && year!='')
    {
      /*var today = new Date();
      var dd = today.getDate(); 
      var mm = today.getMonth(); 
      var yyyy = today.getFullYear();*/

      var dd = "31"; 
      var mm = "02"; 
      var yyyy = "2024";
       
      if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} 
        var today = new Date(yyyy, mm, dd);
    
      var jday  = day;
      var jmnth = month;
      var jyear = year;
      var jdate = new Date(jyear, jmnth-1, jday);
      
      var sel_dob = $("#dob1").val();
      var dobYear = 0;
      if(sel_dob!='')
      {
        var dob_arr = sel_dob.split('-');
        if(dob_arr.length == 3)
        {
          dobYear = dob_arr[0];
        }
      }
      var minjoinyear = parseInt(dobYear) + parseInt(18);
      //console.log(jdate +'>'+ today);

      var examDate = new Date(exam_date_exist);
      var formattedExamDate = formatDateJs(examDate);
      // Add 9 months
      var ninemonthDate = new Date(jdate);
      ninemonthDate.setMonth(ninemonthDate.getMonth() + 9);
      //alert(ninemonthDate);
      var beforeninemonthDate = new Date(exam_date_exist);
      beforeninemonthDate.setMonth(beforeninemonthDate.getMonth() - 9);
      jdate.setDate(jdate.getDate() + 1); 
      
      /*if( jdate > today )
      {
        $("#doj_error").html('Date of joining should not be greater than 31-March-2024');
        flag = 0;
        return false;
      }
      else if( jdate < beforeninemonthDate ) // && jdate > examDate 
      {
        //console.log(jdate +'<'+ beforeninemonthDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Commencement of operations / joining as BC to be within 9 months from the date of examination.');
        //$("#doj_error").html('Please select your Date of Joining within 9 months (270 days) from the date of examination.<br> Your Examination Date is '+formattedExamDate+', your Date of Joining should be on or after '+formattedbeforeNineMonthDate+'.');
        flag = 0;
        return false;
      }*/
      if( jdate < check_start_date ) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.'); 
        flag = 0;
        return false;
      }
      else if( jdate > check_end_date ) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.'); 
        flag = 0;
        return false;
      }
      else if( jdate > examDate) // && jdate > examDate 
      { 
        //console.log(jdate +'>'+ examDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.');
        //$("#doj_error").html('Commencement of operations / joining as BC to be within 9 months from the date of examination.');
        flag = 0;
        return false;
      }
      else
      {
        $("#doj_error").html('');
        flag = 1;
      }
      
      if(jyear!='' && jyear < minjoinyear )
      {
        //alert("Please select Proper Year of Joining");
        $("#doj_error").html("Please select Proper Year of Joining");
        $("#doj_error").focus();
        flag = 0;
        return false;
      }
      else
      {
        $("#doj_error").html('');
        flag = 1;
      }
    }
    else
    {
      $("#doj_error").html('Please select valid date');
      $("#doj_error").focus();
      flag = 0;
    }
    if(flag==1)
      return true;
    else
      return false;
  }

  function formatDateJs(date) {
      var day = date.getDate();
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var month = monthNames[date.getMonth()];
    var year = date.getFullYear();

    // Add leading zero to the day if it's less than 10
    day = day < 10 ? '0' + day : day;

    return day + '-' + month + '-' + year;
  }

  function check_bank_bc_id_no(){
    var name_of_bank_bc = $("#name_of_bank_bc").val();
    var ippb_emp_id = $("#ippb_emp_id").val();
    var regnumber = '<?php echo $user_info[0]['regnumber']; ?>';
    var datastring='name_of_bank_bc='+name_of_bank_bc+'&ippb_emp_id='+ippb_emp_id+'&mem_type=NM'+'&regnumber='+regnumber;
    $.ajax({
        url:site_url+'Bcbfexam/check_bank_bc_id_no/',
        data: datastring,
        type:'POST',
        async: false,
        success: function(data) {
        if(data != ""){
           $("#ippb_emp_id_error").html(data);
           $("#ippb_emp_id_error").focus();
           return false;
        }else{
          $("#ippb_emp_id_error").html(data);
        }
      }
    });  
  }

</script>


<!-- START: JS CODE FOR IMAGE EDITOR -->
<?php $this->load->view('iibfbcbf/common/inc_lightbox_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_sweet_alert_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('inc_fileChooser_accepted_files' => $inc_fileChooser_accepted_files, 'page_name'=>'non_mem_reg')); ?>

<script>
  function validate_form_images(input_id) 
  {
    $("#page_loader").show();
     
    /*if(input_id == 'scannedphoto') { $('#scannedphoto').parsley().reset(); }
    else if(input_id == 'scannedsignaturephoto') { $('#scannedsignaturephoto').parsley().reset(); }
    else if(input_id == 'idproofphoto') { $('#idproofphoto').parsley().reset(); }
    else */if(input_id == 'empidproofphoto') { $('#empidproofphoto').parsley().reset(); }
    else if(input_id == 'declarationform') { $('#declarationform').parsley().reset(); }

    $("#page_loader").hide();
  }
</script>
<!-- END: JS CODE FOR IMAGE EDITOR -->