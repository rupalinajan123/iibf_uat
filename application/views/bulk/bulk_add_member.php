<?php
$disaply_class = '';
$jK_exam_code = array(1006,1007);//1005, added to disable e-learning for J & K (Pooja mane 12-2-24)
if ($_SESSION['is_elearning_course']=='y') {
   $disaply_class='hidden';
}
?>
<!-- custom style for datepicker dropdowns -->
<style>
   .example {
   width: 33%;
   min-width: 370px;
   /* padding: 15px;*/
   display: inline-block;
   box-sizing: border-box;/*text-align: center;*/
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
   .mandatory-field, .required-spn {
   color:#F00;
   }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1> Examination application form</h1>
      <!--<a  class="btn btn-info" href = "javascript:history.back()" style="float:right">Back</a>--> 
      <a  class="btn btn-warning" href="<?php echo base_url();?>bulk/BulkApply/add_member/"  style="float:right">Refresh</a>
      <?php ////echo $breadcrumb;?>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <!-- Horizontal Form -->
            <?php if($this->session->flashdata('error')!=''){?>
            <div class="alert alert-danger alert-dismissible">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
               <?php echo $this->session->flashdata('error'); ?> 
            </div>
            <?php } if($this->session->flashdata('success')!=''){ ?>
            <div class="alert alert-success alert-dismissible">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
               <?php //echo $this->session->flashdata('success'); ?>
            </div>
            <?php } 
               if(validation_errors()!=''){?>
            <div class="alert alert-danger alert-dismissible">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
               <?php //echo validation_errors(); ?>
            </div>
            <?php } ?>
            <?php if(!empty($mem_info)){
               ?>
            <!--<div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Membership No :</label>
               
               <div class="col-sm-5">
               
                <input type="text" class="form-control"  name="regnumber" placeholder="Registration no"     value="<?php echo $mem_info[0]['regnumber'];?>" readonly="readonly" >
               
               </div><button name="getdata" >Get Details</button> 
               
               </div>--> 
            <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApply/comApplication/">
               <div class="box box-info">
                  <div class="box-header with-border">
                     <h3 class="box-title">Basic Details</h3>
                  </div>
                  <?php 
                     $ex_prd='';
                     
                     if(isset($this->session->userdata['exmCrdPrd']['exam_prd']))
                     
                     {
                     
                      $ex_prd=$this->session->userdata['exmCrdPrd']['exam_prd'];
                     
                     }
                     
                     
                     
                     // code to get discount flag
                     
                     $discount_flg = '';
                     
                     $this->db->where('exam_code',$this->session->userdata('examcode'));
                     
                     $this->db->where('eligible_period',$this->session->userdata['exmCrdPrd']['exam_prd']);
                     
                     $this->db->where('member_no',$mem_info[0]['regnumber']);
                     
                     $discount = $this->master_model->getRecords('eligible_master','','discount_flag,fee_paid_flag,reapeter_flag');
                     
                     $discount_flg = $discount[0]['discount_flag'];
                     
                     
                     
                     ?>
                  <!-- /.box-header --> 
                  <!-- form start -->
                  <div class="box-body">
                     <input type="hidden" name="discount_flag" id="discount_flag" value="<?php echo $discount_flg?>" />
                     <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>">
                     <input type="hidden" name="reg_no" id="reg_no" value="<?php echo $mem_info[0]['regnumber'];?>">
                     <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type'];?>">
                     <input type="hidden" id="exname" name="exname"  value=" <?php echo $examinfo[0]['description'];?>">
                     <input type="hidden" id="excd" name="excd"  value="<?php echo base64_encode($this->session->userdata('examcode'));?>">
                     <input id="examcode" name="examcode" type="hidden" value="<?php echo $this->session->userdata('examcode');?>">
                     <input id="eprid" name="eprid" type="hidden" value="<?php echo $ex_prd;?>">
                     <input id="fee" name="fee" type="hidden" value="">
                     <input type='hidden' name='mtype' id='mtype' value="<?php echo $mem_info[0]['registrationtype'];?>">
                     <?php 
                        if($this->session->userdata('examcode') != 1015){
                        ?>
                     <input type='hidden' name='free_paid_flag' id='free_paid_flag' value="P"> 
                     <input type='hidden' name='reapeter_flag' id='reapeter_flag' value="N"> 
                     <?php }else{?>
                     <input type='hidden' name='free_paid_flag' id='free_paid_flag' value="<?php echo $discount[0]['fee_paid_flag']?>"> 
                     <input type='hidden' name='reapeter_flag' id='reapeter_flag' value="<?php echo $discount[0]['reapeter_flag']?>"> 
                     <?php }?>
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
                        <label for="roleid" class="col-sm-3 control-label">Membership No :</label>
                        <div class="col-sm-5">
                           <!--<input type="text" class="form-control"  name="regnumber" placeholder="Registration no"     value="<?php echo $mem_info[0]['regnumber'];?>" readonly="readonly" >--> 
                           <?php echo $mem_info[0]['regnumber'];?> 
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">First Name  : </label>
                        <div class="col-sm-3"> <?php echo $mem_info[0]['firstname'];?> <span class="error"><?php echo form_error('firstname');?></span> </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Middle Name :</label>
                        <div class="col-sm-5">
                           <?php echo $mem_info[0]['middlename'];?> 
                           <!--    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $user_info[0]['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >--> 
                           <span class="error"><?php echo form_error('middlename');?></span> 
                        </div>
                        <!--(Max 30 Characters) --> 
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Last Name :</label>
                        <div class="col-sm-5">
                           <?php echo $mem_info[0]['lastname'];?> 
                           <!--<input type="text" class="form-control" id="middlename" name="lastname" placeholder="Last Name"  value="<?php echo $user_info[0]['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >--> 
                           <span class="error"><?php echo form_error('lastname');?></span> 
                        </div>
                        <!--(Max 30 Characters) --> 
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Phone : STD Code : </label>
                        <div class="col-sm-2"> <?php echo $mem_info[0]['stdcode'];?> <?php echo $mem_info[0]['office_phone'];?> <span class="error"><?php echo form_error('stdcode');?></span> </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span> :</label>
                        <div class="col-sm-5"> <?php echo $mem_info[0]['mobile'];?> <span class="error"><?php echo form_error('mobile');?></span> </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span> : </label>
                        <div class="col-sm-5"> <?php echo $mem_info[0]['email'];?> <span class="error"><?php echo form_error('email');?></span> </div>
                     </div>
                     <?php if(!$mem_info[0]['bank_emp_id']){?>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00">*</span></label>
                        <div class="col-sm-4">
                           <input type="text" class="form-control" id="bank_emp_id" name="bank_emp_id" placeholder="Employee Id"  value="<?php echo set_value('bank_emp_id');?>"  data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required >
                           <span class="error">
                           <?php //echo form_error('city');?>
                           </span> 
                        </div>
                     </div>
                     <?php }else{ ?>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00">*</span></label>
                        <div class="col-sm-4"> <?php echo $mem_info[0]['bank_emp_id'];?>
                           <input type="hidden" name="bank_emp_id" value="<?php echo $mem_info[0]['bank_emp_id'];?> ">
                        </div>
                     </div>
                     <?php } ?>
                     <?php if(count($bulk_payment_scale_master) > 0){
                        $is_flag = 1; ?>
                     <?php if(!$mem_info[0]['bank_scale']){?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Pay Scale <span style="color:#F00">*</span></label>
                        <div class="col-sm-4">
                           <select class="form-control" id="bank_scale" name="bank_scale" required>
                              <option value="">Select</option>
                              <?php if(count($bulk_payment_scale_master) > 0){
                                 foreach($bulk_payment_scale_master as $row1){  ?>
                              <option value="<?php echo $row1['id'];?>" <?php echo set_select( 'bank_scale', $row1['id']); ?>> <?php echo $row1['pay_scale'];?> </option>
                              <?php } } ?>
                           </select>
                        </div>
                     </div>
                     <?php }else{ ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Pay Scale <span style="color:#F00">*</span></label>
                        <div class="col-sm-4">
                           <?php if(count($bulk_payment_scale_master) > 0){foreach($bulk_payment_scale_master as $row1){ ?>
                           <?php if($mem_info[0]['bank_scale']==$row1['id']){echo $row1['pay_scale'];}?>
                           <?php } } ?>
                           <input type="hidden" name="bank_scale" value="<?php echo $mem_info[0]['bank_scale']; ?>" >
                        </div>
                     </div>
                     <?php } ?>
                     <?php } else { ?>
                     <input type="hidden" name="bank_scale" value="" >
                     <?php } ?>
                     <?php if(count($bulk_zone_master) > 0){
                        $is_flag = 1; ?>
                     <?php if(!$mem_info[0]['bank_zone']){?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Bank Zone <span style="color:#F00">*</span></label>
                        <div class="col-sm-4">
                           <select class="form-control" id="bank_zone" name="bank_zone" required>
                              <option value="">Select</option>
                              <?php if(count($bulk_zone_master) > 0){
                                 foreach($bulk_zone_master as $row1){   ?>
                              <option value="<?php echo $row1['zone_id'];?>" <?php echo set_select( 'bank_zone', $row1['zone_id']); ?>> <?php echo $row1['zone_code'];?> </option>
                              <?php } } ?>
                           </select>
                        </div>
                     </div>
                     <?php }else{ ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Bank Zone <span style="color:#F00">*</span></label>
                        <div class="col-sm-4">
                           <?php if(count($bulk_zone_master) > 0){foreach($bulk_zone_master as $row1){ ?>
                           <?php if($mem_info[0]['bank_zone']==$row1['zone_id']){echo  $row1['zone_code'];}?>
                           <?php } } ?>
                           <input type="hidden" name="bank_zone" value="<?php echo $mem_info[0]['bank_zone']; ?>" >
                        </div>
                     </div>
                     <?php } ?>
                     <?php } else { ?>
                     <input type="hidden" name="bank_zone" value="" >
                     <?php } ?>
                  </div>
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
                           <div id="error_dob_size"></div>
                           <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                           <?php //echo form_error('idproofphoto');?>
                           </span> 
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
                           <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                           <?php //echo form_error('idproofphoto');?>
                           </span> 
                        </div>
                     </div>


                     <?php
                if($this->session->userdata('examcode') == 994 || $this->session->userdata('examcode') == 996 || $this->session->userdata('examcode') == 1055 || $this->session->userdata('examcode') == 1056)
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
                  <input type="hidden" id="dob1" name="dob" required value="<?php echo $mem_info[0]['dateofbirth']; ?>">
                  <input type="hidden" id="exam_date_exist" name="exam_date_exist" value="<?php echo date('Y-m-d',strtotime($compulsory_subjects[0]['exam_date']));?>">
                  <div class="col-sm-4 doj">
                    <div class="col-sm-2 example" style="width: 100%;padding-left: 0px;">
                      <input type="hidden" id="doj1" name="date_of_commenc_bc" value="<?php echo $mem_info[0]['date_of_commenc_bc']; ?>">
                    </div>
                    <span id="doj_error" class="error"></span>
                  </div>
                </div>

                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Bank BC ID No <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input required type="text" class="form-control" id="ippb_emp_id" name="ippb_emp_id" placeholder="Bank BC ID No" onchange="check_bank_bc_id_no();" value="<?php echo $mem_info[0]['ippb_emp_id']; ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                  <span id="ippb_emp_id_error" class="error"></span>
                </div>
              </div>
              <?php
              $file_size = '300kb';
              if($this->session->userdata('examcode') == 994 || $this->session->userdata('examcode') == 996 || $this->session->userdata('examcode') == 1055 || $this->session->userdata('examcode') == 1056){
                $file_size = '100kb';
              }
              ?>
              <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Upload Bank BC ID Card <span style="color:#f00">**</span></label>
                  <div class="col-sm-5">
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
                              
                              echo date('F',strtotime($month))."-".substr($examinfo['0']['exam_month'],0,-2);
                              
                              ?>
                           <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
                           <div id="error_dob"></div>
                           <div id="error_dob_size"></div>
                           <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                           <?php //echo form_error('idproofphoto');?>
                           </span> 
                        </div>
                     </div>
                     <?php 
                        if(isset($examinfo[0]['app_category']) &&(count($caiib_subjects) >0))
                        
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
                              <?php if( count($medium) > 1 && $this->session->userdata('is_elearning_course')=='n') 
                              { ?>
                                 <option value="">Select</option>
                              <?php } ?>
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
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>
                        <div class="col-sm-4">
                           <select name="selCenterName" id="selCenterName" class="form-control" required onchange="valCentre(this.value);">
                              <?php if( count($center) >0 && $this->session->userdata('is_elearning_course')=='n') 
                              { ?>
                                 <option value="">Select</option>
                              <?php } ?>
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
                        
                        if($sql[0]['elearning_flag'] == 'Y' && in_array($examinfo['0']['exam_code'], $jK_exam_code)){
                        
                        ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label>
                        <div class="col-sm-3">
                           <input type="radio" name="elearning_flag" id="elearning_flag_Y" value="Y" checked="checked" >
                           YES
                           <input type="radio" name="elearning_flag" id="elearning_flag_N" value="N" disabled>
                           NO 
                        </div>
                     </div>
                     <?php }else{?>
                     <input type="hidden" name="elearning_flag" id="elearning_flag_Y" value="N" >
                     <input type="hidden" name="elearning_flag" id="elearning_flag_N" value="N" >
                     <?php }?>
                     <?php 
                        if(count($compulsory_subjects) > 0 && $this->session->userdata('examcode')!=101 
                        && $this->session->userdata('is_elearning_course')=='n'
                        && $this->session->userdata('examcode')!=1010
                        
                        && $this->session->userdata('examcode')!=10100
                        
                        && $this->session->userdata('examcode')!=101000
                        
                        && $this->session->userdata('examcode')!=1010000
                        
                        && $this->session->userdata('examcode')!=10100000) /* && $this->session->userdata('examcode')!=996 */
                        
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
                        <div id="seat_capacity_<?php echo $i;?>"> - </div>
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
                        <label for="roleid" class="col-sm-2 control-label" style="text-align:left;">
                        Seat(s) Available<span style="color:#F00">*</span>
                        <div id="seat_capacity_id"> - </div>
                        </label>
                        </div>
                        </label>
                     </div>
                     <?php }
                        }?>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Centre Code <span style="color:#F00">*</span></label>
                        <div class="col-sm-2">
                           <input type="text" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly"
                              value="">
                        </div>
                     </div>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Exam Mode <span style="color:#F00">*</span></label>
                       
                        <div name="optmode1" id="optmode1" style="display: none;">Exam will be in ONLINE mode only, Read Important Instructions on the website.</div>
                        <div name="optmode2" id="optmode2" style="display: none;">Exam will be in OFFLINE mode only, Read Important Instructions on the website.</div>
                        <input id="optmode" name="optmode" value="" type="hidden">
                     </div>
                     <?php if($this->session->userdata('examcode')!=101 
                        && $this->session->userdata('is_elearning_course')=='n'
                        && $this->session->userdata('examcode')!=1010
                        
                        && $this->session->userdata('examcode')!=10100
                        
                        && $this->session->userdata('examcode')!=101000
                        
                        && $this->session->userdata('examcode')!=1010000
                        
                        && $this->session->userdata('examcode')!=10100000
                        
                        && $this->session->userdata('examcode')!=996)
                        
                         {
                        
                         ?>
                     <?php 
                        }?>
                     <div class="form-group <?php echo $disaply_class; ?>">
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
                           <span class="error">
                           <?php //echo form_error('gender');?>
                           </span> 
                        </div>
                        <img id="image_upload_scanphoto_preview" height="100" width="100"/> 
                     </div>
                     <div class="form-group <?php echo $disaply_class; ?>">
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
                           <span class="error">
                           <?php //echo form_error('gender');?>
                           </span> 
                        </div>
                        <img id="image_upload_sign_preview" height="100" width="100"/> 
                     </div>
                     <?php 
                        /*if(!file_exists('./uploads/photograph/'.$mem_info[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$mem_info[0]['scannedsignaturephoto']) ||$mem_info[0]['scannedphoto']=='' || $mem_info[0]['scannedsignaturephoto']=='')
                        
                        {*/
                        
                        if(!is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')) || !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')))
                        
                        {?>
                     <!--<div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label"></label>
                        
                            If your above Photo/Signature is not clear, Pl upload another Photo/Signature using the below given link.
                        
                              <span class="error"><?php //echo form_error('gender');?></span>
                        
                        </div>-->
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-5">
                           <input  type="file" class="" name="scannedphoto" id="scannedphoto" required="required">
                           <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                           <div id="error_photo"></div>
                           <br>
                           <div id="error_photo_size"></div>
                           <span class="photo_text" style="display:none;"></span> <span class="error">
                           <?php //echo form_error('scannedphoto');?>
                           </span> 
                        </div>
                     </div>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Signature</label>
                        <div class="col-sm-5">
                           <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto"  required="required">
                           <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                           <div id="error_signature"></div>
                           <br>
                           <div id="error_signature_size"></div>
                           <span class="signature_text" style="display:none;"></span> <span class="error">
                           <?php //echo form_error('scannedsignaturephoto');?>
                           </span> 
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
                                 foreach($states as $row1){   ?>
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
                           <span class="error">
                           <?php //echo form_error('pincode');?>
                           </span> 
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
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Scribe required?</label>
                        <div class="col-sm-3">
                           <input type="checkbox" name="scribe_flag" id="scribe_flag" value="Y">
                        </div>
                     </div>
                     <div class="form-group">
                     <?php if($this->session->userdata('examcode')!=1009) { ?>
                        <div class="col-sm-12">
                           <img src="<?php echo base_url()?>assets/images/bullet2.gif"> The candidate should send a separate application along with the DECLARATION as given in the  Scribe Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination 
                           (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt 
                           of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.<br />

                            <?php 
                           $exam_code = $this->session->userdata('examcode');
                           if ($exam_code != '1006' && $exam_code != '1007') {
                           ?>
                           <img src="<?php echo base_url()?>assets/images/bullet2.gif"> It is mandatory for a candidate to opt the examination centre being his/her place of work. If there is no centre at his/her place of work, shall have to opt the centre nearest to his/her place of work. Result of candidate violating this rule or giving wrong information is liable for cancellation.<br>
                           <br />
                           <?php } ?>

                           <!--B) Since the Institute will not be sending the Admit Letter(hard copy) through post, Correct E-mail address is mandatory for receipt of Admit Letter/Hall Ticket through e-mail.--> 
                           <span class="error">
                           <?php //echo form_error('gender');?>
                           </span> 
                        </div>
                        <?php } ?>
                     </div>
                     <div class="box-footer">
                        <div class="col-sm-4 col-xs-offset-3">
                           <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return member_apply_exam();" id="preview">Preview</a>-->
                           <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Preview" onclick="javascript : return  member_apply_exam();">
                           <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                          <!--  <button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button> -->
                           <!-- <a href="<?php echo base_url();?>Applyexam/examdetails/?ExId=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>  -->
                           <!-- <a href="<?php echo base_url();?>bulk/BulkApply/exam_applicantlst" class="btn btn-info" id="preview">Back</a> -->
                           <button type="button" onclick="submit_back_form()" class="btn btn-info" name="getdata">Back</button>
                        </div>
                     </div>
                  </div>
               </div>
            </form>

            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url() ?>/bulk/BulkApply/add_member/" autocomplete="off" id="preview_back_form">
            
                           <input type="hidden" class="form-control" name="regnumber"  value="">
                    
            </form>
            <?php 
               }else{
               
                ?>
            <div class="box box-info">
               <div class="box-header with-border">
                  <h3 class="box-title">Basic Details</h3>
               </div>
               <div class="box-body">
                  <form class="form-horizontal"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApply/add_member/" autocomplete="off">
                     <div class="form-group">
                        <label class="col-sm-3 control-label">Membership No :</label>
                        <div class="col-sm-5">
                           <input type="text" class="form-control"  name="regnumber" placeholder="Registration no"  value="" required>
                        </div>
                        <button name="getdata" >Get Details</button>
                     </div>
                  </form>
               </div>
            </div>
            <?php }?>
         </div>
      </div>
   </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <center>
               <strong>
                  <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4>
               </strong>
            </center>
         </div>
         <div class="modal-body">
         <?php if($this->session->userdata('examcode')!=1009) { ?>
            Dear Candidate,<br><br>
            <p>
               You have opted for the services of a scribe for the above mentioned examination under <strong>Remote Proctored mode</strong>.<br><br>
               For the purpose of approving the scribe and to give you extra time as per rules, you are requested to email Admit letter, Details of the scribe, Declaration and Relevant Doctor's Certificates to <strong>anil@iibf.org.in / sajan@iibf.org.in</strong> at least one week before the exam date<br><br>
               Your application for scribe will be scrutinized and an email will be sent 1-2 days before the exam date, mentioning the status of acceptance of scribe.<br><br>
               You will be required to produce the print out of permission granted, required documents along with the Admit Letter to the test conducting authority (procter).<br><br>
            </p>
            <p style="color:#F00">Click Here - <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_R-150219.pdf" target="_blank">GENERAL GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED & ORTHOPEADICALLY CHALLENGED CANDIDATES</a><br>
            </p>
            Regards,<br>
            IIBF Team.<br>
            <?php } else { ?>
               Dear Candidate,<br><br>
            <p>
               You have opted for the services of a scribe for the above mentioned examination under  <strong>Remote Proctored mode</strong>. Please note the following - <br><br>
               •	Candidates desirous of availing scribe facility need to apply online on the IIBF website by clicking on Apply Now> Apply for scribe.<br><br>
               •	Only the candidates who have applied Online & obtained prior approval for scribe from IIBF will be allowed to appear with the scribe on the day of the examination.<br><br>
               •	Candidates are advised to apply online for scribe well in advance, not later than 3 days before the examination.<br><br>
               •	Please ensure that the scribe fulfils the eligibility criteria as prescribed in the rules/guidelines before applying.
               <br><br>
               •	Please note that, in case, it is found later that the scribe does not fulfil the eligibility criteria, candidature of the applicant will stand cancelled.<br><br>
               •	Please read the rules/guidelines for availing the facility of scribe carefully before applying for Scribe<br><br>
               •	Your application for scribe will be scrutinized and an email will be sent 1-2 days before the exam date, mentioning the status of acceptance of scribe.<br><br>
               •	You will be required to produce the print out of permission granted, required documents along with the Admit Letter to the test conducting authority (proctor).<br><br>
               •	For the Scribe Guidelines Click Here -<a href="https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf">https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf</a><br><br>
            </p>
           
            Regards,<br>
            IIBF Team.<br>
            <?php } ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

<!-- Image Validation -->
<script src="https://iibf.esdsconnect.com/staging/js/validateFile.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>

<script>
   $(document).ready(function(){
   
    $('#scribe_flag').on('change', function(e){
   
      if(e.target.checked){
   
        $('#myModal').modal();
   
      }
   
   });
   
   });
   
</script>
<script>
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
   
      var discount_flag = document.getElementById('discount_flag').value;
   
      var free_paid_flag = document.getElementById('free_paid_flag').value;
   
      if(document.getElementById('elearning_flag_Y').checked){
   
        var Eval = document.getElementById('elearning_flag_Y').value;
   
      }
   
      
   
      if(document.getElementById('elearning_flag_N').checked){
   
        var Eval = document.getElementById('elearning_flag_N').value;
   
      }
   
      
   
      if(cCode != ''){
   
        var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval+'&discount_flag='+discount_flag+'&free_paid_flag='+free_paid_flag;
   
          $.ajax({
   
              url:site_url+'Bulk_fee/getFee/',
   
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
   
      var discount_flag = document.getElementById('discount_flag').value;
   
      var free_paid_flag = document.getElementById('free_paid_flag').value;
   
      if(document.getElementById('elearning_flag_Y').checked){
   
        var Eval = document.getElementById('elearning_flag_Y').value;
   
      }
   
      
   
      if(document.getElementById('elearning_flag_N').checked){
   
        var Eval = document.getElementById('elearning_flag_N').value;
   
      }
   
      
   
      if(cCode != ''){
   
        var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval+'&discount_flag='+discount_flag+'&free_paid_flag='+free_paid_flag;
   
          $.ajax({
   
              url:site_url+'Bulk_fee/getFee/',
   
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

 
if ( $("#selCenterName").length ) {
  var selCenterName = $("#selCenterName").val(); 
   if(selCenterName != "")
   {
      valCentre(selCenterName);
   }   
   }
   
   })


function submit_back_form()
   {
      $("#preview_back_form").submit();
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
    var check_start_date = "2023-07-01"; 
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
        $("#doj_error").html('Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.'); 
        flag = 0;
        return false;
      }
      else if( jdate > check_end_date ) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.'); 
        flag = 0;
        return false;
      }
      else if( jdate > examDate) // && jdate > examDate 
      { 
        //console.log(jdate +'>'+ examDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.');
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
    var datastring='name_of_bank_bc='+name_of_bank_bc+'&ippb_emp_id='+ippb_emp_id+'&mem_type=NM';
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