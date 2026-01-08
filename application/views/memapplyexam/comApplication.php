<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
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
</style>
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
  <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Applyexam/comApplication/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>"> 
    <section class="content">
      <div class="row">
       <!-- start exemption new changes - priyanka D -26-june-24 -->
        <?php
        
        if(isset($showExemptionOption) && $showExemptionOption==1 ) { ?>
          <div class="col-md-12 " id="myModal_exemption1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-bottom: 3%;     font-size: 0px;" >
            <div class="">
              <div class="modal-content">
              <div class="modal-header">
              
              <strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00">Please refer to the Syllabus and Rules pertaining to the Certificate in Risk and Financial Services Level 1. In case you satisfy the eligibility criteria for exemption from Level 1 Examination
              
              </h4></strong>
              </div>
              <div class="modal-body">
              <center>
              <button type="button" class="btn btn-info takenexemption" >Continue with Exemption</button>
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
             
             
             <?php $fee_amount=$grp_code='';?>
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
            // add below line by gaurav
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
                                         
             <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                  <div class="col-sm-1">
                   <?php echo $user_info[0]['regnumber'];?>
                    </div>
                </div>-->
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name </label>
                     <div class="col-sm-3">
          <?php echo $user_info[0]['firstname'];?>
                         <span class="error"></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                  <div class="col-sm-5">
                    <?php echo $user_info[0]['middlename'];?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
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
                          In order to check your profile details, you need to <a href="<?php echo base_url();?>" target="new"><strong>login</strong></a> to with your membership number and password
                         <span class="error"></span>
                    </div>
                    
                </div>
                
                
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone : STD Code </label>
                  <div class="col-sm-2">
                     <?php echo $user_info[0]['stdcode'];?>
                     <?php echo $user_info[0]['office_phone'];?>
                      <span class="error"></span>
                    </div>
                    
                </div>-->
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                      <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-editmobilecheckexamapply required data-parsley-trigger-after-failure="focusout" > <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>-->
                <input type="hidden" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-editmobilecheckexamapply required data-parsley-trigger-after-failure="focusout" > <span class="error"><?php //echo form_error('mobile');?></span>
                <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                    <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-editemailcheckexamapply  type="text" data-parsley-trigger-after-failure="focusout" >
                      <span class="error"></span>
                    </div>
                </div>-->
                <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-editemailcheckexamapply  type="hidden" data-parsley-trigger-after-failure="focusout" >
                <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            
         
            <div class="box-body">

                <!-- Start and add the below field institute name by gaurav -->
                <?php if( $this->session->userdata('examcode') == 1009 ) { ?>
                  <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label">Institute name</label>
                      <div class="col-sm-5 ">
                        <?php echo $sel_institute_data[0]['name'] != '' ? $sel_institute_data[0]['name'] : 'Institute not found.';?>
                        <div id="error_dob"></div>
                      </div>
                  </div>
                  <input type="hidden" name="institutionworking" value="<?php echo $sel_institute_data[0]['institude_id']; ?>">
                  <input type="hidden" name="institutionname" value="<?php echo $sel_institute_data[0]['name']; ?>">
                <?php } ?>  
                <!-- End -->

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
              $file_size = '300kb';
              if($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047){
                $file_size = '100kb';
              }
              ?>
              <?php /* <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"><?php echo ( ($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047 || $this->session->userdata('examcode') == 991 || $this->session->userdata('examcode') == 997) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); ?> <span style="color:#f00">**</span></label>
                   */ ?><?php /* <div class="col-sm-5">
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
                  </div>
                  <img class="mem_reg_img" id="image_upload_empidproof_preview" height="100" width="100" src="/assets/images/default1.png" />
                </div> */ ?>

                <!-- START: FOR IMAGE EDITOR -->
                <?php $data_lightbox_title_common = "Ordinary Member Registration"; ?>
                <input type="hidden" name="form_value" id="form_value" value="form_value">
                <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
                <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
                <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">
                <!-- END: FOR IMAGE EDITOR -->

                <div class="form-group"><?php // Upload Your Upload Bank BC ID Card / Employee Id proof  ?>
                <?php 
                $examcode = $this->session->userdata('examcode');
                  $image_nm_emp_bank = (($examcode == 101 || $examcode == 1046 || $examcode == 1047) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); 
                  $field_nm_emp_bank = (($examcode == 101 || $examcode == 1046 || $examcode == 1047) ? 'bank_bc_id_card' : 'empidproofphoto');
                ?>
                <label for="empidproofphoto" class="col-sm-3 control-label"><?php echo (isset($examcode) && ($examcode == 101 || $examcode == 1046 || $examcode == 1047) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); ?> <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="empidproofphoto" id="empidproofphoto" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#empidproofphotoError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('<?php echo $field_nm_emp_bank; ?>', 'member_registration', 'Edit Signature');" onblur="validate_form_images('empidproofphoto')"><?php echo $image_nm_emp_bank; ?></button>
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

                <?php
                }
                ?>
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                  <div class="col-sm-5 ">
                    <?php 
                    //$month = date('Y')."-".substr($examinfo['0']['exam_month'],4)."-".date('d');
          $month = date('Y')."-".substr($examinfo['0']['exam_month'],4);
                  if($this->session->userdata('examcode')==600)
                    echo 'Nov-Dec 2025';
                  else
                    echo date('F',strtotime($month))."-".substr($examinfo['0']['exam_month'],0,-2);
          //echo 'Dec 2020';
                  ?>
                        <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
                   <div id="error_dob"></div>
                 
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
                
                <?php if(count($compulsory_subjects) > 0 && ($this->session->userdata('examcode')==101 || $this->session->userdata('examcode')==1046 || $this->session->userdata('examcode')==1047)){?>
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
        ## Caiib changes on 23-Mar-2021 only elective condition 
        if(isset($examinfo[0]['app_category']) &&(count($caiib_subjects) >0) && $elective != 'show')
        {
          $subject_name=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E','subject_code'=>$examinfo[0]['subject']),'subject_description');?>
        <div class="form-group">
          <!--  <label for="roleid" class="col-sm-3 control-label">Elective Subject Name <span style="color:#F00">*</span></label>-->
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
                 <input type="hidden" name="check_elective_validation" id="check_elective_validation" value="N">
                              
                                 <input type="hidden" name="selSubcode" id="selSubcode" value="">
                         <input type="hidden" name="selSubName1" id="selSubName1" value="">
              <?php }
            ?>
              </div>
            </div>
        <?php }
        else
        {?>
        <input type="hidden" name="check_elective_validation" id="check_elective_validation" value="N">
        
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
                  $this->db->where('exam_code',$examinfo['0']['exam_code']);
          $sql = $this->master_model->getRecords('exam_master','','elearning_flag,sub_el_count'); 
          if($sql[0]['elearning_flag'] == 'Y'){ 
        ?>
                <?php
        $styleForELearning="display:none;";
        if($this->session->userdata('examcode')==1002 || $this->session->userdata('examcode')==1003 || $this->session->userdata('examcode')==1004 || $this->session->userdata('examcode')==1005 || $this->session->userdata('examcode')==1014 || $this->session->userdata('examcode')==1006 || $this->session->userdata('examcode')==1007 || $this->session->userdata('examcode')==1011 || $this->session->userdata('examcode')==1014) {
          $styleForELearning="display:none;";

           if($this->session->userdata('examcode')==1002 || $this->session->userdata('examcode')==1003 || $this->session->userdata('examcode')==1004 || $this->session->userdata('examcode')==1005 || $this->session->userdata('examcode')==1006 || $this->session->userdata('examcode')==1007 || $this->session->userdata('examcode')==1011 || $this->session->userdata('examcode')==1014){
            $checked = 'checked="checked"';
            $checked1 = '';
          }
          else{
            $checked = '';
            $checked1 = 'checked="checked"';
          }
        }

        ?>
        <div class="form-group" style="<?php echo $styleForELearning; ?>" > <!-- priyanka d - 02-feb-23 >> hide as duplicate field -->
          <label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label> 
          <div class="col-sm-3">
            <input type="radio" name="elearning_flag" id="elearning_flag_Y" value="Y" <?php echo $checked; ?>>YES
            <input type="radio" name="elearning_flag" id="elearning_flag_N" value="N" <?php echo $checked1; ?>>NO
          </div>
        </div>
                 <?php }else{?>
                 <input type="hidden" name="elearning_flag" id="elearning_flag_Y" value="N" >
         <input type="hidden" name="elearning_flag" id="elearning_flag_N" value="N" >
                 <?php }?>
                 
                 <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) {
                  
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
                 <div class="col-md-12">&nbsp;</div>
                
                  <?php 
                  //print_r($compulsory_subjects);
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
            
            ## Caiib changes on 23-Mar-2021 only elective condition 
            if((!isset($examinfo[0]['app_category']) &&(count($caiib_subjects) >0)) || $elective == 'show')
            {?>
                <input type="hidden" name="check_elective_validation" id="check_elective_validation" value="Y">
                                <div class="form-group">
                                <label for="roleid" class="col-sm-12 control-label">
                                    <div class="col-sm-12 control-label">
                                    <div class="col-sm-1">&nbsp;</div>
                                      <div class="col-sm-3">
                                    <select name="selSubName" id="selSubName" class="form-control" required>
                                    <option value="">Elective Subject</option>
                                    <?php 
                                        foreach($caiib_subjects as $srow)
                                        {?>
                                                <option <?php if($keepElectiveSelected==$srow['subject_code']) echo 'selected'; ?> value="<?php echo $srow['subject_code']?>"><?php echo $srow['subject_description']?></option>
                                        <?php 
                                        }?>
                                    </select>
                                   <div class="text-center">
                                    <input value="Change Subject" name="enab_elect_subj" class="button" id="enab-elect-subj" type="button">
                                    </div>
                                      </div>
                                     <div class="col-sm-2" <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) echo'style="display:none;"'?>>
                                    <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>
                                    <select  name="venue_caiib" id="venue_id" class="form-control venue_cls" required  onchange="caiib_venue(this.value,'date_id','time_id','seat_capacity_id');" >
                                    <option value="">Select</option>
                                    </select>
                                    </div>
                                    
                                    <div class="col-sm-1" >
                                    <label <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) echo'style="display:none;"'?> for="roleid" class="col-sm-3 control-label">Date<span style="color:#F00">*</span></label>
                                    <select <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) echo'style="display:none;"'?> name="date_caiib" id="date_id" class="form-control date_cls" required  onchange="date(this.value,'venue_id','time_id');">
                                    <option value="">Select</option>
                                    </select>
                                    <span class="date_caiib_elective">&nbsp;</span>
                                    </div>
                                    
                                    <div class="col-sm-2" <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) echo'style="display:none;"'?>>
                                    <label for="roleid" class="col-sm-3 control-label">Time<span style="color:#F00">*</span></label>
                                    <select name="time_caiib" id="time_id" class="form-control time_cls" required onchange="time(this.value,'venue_id','date_id','seat_capacity_id');">
                                    <option value="">Select</option>
                                    </select>
                                    </div>
                                    
                                      <label <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) echo'style="display:none;"'?> for="roleid" class="col-sm-2 control-label" style="text-align:left;">Seat(s) Available<span style="color:#F00">*</span><div id="seat_capacity_id">
                                    -
                                    </div></label>
                                    
                                  
                                    </div>
                                   </label>
                                </div>
        <?php }
             
         } 
         
         else
       {
         if((!isset($examinfo[0]['app_category']) &&(count($caiib_subjects) >0)) || $elective == 'show')
            {?>
              <input type="hidden" name="check_elective_validation" id="check_elective_validation" value="Y">
                            
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
         
         }
         
         #--------------Code added by pooja godse 2019-03-21------------#
         if($this->session->userdata('examcode')==$this->config->item('examCodeJaiib') || $this->session->userdata('examcode')==$this->config->item('examCodeDBF'))
         {?>
             
        <!--<div class="form-group">
       
       <div style="background-color: lightgrey;
  width: 900px;
  border: 5px solid #7fd1ea;
  padding: 30px;
  margin: 50px; font-size:16px">Candidate selecting exam date as <strong>12th May or 19th May 2019</strong> please note that; Due to forthcoming Lok Sabha Election, Institute has decided to reschedule exam date which are coinciding with the election date for those affected 90 Centers/City as mentioned below. <a href="<?php echo base_url()?>uploads/Election_Affected_Centre_List.pdf" target="_blank" style="font-size:16px">
<strong>(Click here to view 90 Centres/City list for which schedule is changed). </strong></a>
 <br> <br>
   <ol style="font-size:16px">
  <li>The Exam scheduled on <strong>12-May-2019</strong> is re-scheduled on <strong>25-May 2019 (4th Saturday)</strong>  </li>
 <li>The Exam scheduled on <strong>19-May 2019</strong> is re-scheduled on <strong>26-May 2019 (4th Sunday)</strong></li>
 
</ol> 
 
<p  style="font-size:16px">
<br>
For all other Centre/City the examination will be conducted as per existing scheduled
</p>
                       
            <p style="color:#FF0000; font-size:16px" >  Candidates are advised to download Revised Admit letter from the Institute website one week before the exam date. </p>
  
                    
                    <p style="font-size:16px"> 
                    <input type="checkbox" id="agree" value="yes" name="agree" required>&nbsp; I agree to abide by changed schedule.
                    
                    </p>
  <br>
  </div>
       </div>-->
<?php }
 #--------------end Code added by pooja godse 2019-03-21------------#
 ?>
 
 
         <?php 
          if($sql[0]['sub_el_count'] == 'Y'){
            $subject_cnt = count($compulsory_subjects);
            $subject_cnt_arr = array('subject_cnt'=>$subject_cnt);
                    $this->session->set_userdata($subject_cnt_arr);
                if( count($compulsory_subjects) >0)
        { ?>
                 <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to select eLearning</label>
                        <div class="col-sm-3">
                        <input type="radio" name="elearning_flag" id="subject_elearning_flag_Y" value="Y" checked="checked">YES
               <input type="radio" name="elearning_flag" id="subject_elearning_flag_N" value="N" >NO
                        </div>
                 </div>
<?php }else
{
  $elective_flag=0;
   if(count($caiib_subjects)>0)
   {
           foreach($caiib_subjects as $srow)
      
              {
                 if($srow['subject_code'] == 165 || $srow['subject_code'] == 161 || $srow['subject_code'] == 160)
                     {
                        $elective_flag=1;
                     }        
            
              }
   }
   
  if($elective_flag)
  {?>
    <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to select eLearning</label>
                        <div class="col-sm-3">
                        <input type="radio" name="elearning_flag" id="subject_elearning_flag_Y" value="Y" checked="checked">YES
               <input type="radio" name="elearning_flag" id="subject_elearning_flag_N" value="N" >NO
                        </div>
                 </div>
  
  <?php 
  }
 }?>
                 <?php 
            if($this->session->userdata('examcode') == $this->config->item('examCodeCaiib'))
          {
            
            $subcnt = 0;
            foreach($compulsory_subjects as $el_subject){
            ## Changes related to caiib
              /*if($el_subject['subject_code'] == 165 || $el_subject['subject_code'] == 161 || $el_subject['subject_code'] == 160)*/
              { 
            
            $subcnt++;
            
          ?>
                    <div class="form-group show_el_subject" >
                    <label for="roleid" class="col-sm-3 control-label"><?php echo $el_subject['subject_description']?><span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                        <input type="checkbox" name="el_subject[<?php echo $el_subject['subject_code']?>]" value="Y" checked="checked" class="el_sub_prop" />
                        </div>
          </div>
          <?php } }
            $subject_cnt = $subcnt;
            $subject_cnt_arr = array('subject_cnt'=>$subject_cnt);
                    $this->session->set_userdata($subject_cnt_arr);
            
          }else{ 
          
            $subject_cnt = count($compulsory_subjects);
            $subject_cnt_arr = array('subject_cnt'=>$subject_cnt);
                    $this->session->set_userdata($subject_cnt_arr);
            foreach($compulsory_subjects as $el_subject){ ?>
          <div class="form-group show_el_subject" >
                    <label for="roleid" class="col-sm-3 control-label"><?php echo $el_subject['subject_description']?><span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                        <input type="checkbox" name="el_subject[<?php echo $el_subject['subject_code']?>]" value="Y" checked="checked" class="el_sub_prop" />
                        </div>
          </div>
          <?php }  } ?>
          <div class="form-group show_el_subject" id="elarning_elective" style="display:none;">
                    <label for="roleid" class="col-sm-3 control-label">RETAIL BANKING<span style="color:#F00">*</span></label>
          <div class="col-sm-3"><input type="checkbox" id="retail_check" name="el_subject[165]" value="Y" class="el_sub_prop" /></div>
                 </div>
         <!-- Code added for caiib elarning end-->
         <?php }else{ ?>
        <!--<input type="hidden" name="elearning_flag" value="N" id="subject_elearning_flag_Y" />
                <input type="hidden" name="elearning_flag" value="N" id="subject_elearning_flag_N" />-->
                <input type="hidden" name="el_subject[]" value="N"  class="el_sub_prop" />
        <?php }?>
 
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
            <?php if($this->session->userdata('examcode')!=101 && $this->session->userdata('examcode')!=1046 && $this->session->userdata('examcode')!=1047)
         {
         ?>
              
             <?php 
         }?>     

          <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) {

          ?>
                <div class="form-group div_photo">
                  <label for="roleid" class="col-sm-3 control-label">Photo</label>
                    <div class="col-sm-2">
                      <label for="roleid" class="col-sm-3 control-label">
                        <?php 
                            if(is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')))
                              { ?>
                                  <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('mregnumber_applyexam'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" >
                            <?php 
                              }
                            else
                            { 
                              ?>
                                <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                              <?php   
                            } ?>
                      </label>
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
                    
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
                </div>
                
                <?php } ?>
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
                                    foreach($states as $row1){  ?>
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
               <?php /*?> <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Are you a person with benchmark disability of 40% or above (PwBD)<span style="color:#F00">*</span> </label> 
                        <div class="col-sm-3">
                       
                           <input type="radio" name="scribe_flag_d" id="scribe_flag_d" value="Y" onclick="showSelect();">YES
               <input type="radio" name="scribe_flag_d" id="scribe_flag_d" value="N" onclick="hideSelect();" checked="checked">NO
               
                        </div>
            
                         </div>
             <div id="disability" style="display:none">
        <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Type of Disability  <span style="color:#F00">*</span></label> 
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
                                 <label for="roleid" class="col-sm-3 control-label">Sub Type of Disability  <span style="color:#F00">*</span> </label> 
                  <div class="col-sm-3">
                     <div id="textTraining_type"></div>
                                    </div>
                   </div><?php */?>
      
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
                    <label for="roleid" class="col-sm-3 control-label">Do you intend to use <br />the services of a scribe ?<span style="color:#F00">*</span> </label> 
                        <div class="col-sm-3">
            <?php if($benchmark_disability_info[0]['benchmark_disability']=='Y'){ ?>
                           <input type="radio" name="scribe_flag" id="scribe_flag" value="Y" onclick="showSelect_scribe_flagY();">YES
               <input type="radio" name="scribe_flag" id="scribe_flag" value="N" onclick="showSelect_scribe_flagN();" checked="checked">NO
               <?php }  else { ?>
              <input type="radio" readonly name="scribe_flag" id="scribe_flag" value="N" onclick="showSelect_scribe_flagN();" checked="checked">NO
              <?php } ?>
                        </div>
                         </div>
                <div class="form-group">
              <div class="col-sm-12">

              <?php 
              $exam_code_chk = $this->session->userdata('examcode');
              $exam_arr = array(1001,1002,1003,1004,1005,1009,1013,1014,1006,1007,1008,1011,1012,2027,1019,1020,1058); // 1002,1003,1004,1005,1009,1013,1014
              if(!in_array($exam_code_chk, $exam_arr)){ 
              ?>   
              <img src="<?php echo base_url()?>assets/images/bullet2.gif"> It is mandatory for a candidate to opt the examination centre being his/her place of work. If there is no centre at his/her place of work, shall have to opt the centre nearest to his/her place of work. Result of candidate violating this rule or giving wrong information is liable for cancellation.<br>

              <?php } ?>
              
<br /><!--B) Since the Institute will not be sending the Admit Letter(hard copy) through post, Correct E-mail address is mandatory for receipt of Admit Letter/Hall Ticket through e-mail.-->
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>
                </div>
               
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                     
                     <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return member_apply_exam();" id="preview">Preview</a>-->
                     <?php if($ButtonDisable) { ?>
                       <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Preview" onclick="javascript : return  member_apply_exam();">
                     
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                     <button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button>
                     <?php } ?> 
                     <a href="<?php echo base_url();?>Applyexam/examdetails/?ExId=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>
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
    <!--<img src="<?php //echo base_url()?>assets/images/bullet2.gif"> -->
<?php
  $exam_cade_rpe_array = array(1002,1003,1004,1005,1009,1013,1014);
  $ecode = $this->session->userdata('examcode');
  if(in_array($this->session->userdata('examcode'), $exam_cade_rpe_array)){
    ?>
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
    <?php
  }else if($ecode != $this->config->item('examCodeJaiib') || $ecode != $this->config->item('examCodeDBF') || $ecode != $this->config->item('examCodeSOB') || $ecode != $this->config->item('examCodeCaiib') || $ecode != 62 || $ecode != $this->config->item('examCodeCaiibElective63') || $ecode != 64 || $ecode != 65 || $ecode != 66 || $ecode != 67 || $ecode != $this->config->item('examCodeCaiibElective68') || $ecode != $this->config->item('examCodeCaiibElective69') || $ecode != $this->config->item('examCodeCaiibElective70') || $ecode != $this->config->item('examCodeCaiibElective71') || $ecode != 72){
?>
  In case any candidate wants to avail scribe facility he/she needs to apply online on the IIBF website by clicking on Apply Now> Apply for scribe. Once the application is approved by IIBF, the candidate will get an email confirmation of the permission granted by the Institute. Candidates are advised to apply online for scribe well in advance, not later than 3 days before the examination. (This is required to make suitable arrangements at the examination venue). Candidate is required to follow this procedure for each attempt/subject of the examination in case the help of scribe is required.
<br /><br />
      <p style="color:#F00">Click here to download the declaration form <a href="https://www.iibf.org.in/documents/SCRIBE%20Guideline_30-08-2022.pdf" download target="_blank">Scribe_Guideliness_Rev.pdf</a></p>
            
<?php }else{?>          
      
      Dear Candidate,<br><br>
 <p>
You have opted for the services of a scribe for the above mentioned examination under <strong>Remote Proctored mode</strong>.<br><br>
 
For the purpose of approving the scribe and to give you extra time as per rules, you are requested to email Admit letter, Details of the scribe, Declaration and Relevant Doctor's Certificates to <strong>suhas@iibf.org.in / amit@iibf.org.in</strong> at least one week before the exam date<br><br>
 
Your application for scribe will be scrutinized and an email will be sent 1-2 days before the exam date, mentioning the status of acceptance of scribe.<br><br>
 
You will be required to produce the print out of permission granted, required documents along with the Admit Letter to the test conducting authority (procter).<br><br></p>
 
<p style="color:#F00">Click Here - <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_R-150219.pdf" target="_blank">GENERAL GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED & ORTHOPEADICALLY CHALLENGED CANDIDATES</a><br>
 
 </p>
Regards,<br>
IIBF Team.<br>
<?php }?>
      
      
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
      
      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00">CAIIB June -2025 & CAIIB Elective-June 2025</h4></strong></center>
      </div>
      <div class="modal-body">
      <button type="button" class="btn btn-info goAsFresher" >Forgo Credits and register de-novo</button>
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

<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>

<!-- Added for Image Validation -->
<script src="https://iibf.esdsconnect.com/staging/js/validateFile.js"></script>

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
  $( "form#member_conApplication" ).submit(function() {
    if (!confirm('Please check your application details carefully before proceeding for payment ')) 
          {
            
            return false;
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
          url: site_url+'Applyexam/getsetAsFresherOrOld/?method=set&optVal='+selectedoptVal,
          success: function(res)
          { 
            //alert(res);
            window.location.href = "<?php echo base_url();?>/Applyexam/comApplication/?optval="+res;
          }
        });
      }

      function getAsFresherOrOld() {

        
        $.ajax({
          type: 'POST',
          url: site_url+'Applyexam/getsetAsFresherOrOld/?method=get',
          success: function(res)
          { 
            if(res!='')
              window.location.href = "<?php echo base_url();?>/Applyexam/comApplication/?optval="+res;
            else
            window.location.href = "<?php echo base_url();?>/Applyexam/comApplication/";
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
  /* code added for elective elarning*/
  $('#selSubName').on('change',function(){
    
    if($(this).val() == 165)
    {
      $(".show_el_subject").show();
      $(".el_sub_prop").prop("checked",true);
    //$( "#subject_elearning_flag_N" ).prop( "checked", false );
    $( "#subject_elearning_flag_Y" ).prop( "checked", true );
      
      if(document.getElementById('subject_elearning_flag_Y').checked) 
      {
        
          $("#elarning_elective").show();
          if($("#retail_check").prop("checked") == false)
          {
            $("#retail_check").trigger('click');
          } 
          $(".loading").show();
          var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
          var datastring_1='subject_cnt='+el_subject_cnt;
          
          $.ajax({
              url:site_url+'Applyexam/set_jaiib_elsub_cnt/',
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
                //alert(data);
                document.getElementById('fee').value = data ;
                document.getElementById('html_fee_id').innerHTML =data;
              }
            }
          });
        $(".loading").hide();   
      
      }
    }else{
$( "#subject_elearning_flag_N" ).prop( "checked", true );
<?php if(count($compulsory_subjects)>0)
{?>
$( "#subject_elearning_flag_Y" ).prop( "checked", true );
$(".show_el_subject").show();
 $(".el_sub_prop").prop("checked",true);
<?php }
else
{?>
  $( "#subject_elearning_flag_Y" ).prop( "checked", false );
<?php 
}?>
      $(".loading").show();
      $("#retail_check").prop('checked', false);
      $("#elarning_elective").hide();
      
      var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
      var datastring_1='subject_cnt='+el_subject_cnt;
      
      $.ajax({
          url:site_url+'Applyexam/set_jaiib_elsub_cnt/',
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
            //alert(data);
            document.getElementById('fee').value = data ;
            document.getElementById('html_fee_id').innerHTML =data;
          }
        }
      });
    $(".loading").hide();
    }
  });
  /* code added for elective elarning end*/
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
  
  $("#subject_elearning_flag_Y").click(function(){
    $(".loading").show();
    $(".show_el_subject").show();
    $(".el_sub_prop").prop('checked', true);
    if($("#selSubName").val() != 165)
    {
      $("#elarning_elective").hide();
      $("#retail_check").prop('checked', false);
    }
    var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
    var datastring_1='subject_cnt='+el_subject_cnt;
    
    $.ajax({
        url:site_url+'Applyexam/set_jaiib_elsub_cnt/',
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
    
  })
  
  $("#subject_elearning_flag_N").click(function(){ 
    $(".loading").show();
    $(".show_el_subject").hide();
    $(".el_sub_prop").prop('checked', false);
    
    var el_subject_cnt = 0;
    
    var datastring_1='subject_cnt='+el_subject_cnt;
    
    $.ajax({
        url:site_url+'Applyexam/set_jaiib_elsub_cnt/',
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
  })
  
  $(".el_sub_prop").click(function(){
    $(".loading").show();
    var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
    var datastring_1='subject_cnt='+el_subject_cnt;
    
    $.ajax({
        url:site_url+'Applyexam/set_jaiib_elsub_cnt/',
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
  })
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
function showSelect_scribe_flagY() {
    $('#myModal').modal('show');
}
function showSelect_scribe_flagN() {
    $('#myModal').modal('hide');
}
  
/*function showSelect() {
$("#disability").show();
//$('#disability').attr("required","true");
$("#showdept_dropdown_default").show();
$("#Sub_menue").attr("required","true");
$("#disability_value").attr("required","true");
$("#Sub_menue").show();
$("#scribe_flag").removeAttr("disabled");
 
}
function hideSelect() {
$("#showdept_dropdown_default").hide();
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
      url: base_url+"Applyexam/getsub_menue",
      data:{deptid:deptid},
      success:function(data){
        if(data != "")
        {   
            $("#showdept_dropdown").show();
            $("#textTraining_type").text('');
            $("#textTraining_type").append(data);
            $("#Sub_menue").attr("required","true");
              $("#showdept").hide();
          $("#showdept_dropdown_default").hide();
          
        
        }
        else{
        $("#Sub_menue").removeAttribute("required"); 
          $("#showdept_dropdown").hide();
          $("#showdept").show();
        
      
        }
      } 
    },"json");
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
    var regnumber = '<?php echo $this->session->userdata('mregnumber_applyexam'); ?>';
    var datastring='name_of_bank_bc='+name_of_bank_bc+'&ippb_emp_id='+ippb_emp_id+'&regnumber='+regnumber;
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
<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('inc_fileChooser_accepted_files' => $inc_fileChooser_accepted_files, 'page_name'=>'ordinary_mem_apply_exam')); ?>

<script>
  function validate_form_images(input_id) 
  {
    $("#page_loader").show();
     
    /*if(input_id == 'scannedphoto') { $('#scannedphoto').parsley().reset(); }
    else if(input_id == 'scannedsignaturephoto') { $('#scannedsignaturephoto').parsley().reset(); }
    else if(input_id == 'idproofphoto') { $('#idproofphoto').parsley().reset(); }
    else */if(input_id == 'empidproofphoto') { $('#empidproofphoto').parsley().reset(); }
    //else if(input_id == 'declarationform') { $('#declarationform').parsley().reset(); }

    $("#page_loader").hide();
  }
</script>
<!-- END: JS CODE FOR IMAGE EDITOR -->