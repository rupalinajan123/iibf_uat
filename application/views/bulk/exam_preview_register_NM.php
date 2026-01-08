<!-- custom style for datepicker dropdowns -->
<style>
   .modal-dialog {
   position: relative;
   display: table;
   overflow-y: auto;
   overflow-x: auto;
   width: 920px;
   min-width: 300px;
   }
   #confirm .modal-dialog {
   position: relative;
   display: table;
   overflow-y: auto;
   overflow-x: auto;
   width: 420px;
   min-width: 400px;
   }
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
   .mandatory-field,
   .required-spn {
   color: #F00;
   }
   .box-title-hd {
   color: #3c8dbc;
   font-size: 16px;
   margin: 0;
   }
</style>
<!-- Content Wrapper. Contains page content -->
<?php 
   //header('Cache-Control: must-revalidate');
   //header('Cache-Control: post-check=0, pre-check=0', FALSE);
   ?>

<?php
$disaply_class = '';
if ($this->session->userdata['is_elearning_course']=='y') {
   $disaply_class='hidden';
}
?>

<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <br>
   </section>
   <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
      action="<?php echo base_url()?>bulk/BulkApplyNM/Msuccess_reg/">
      <section class="content">
         <div class="row">
            <div class="col-md-12">
               <!-- Horizontal Form -->
               <div class="box box-info">
                  <div class="box-header with-border">
                     <h3 class="box-title">Basic Details</h3>
                     <div style="float:right;">
                        
                     </div>
                  </div>
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
                     <div class="">
                        <div class="form-group">
                           <label for="roleid" class="col-sm-3 control-label">First Name </label>
                           <div class="col-sm-1">
                              <?php echo $this->session->userdata['enduserinfo']['sel_namesub'];?>
                           </div>
                           <div class="col-sm-0">
                              <?php echo $this->session->userdata['enduserinfo']['firstname'];?>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                           <div class="col-sm-5">
                              <?php echo $this->session->userdata['enduserinfo']['middlename'];?>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                           <div class="col-sm-5">
                              <?php echo $this->session->userdata['enduserinfo']['lastname'];?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="box box-info">
                  <div class="box-header with-border">
                     <h3 class="box-title">Contact Details</h3>
                  </div>
                  <div class="box-header with-border">
                     <h6 class="box-title">Office/Residential Address for communication</h6>
                  </div>
                  <div class="box-body">
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Address line1 </label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['addressline1'];?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['addressline2'];?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['addressline3'];?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['addressline4'];?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">District </label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['district']?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">City </label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['city']?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">State </label>
                        <div class="col-sm-2">
                           <?php if(count($states) > 0){
                              foreach($states as $row1){  ?>
                           <?php if($this->session->userdata['enduserinfo']['state']==$row1['state_code']){echo  $row1['state_name'];}?>
                           <?php } } ?>
                        </div>
                        <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode </label>
                        <div class="col-sm-2">
                           <?php echo $this->session->userdata['enduserinfo']['pincode'];?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Date of Birth </label>
                        <div class="col-sm-2">
                           <?php echo date('d-m-Y',strtotime($this->session->userdata['enduserinfo']['dob']));?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Gender </label>
                        <div class="col-sm-2">
                           <?php if($this->session->userdata['enduserinfo']['gender']=='female'){echo 'Female';}?>
                           <?php if($this->session->userdata['enduserinfo']['gender']=='male'){echo  ' Male';}?>
                           <span class="error"><?php //echo form_error('gender');?></span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Qualification </label>
                        <div class="col-sm-4">
                           <?php if($this->session->userdata['enduserinfo']['optedu']=='U'){echo  'Under Graduate';}?>
                           <?php if($this->session->userdata['enduserinfo']['optedu']=='G'){echo  'Graduate';}?>
                           <?php if($this->session->userdata['enduserinfo']['optedu']=='P'){echo  'Post Graduate';}?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Please specify </label>
                        <div class="col-sm-5">
                           <?php 
                              if($this->session->userdata['enduserinfo']['optedu']=='U' && $this->session->userdata['enduserinfo']['eduqual1'])
                              {
                                 if(count($undergraduate))
                                 {
                                  foreach($undergraduate as $row1)
                                  {   
                                    if($this->session->userdata['enduserinfo']['eduqual1']==$row1['qid']){echo  $row1['name'];}
                                    }
                                   } 
                              }?>
                        </div>
                        <div class="col-sm-5">
                           <?php 
                              if($this->session->userdata['enduserinfo']['optedu']=='G' && $this->session->userdata['enduserinfo']['eduqual2'])
                              {
                                 if(count($graduate))
                                 {
                                                     foreach($graduate as $row2)
                                  {   
                                                    if($this->session->userdata['enduserinfo']['eduqual2']==$row2['qid']){echo  $row2['name'];}
                                  }
                                                  } 
                              }?>
                        </div>
                        <div class="col-sm-5">
                           <?php 
                              if($this->session->userdata['enduserinfo']['optedu']=='P' && $this->session->userdata['enduserinfo']['eduqual3'])
                              {
                                 if(count($postgraduate))
                                 {
                                                     foreach($postgraduate as $row3)
                                  {   
                                                    if($this->session->userdata['enduserinfo']['eduqual3']==$row3['qid']){echo  $row3['name'];}
                                                      }
                                                     } 
                              }?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Email </label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['email']?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Phone </label>
                        <div class="col-sm-3">
                           STD Code :
                           <?php echo $this->session->userdata['enduserinfo']['stdcode'];?>
                        </div>
                        <div class="col-sm-2">
                           Phone No :
                           <?php echo $this->session->userdata['enduserinfo']['phone'];?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Mobile </label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['mobile'];?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number</label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['aadhar_card'];?>
                        </div>
                     </div>
                     <?php if(count($bulk_branch_master) > 0) { ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Bank Branch</label>
                        <div class="col-sm-2">
                           <?php if(count($bulk_branch_master) > 0){
                              foreach($bulk_branch_master as $row1){  ?>
                           <?php if($this->session->userdata['enduserinfo']['bank_branch']==$row1['id']){echo  $row1['bname'];}?>
                           <?php } } ?>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if(count($bulk_designation_master) > 0) {?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Bank Designation</label>
                        <div class="col-sm-2">
                           <?php if(count($bulk_designation_master) > 0){
                              foreach($bulk_designation_master as $row1){   ?>
                           <?php if($this->session->userdata['enduserinfo']['bank_designation']==$row1['id']){echo  $row1['dname'];}?>
                           <?php } } ?>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if(count($bulk_payment_scale_master) > 0) {?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Pay Scale</label>
                        <div class="col-sm-2">
                           <?php if(count($bulk_payment_scale_master) > 0){
                              foreach($bulk_payment_scale_master as $row1){   ?>
                           <?php if($this->session->userdata['enduserinfo']['bank_scale']==$row1['id']){echo  $row1['pay_scale'];}?>
                           <?php } } ?>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if(count($bulk_zone_master) > 0) {?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Bank Zone</label>
                        <div class="col-sm-2">
                           <?php if(count($bulk_zone_master) > 0){
                              foreach($bulk_zone_master as $row1){  ?>
                           <?php if($this->session->userdata['enduserinfo']['bank_zone']==$row1['zone_id']){echo  $row1['zone_code'];}?>
                           <?php } } ?>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if($this->session->userdata['enduserinfo']['bank_emp_id'] != '') { ?> <?php } ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Bank Employee Id</label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['bank_emp_id'];?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedphoto'];?>" height="100" width="100" ></label>
                        <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedsignaturephoto'];?>" height="100" width="100"></label>
                        <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['idproofphoto'];?>"  height="100" width="100"></label>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Uploaded Photo</label>
                        <label for="roleid" class="col-sm-3 control-label">uploaded Signature</label>
                        <label for="roleid" class="col-sm-3 control-label">Uploaded ID Proof</label>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Select Id Proof </label>
                        <div class="col-sm-5">
                           <?php  if(count($idtype_master) > 0)
                              {
                                foreach($idtype_master as $idrow)
                                {?>
                           <?php if($this->session->userdata['enduserinfo']['idproof']==$idrow['id']){echo  $idrow['name'];}?>
                           <?php 
                              }
                              }?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">ID No.</label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['enduserinfo']['idNo'];?>
                        </div>
                     </div>
                  </div>
               </div>
               <!---->
               <div class="box box-info">
                  <div class="box-header with-border">
                     <h3 class="box-title">Exam Details</h3>
                  </div>
                  <div class="box-body">
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                        <div class="col-sm-9 ">
                           <?php //echo $this->session->userdata['enduserinfo']['exname'];?>
                           <?php echo str_replace("\\'","",html_entity_decode($this->session->userdata['enduserinfo']['exname']));?>
                           <div id="error_dob"></div>
                           <br>
                           <div id="error_dob_size"></div>
                           <span class="dob_proof_text" style="display:none;"></span>
                           <span class="error"><?php //echo form_error('idproofphoto');?></span>
                        </div>
                     </div>
                     <?php 

                      $colsize = 5; 
                if(!in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) {
                    $colsize = 3;
                }    
                
                        if($this->session->userdata('examcode')!=101

                        && $this->session->userdata('examcode')!=1046

                        && $this->session->userdata('is_elearning_course')=='n'
                        && $this->session->userdata('examcode')!=1010
                        && $this->session->userdata('examcode')!=10100
                        && $this->session->userdata('examcode')!=101000
                        && $this->session->userdata('examcode')!=1010000
                        && $this->session->userdata('examcode')!=10100000)/* && $this->session->userdata('examcode')!=996 */
                        {?>
                     <div class="form-group grayDiv">
                        <label for="roleid" class="col-sm-<?php echo $colsize; ?> text-center"><strong class="black_clr">Subject(s)</strong></label>
                        <?php if(!in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) { ?>
                        <label for="roleid" class="col-sm-6 text-center"><strong class="black_clr">Venue</strong></label>
                     <?php } ?>
                        <label for="roleid" class="col-sm-1 text-center"><strong class="black_clr">Date</strong></label>

                        <?php if(!in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) { ?>
                        <label for="roleid" class="col-sm-1 text-center"><strong class="black_clr">Time</strong></label>
                     <?php } ?>
                     </div>
                     <br />
                     <?php }?>
                     <?php 
                        if(count($compulsory_subjects) > 0 && $this->session->userdata('examcode')!=101

                        && $this->session->userdata('examcode')!=1046   
                        
                        && $this->session->userdata('is_elearning_course')=='n'
                        && $this->session->userdata('examcode')!=1010
                        && $this->session->userdata('examcode')!=10100
                        && $this->session->userdata('examcode')!=101000
                        && $this->session->userdata('examcode')!=1010000
                        && $this->session->userdata('examcode')!=10100000 ) /* && $this->session->userdata('examcode')!=996 */
                        {
                         $i=1;
                         foreach($compulsory_subjects as $k=>$v)
                         {
                         $venue_add_finalstring='';
                        $get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time']));
                        //echo $this->db->last_query();
                        //echo $get_venue_details[0]['venue_name'];
                        $venue_add=$get_venue_details[0]['venue_name'].'*'.$get_venue_details[0]['venue_addr1'].'*'.$get_venue_details[0]['venue_addr2'].'*'.$get_venue_details[0]['venue_addr3'].'*'.$get_venue_details[0]['venue_addr4'].'*'.$get_venue_details[0]['venue_addr5'].'*'.$get_venue_details[0]['venue_pincode'];
                        $venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
                        
                          ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-<?php echo $colsize; ?>"><?php echo $v['subject_name'];?></label>

                        <?php if(!in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) { ?>
                        <span for="roleid" class="col-sm-6"><?php echo $venue_add_finalstring; ?></span>
                     <?php } ?>

                        <center><span for="roleid" class="col-sm-1" style="text-align: right"><?php echo date('d-M-Y',strtotime($v['date']));?></span></center>

                        <?php if(!in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) { ?>
                        <center><span for="roleid" class="col-sm-1" style="text-align: right"><?php echo $v['session_time']; ?></span></center>
                     <?php } ?>

                     </div>
                     <?php 
                        }
                        }?>
                     <?php 
                        if($this->session->userdata['enduserinfo']['elearning_flag'] == 'Y'){
                          $elearning_flag = 'Yes';
                        }else{
                          $elearning_flag = 'No';
                        }
                        ?>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Elearning Flag</label>
                        <div class="col-sm-5 ">
                           <?php echo $elearning_flag;?>
                           <div id="error_dob"></div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                        <div class="col-sm-5 ">
                           <!--<div style="color:#F00">select center first</div>-->
                           <?php echo $this->session->userdata['enduserinfo']['fee'];?>
                           <div id="error_dob"></div>
                           <br>
                           <div id="error_dob_size"></div>
                           <span class="dob_proof_text" style="display:none;"></span>
                           <span class="error"><?php //echo form_error('idproofphoto');?></span>
                        </div>
                     </div>

                     <?php if( $this->session->userdata('examcode') == 994 || $this->session->userdata('examcode') == 996 || $this->session->userdata('examcode') == 1055 || $this->session->userdata('examcode') == 1056 || $this->session->userdata('examcode') == 1046 ) { ?>
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Name of Bank where working as BC</label>
                    <div class="col-sm-5 ">
                     <?php 
                     $get_bank_inst_details=$this->master_model->getRecords('bcbf_old_exam_institute_master',array('institute_id'=>$this->session->userdata['enduserinfo']['name_of_bank_bc']));
                     echo $get_bank_inst_details[0]['institute_name'];?>
                     <div id="error_dob"></div>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Date of commencement of operations/joining as BC</label>
                    <div class="col-sm-5 ">
                     <?php echo ($this->session->userdata['enduserinfo']['date_of_commenc_bc'] != "" ? date("d-m-Y",strtotime($this->session->userdata['enduserinfo']['date_of_commenc_bc'])) : '');?>
                     <div id="error_dob"></div>
                    </div>
                </div>  
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Bank BC ID No</label>
                    <div class="col-sm-5 ">
                     <?php echo $this->session->userdata['enduserinfo']['ippb_emp_id'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Bank BC ID Card</label>
                    <?php //print_r($this->session->userdata['enduserinfo']); ?>
                    <div class="col-sm-5 ">
                        <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['bank_bc_id_card_file_path'];?>"  height="100" width="100"></label>
                        <div id="error_dob"></div>
                    </div>
                </div>
                <?php } ?>
                
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                        <div class="col-sm-5 ">
                           <?php 
                              //$month = date('Y')."-".substr($this->session->userdata['enduserinfo']['exam_month'],4)."-".date('d');
                              $month = date('Y')."-".substr($this->session->userdata['enduserinfo']['exam_month'],4);
                              echo date('F',strtotime($month))."-".substr($this->session->userdata['enduserinfo']['exam_month'],0,-2);
                              ?>
                           <div id="error_dob"></div>
                           <br>
                           <div id="error_dob_size"></div>
                           <span class="dob_proof_text" style="display:none;"></span>
                           <span class="error"><?php //echo form_error('idproofphoto');?></span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Medium *</label>
                        <div class="col-sm-2">
                           <?php 
                              if(count($medium) > 0)
                                       {
                                           foreach($medium as $mrow)
                                           {
                                  if($this->session->userdata['enduserinfo']['medium']==$mrow['medium_code']){echo  $mrow['medium_description'];}
                                }
                                       }?>
                        </div>
                     </div>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Centre Name *</label>
                        <div class="col-sm-2">
                           <?php if(count($center) > 0)
                              {
                                  foreach($center as $crow)
                                  {
                                      if($this->session->userdata['enduserinfo']['selCenterName']==$crow['center_code']){echo $crow['center_name'];}?>
                           <?php }
                              }?>
                        </div>
                     </div>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Centre Code *</label>
                        <div class="col-sm-2">
                           <?php echo $this->session->userdata['enduserinfo']['txtCenterCode'];?>
                        </div>
                     </div>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Exam Mode *</label>
                        <div class="col-sm-2">
                           <?php if($this->session->userdata['enduserinfo']['optmode']=='ON'){echo  ' Online';}?> 
                           <?php if($this->session->userdata['enduserinfo']['optmode']=='OF'){echo  'Offline';}?>
                           <span class="error"><?php //echo form_error('gender');?></span>
                        </div>
                     </div>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Scribe required</label>
                        <div class="col-sm-3">
                           <?php 
                              if($this->session->userdata['enduserinfo']['scribe_flag']=='Y')
                              {
                              echo 'Yes';
                              }
                              else
                              {
                              echo 'No';
                              }?>
                        </div>
                     </div>
                  </div>
                  <div class="box-footer">
                     <div class="col-sm-4 col-xs-offset-3">
                        <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">
                        <a href="<?php echo base_url();?>bulk/BulkApplyNM/add_member" class="btn btn-info" id="preview">Back</a>
                     </div>
                  </div>
               </div>
               <!---->
            </div>
         </div>
      </section>
   </form>
</div>
<script>
   $(document).ready(function(){
   function createCookie(name, value, days) {
     var expires;
   if (days) 
   {
         var date = new Date();
         date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
         expires = "; expires=" + date.toGMTString();
     }
   else
   {
         expires = "";
     }
     document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
   }
   createCookie('member_register_form','1','1');
   });
   
</script>