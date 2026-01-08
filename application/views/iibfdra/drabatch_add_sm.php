<!-- custom style for datepicker dropdowns -->
<style>
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
.mandatory-field, .required-spn {
    color:#F00;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Training Batch Form</h1>
        <?php //echo $breadcrumb;?>
    </section>
    <form class="form-horizontal" name="draExamAddFrm" id="draExamAddFrm" data-parsley-validate="parsley"  method="post"  enctype="multipart/form-data" onsubmit="return dravalidateForm()">    
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Training Batch Details</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                    
                            <?php //echo '<pre>';print_r($agency_center);
                            if($this->session->flashdata('error')!=''){?>
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
                            <?php }
														
														if($error_msg!=''){?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $error_msg; ?>
                            </div>
                            <?php }?> 
            
                           <div class="form-group">
                                <label for="batch_type" class="col-sm-3 control-label">Batch Type <span class="mandatory-field">*</span></label>
                                <div class="col-sm-3">
                                    <input checked type="radio" class="minimal" id="batch_type" name="batch_type"  required value="C" <?php echo set_radio('batch_type', 'C'); ?>>
                                    Combine Batch
                                    <input type="radio" class="minimal" id="batch_type" name="batch_type" required value="S" <?php echo set_radio('batch_type', 'S'); ?>>
                                    Separate Batch 
                                    <span class="error"><?php //echo form_error('gender');?></span>
                                </div>
                            </div>
                            
                            <div class="form-group" id="type_div" style="display: none;">
                                <label for="batch_type" class="col-sm-3 control-label">Batch Type <span class="mandatory-field">*</span></label>
                                <div class="col-sm-3">
                                    <input type="radio" class="minimal" id="batch_type" name="hours"  value="50" <?php echo set_radio('hours', '50');?>>
                                   50hr
                                    <input checked="true" type="radio" class="minimal" id="batch_type" name="hours" value="100" <?php echo set_radio('hours', '100'); ?>>
                                   100hr
                                    <span class="error"><?php //echo form_error('gender');?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label for="center" class="col-sm-3 control-label">Centre Name <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="center_id" name="center_id" required >
                                            <option value="">Select</option>
                                            <?php $validity_to=$validity_from='';
                                            if(!empty($agency_center))
                                            {
                                                foreach($agency_center as $val)
                                                {
                                            
                                                    if($val['city_name']!="" || $val['city_name']!=NULL)
                                                    {
                                                     $val['location_name'] = $val['city_name'];
                                                    } 
                                                    else
                                                    { 
                                                      $val['location_name'] = $val['location_name'];
                                                    }

                                                 if($val['renew_pay_status'] != "")  {//not empty
                                                    if(($val['renew_type'] == 'free' && $val['center_type'] == 'R') || ($val['renew_pay_status'] == 1 && $val['center_type'] == 'R')){ ?>

                                                         <!-- <option  <?php //echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""?>   value="<?php //echo $val['center_id']; ?>"><?php //echo $val['location_name']; ?><?php //echo set_value('location_name'); ?> </option> -->
                                                  <?php     
                                                  //////////////////////////


                                               if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
                                                    $_SESSION['validity_to']=$val['center_validity_to'];?>
                                             <option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( The accreditation period is not defined for this centre, please contact admin. )';?></option>
                                             <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){
                                                           
                                                    
                                             ?>
                                              <option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name']. '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
                                                {
                                                $validity_to=$val['center_validity_to'];
                                                $validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from']))));
                                                
                                                ?>
                                                
                                                 <option  <?php echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['center_id']; ?>"><?php echo $val['location_name']; ?> </option>
                                                
                                                <?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
                                                {?>
                                                
                                                 <option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'R')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'IR')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( IN Review Process. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'AR')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Recommender. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 2)
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Approver. )'; ?></option>
                                                 <?php }elseif($val['center_validity_from'] > date('Y-m-d'))
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Your Accreditation period is not started. )'; ?></option>
                                                 <?php }
                                               
                                     /////////////////////////////////////////////////////////////////

                                                      } elseif(($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'R' && $val['renew_type'] != 'free'){ ?>
                                                         <option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( Your renewal process payment is pending. )';?><?php echo set_value('location_name'); ?></option>
                                                   <?php }elseif(($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'T'){ ?>
                                                        <option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( Your renewal process payment is pending. )';?></option>
                                                   <?php }elseif($val['renew_pay_status'] == 1 && $val['center_type'] == 'T'){ 

                                                    if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
                                                    $_SESSION['validity_to']=$val['center_validity_to'];?>
                                             <option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( The accreditation period is not defined for this centre, please contact admin. )';?></option>
                                             <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){
                                                           
                                                    
                                             ?>
                                              <option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name']. '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
                                                {
                                                $validity_to=$val['center_validity_to'];
                                                $validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from']))));
                                                
                                                ?>
                                                
                                                 <option  <?php echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['center_id']; ?>"><?php echo $val['location_name']; ?> </option>
                                                
                                                <?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
                                                {?>
                                                
                                                 <option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'R')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'IR')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( IN Review Process. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'AR')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Recommender. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 2)
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Approver. )'; ?></option>
                                                 <?php }elseif($val['center_validity_from'] > date('Y-m-d'))
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Your Accreditation period is not started. )'; ?></option>
                                                 <?php }
														} 
                                                    } //not empty renew payemnt status
													else
													{//empty renew payemnt status																				

                                                if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
                                                    $_SESSION['validity_to']=$val['center_validity_to'];?>
                                             <option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( The accreditation period is not defined for this centre, please contact admin. )';?></option>
														<?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){ ?>
                                              <option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name']. '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
                                                {
                                                $validity_to=$val['center_validity_to'];
                                                $validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from']))));
                                                ?>
                                                 <option  <?php echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['center_id']; ?>"><?php echo $val['location_name']; ?> </option>
                                                
                                                <?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
                                                {?>
                                                 <option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'R')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'IR')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( IN Review Process. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'AR')
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Recommender. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 2)
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Approver. )'; ?></option>
                                                 <?php }elseif($val['center_validity_from'] > date('Y-m-d'))
                                                 {?>
                                                 <option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Your Accreditation period is not started. )'; ?></option>
                                                 <?php }
													} 
												}
                                            }
                                            ?>
                                            
                                            
                                            <?php /*if(count($center_master) > 0){
                                                foreach($center_master as $center){ 
                                                   $validity_to = $center['center_validity_to'];
                                                    ?>
                                                    <option <?php if($center['center_status'] == 'A' && $center['pay_count'] > 0 && $center['center_validity_from'] != '' && $center['center_validity_to'] != '' && date('Y-m-d') < $center['center_validity_to']) { }else { echo "disabled"; } ?> value="<?php echo $center['center_id'];?>" <?php echo  set_select('exam_center', $center['center_id']); ?>><?php echo $center['location_name'].'-'.$center['center_type'];?>
                                                        <?php if($center['center_validity_from'] == '' && $center['center_validity_to'] == ''){ echo '( The accreditation period is not defined for this centre, please contact admin. )'; } 
                                                        else if($center['center_status'] =='R'){
                                                            echo '( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; 
                                                        }
                                                        else if(date('Y-m-d') > $center['center_validity_to']){
                                                            echo "swati";
                                                            if($center['center_type'] == 'R'){
                                                                 echo  '( The accreditation period for the selected centre is expired, please contact admin. )'; 

                                                            }else{
                                                                 echo  '( The accreditation period for the selected centre is expired, kindly click here to renew/extend the accreditation period or please contact admin. )'; 
                                                            }
                                                           
                                                        }
                                                        else if($center['center_status'] == 'A' && $center['pay_count'] == 0){
                                                            echo  '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; 
                                                        }
                                                        // else{
                                                        //      echo  '(The selected centre is not approved by the admin, kindly contact with admin. )'; 
                                                        // } ?>
                                                    
                                                    </option>
                                        <?php } }*/ ?>
                                        </select>
                                    
                                      <div style="display: none;" id="validdate"><?php  echo $validity_to;?></div>
                                      <input type="hidden" value="<?php echo $validity_to; ?>" id='validity_to' name='validity_to'/>
                                      <input type="hidden" value="<?php echo $validity_from; ?>" id='validity_from' name='validity_from'/>
                                     </div>
                                </div>

                                <!--  <div class="form-group">
                                    <label for="incpector" class="col-sm-3 control-label">Inspector Name <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="inspector_id" name="inspector_id" required >
                                            <option value="">Select</option>
                                           
                                        </select>
                                      
                                    </div>
                                </div> -->
                            
                            <div class="form-group">
                                <label for="batch_name" class="col-sm-3 control-label">Batch Name <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="batch_name" name="batch_name" placeholder="Batch Name" required  value="<?php echo set_value('batch_name');?>" data-parsley-maxlength="30" autocomplete="off" maxlength="30">
                                    <span class="error"><?php //echo form_error('middlename');?></span>
                                </div><!-- (Max 30 Characters)  -->
                            </div>
                            
                           
                            <div class="form-group">
                                    <label for="training_period" class="col-sm-3 control-label">Batch Training Period  <span class="mandatory-field">*</span> </label>
                                    <div class="col-sm-2">
                                        From
                                        <input type="text" class="form-control" id="batch_from"  name="batch_from_date" placeholder="Training From Date" required value="<?php echo set_value('batch_from_date');?>" autocomplete="off"  />
                                        <span class="error"><?php //echo form_error('batch_from_date');?></span>
                                    </div> 
                                    <div class="col-sm-2">
                                        To
                                        <?php
                                        //$sendstring = $examdt."*".$traininglimit;
                                       // $sendstring= '3/5/2019';
                                        ?>
                                        <input type="text" class="form-control" id="batch_to"  name="batch_to_date" placeholder="Training To Date" required value="<?php echo set_value('batch_to_date');?>" autocomplete="off" onChange="chk_days()" />
                                        <span class="error"><?php //echo form_error('training_to');?></span>
                                        <!-- <input type="hidden" name="examdate" value="<?php //echo $examdt;?>" />
                                        <input type="hidden" name="traininglimit" value="<?php //echo $traininglimit;?>" /> -->
                                  
                                    </div> 
                                     <div class="col-sm-1" style="padding-top: 14px; text-align: center;">
                                      <span id="numdays2" class="form-control"> 0 Days</span> 
                                     </div>
                                </div>
                                
                            <div class="form-group datepairtimes">
                                <label for="timing_of_training" class="col-sm-3 control-label">Timing Of Training<span class="mandatory-field">*</span></label>
                                <div class="col-sm-2">
                                        From
                                        <input type="text" class="form-control time start" id="timing_from"  name="timing_from"  required value="<?php echo set_value('timing_from')?>" autocomplete="off" onChange="chk_from_time()"  maxlength="10" />
                                        
                                    </div> 
                                    <div class="col-sm-2">
                                        To
                                        <input type="text" class="form-control time end timing_to" id="timing_to"  name="timing_to"  required value="<?php echo set_value('timing_to')?>" autocomplete="off"  maxlength="10"  onChange="chk_from_time()" />
                                   
                                    </div> 
                                    <div class="col-sm-1 mytimecount" style="padding-top: 14px; text-align: center;">
                                      <span id="numtime2" class="form-control"> 0 hr</span> 
                                     </div>
                            </div>
                            <div class="form-group">
                                <label for="training_medium" class="col-sm-3 control-label">Training Language <!--<span class="mandatory-field">*</span>--></label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="training_medium" name="training_medium" >
                                        <option value="">Select</option>
                                        <?php if(count($medium_master) > 0){
                                                foreach($medium_master as $medium){     ?>
                                                    <option value="<?php echo $medium['medium_description'];?>" <?php echo  set_select('exam_medium', $medium['medium_description']);  ?>  <?php echo (set_value('training_medium')==$medium['medium_description'])?" selected=' selected'":""?>  ><?php echo $medium['medium_description'];?></option>
                                    <?php } } ?>
                                    </select> in the absence of a clear choice English will be presumed as medium opted
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="total_candidates" class="col-sm-3 control-label">No. Of Candidates <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" required id="total_candidates" name="total_candidates" placeholder="Total Candidates" value="<?php echo set_value('total_candidates');?>" autocomplete="off" maxlength="3" minlength="2" >
                                  <span id="error"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="faculty_name" class="col-sm-3 control-label">1st Faculty Name(The one who will be conducting the training) <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="faculty_name" name="faculty_name" placeholder="Faculty Name" required value="<?php echo set_value('faculty_name');?>" autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                  <span class="error"><?php //echo form_error('faculty_name');?></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="faculty_qualification" class="col-sm-3 control-label">1st Faculty Qualification <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="faculty_qualification" name="faculty_qualification" placeholder="Faculty Qualification" required value="<?php echo set_value('faculty_qualification');?>" autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                  <span class="error"><?php //echo form_error('faculty_qualification');?></span>
                                </div>
                            </div>
                            
                            <!-- New fileds added by manoj as discussion on 4 apr 2019 -->
                            <div class="form-group">
                                <label for="faculty_name" class="col-sm-3 control-label">2nd Faculty Name (The one who will be conducting the training)</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="faculty_name2" name="faculty_name2" placeholder="Faculty Name" value="<?php echo set_value('faculty_name2');?>" autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="faculty_qualification" class="col-sm-3 control-label">2nd Faculty Qualification</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="faculty_qualification2" name="faculty_qualification2" placeholder="Faculty Qualification" value="<?php echo set_value('faculty_qualification2');?>" autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                </div>
                            </div>                            
                           <!-- End of New fileds -->  
                           
              <div class="box-header with-border">
                   <div class="col-sm-12"> 
                    <h3 class="box-title" style="color:#333; width:100%; ">
                     <center> &nbsp;<b>Venue of Training Batch</b> </center></h3>
                  </div>
                  <br>
                  </div>
                   <div class="form-group">
                    </div>        
                        <!--    <div class="form-group">
                                <label for="address_heading" class="col-sm-3 control-label">Candidate Address for communication</label>
                            </div>-->
                            
                            
                              <?php if($inst_registration_info[0]['main_city']!="")
                              {
                                  $city = $inst_registration_info[0]['city'];
                              } 
                              else
                              { 
                                  $city = $inst_registration_info[0]['main_city'];
                              }
                               ?>

                            <div class="form-group">

                                 <label for="state" class="col-sm-3 control-label">State <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                               <!--  <select class="form-control" id="csstate" placeholder="State" readonly   name="csstate" required  disabled >
                                <option value="">Select</option>
                                <?php if(count($states) > 0){
                                           foreach($states as $row1){  ?>
                                <option value="<?php //echo $row1['state_code']; ?>" <?php if($row1['state_code'] == $inst_registration_info[0]['main_state']){ ?> selected="selected" <?php } ?>><?php //echo $row1['state_name'];?></option>
                                <?php } } ?>
                                </select> -->
                                <input class="form-control" id="cstate" value="<?php echo set_value('csstate');?>" placeholder="State" readonly   name="csstate" required  disabled >
                                <input type="hidden" class="form-control" id="ccstate" readonly placeholder="State" value="<?php echo set_value('state');?>"  name="state" >
                                <span class="error"></span>
                                </div>

                               
                            </div>
                            
                            <div class="form-group">
                                <label for="district" class="col-sm-3 control-label">District <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" readonly id="cdistrict" name="cdistrict" readonly placeholder="District" value="<?php echo set_value('cdistrict');?>"  >
                                  <span class="error"></span>
                                </div>
                            </div>   

                            <div class="form-group">
                               
                                <label for="city" class="col-sm-3 control-label">City <span class="mandatory-field">*</span></label>
                                <div class="col-sm-3">
                                  <input type="text" class="form-control" readonly placeholder="City" id="ccity"  name="cscity" value="<?php echo set_value('cscity');?>">  

                                   <input type="hidden" class="form-control" readonly placeholder="City" id="cccity"  name="ccity" value="">
                                  <span class="error"></span>
                                </div>
                            
                              <label for="pincode" class="col-sm-3 control-label">Pincode <span class="mandatory-field">*</span></label>
                                <div class="col-sm-2">
                                 <input type="text"  placeholder="pincode" class="form-control" onkeypress="return(number(event));"  name="cpincode" id="cpincode" value=""  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dracheckpin autocomplete="off" data-parsley-trigger-after-failure="focusout">
                                  <span class="error"></span>
                                </div>
                            </div>            
                            
                            <div class="form-group">
                                <label for="address_line1" class="col-sm-3 control-label">Address line 1</span><span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control address_fields" id="addressline1" name="addressline1" required placeholder="Address line 1" value="<?php 
                                    echo set_value('addressline1');
                                    ?>"  data-parsley-maxlength="30" autocomplete="off" maxlength="30">
                                  <span class="error"><?php //echo form_error('addressline1');?></span>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                                <label for="address_line2" class="col-sm-3 control-label">Address line 2</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control address_fields" id="addressline2" name="addressline2" placeholder="Address line 2" data-parsley-maxlength="30" value="<?php  echo set_value('addressline2');?>" autocomplete="off" maxlength="30">
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_line3" class="col-sm-3 control-label address_fields">Address line 3</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line 2" data-parsley-maxlength="30" value="<?php   echo set_value('addressline3');?>"  autocomplete="off" maxlength="30">
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_line4" class="col-sm-3 control-label address_fields">Address line 4</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line 2" data-parsley-maxlength="30" value="<?php echo set_value('addressline4');?>"   autocomplete="off" maxlength="30">
                                  <span class="error"><?php //echo form_error('addressline2');?></span>
                                </div>
                            </div>
                             
                             <div class="form-group">
                                <label for="last_name" class="col-sm-3 control-label">Contact Person Name <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" required placeholder="Contact Person Name" value="<?php echo set_value('contact_person_name');?>" data-parsley-maxlength="30" autocomplete="off" maxlength="30">
                                  <span class="error"><?php //echo form_error('lastname');?></span>
                                </div><!-- (Max 30 Characters)  -->
                            </div>
                            
                           <div class="form-group">
                                    <label for="contact_person_phone" class="col-sm-3 control-label">Contact Person Phone Number<span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="tel" class="form-control" id="contact_person_phone" name="contact_person_phone" placeholder="Contact Person Phone" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php echo set_value('contact_person_phone');?>"   required autocomplete="off" size="10" maxlength="10" data-parsley-trigger-after-failure="focusout"> 
                                        <span class="error"><?php //echo form_error('mobile');?></span>
                                    </div>
                                </div> 
                                
                            <div class="form-group">
                                <label for="name_of_bank " class="col-sm-3 control-label">Name of Bank / Others <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control" id="name_of_bank" name="name_of_bank" placeholder="Name Of Bank" required value="<?php echo set_value('name_of_bank');?>" autocomplete="off" data-parsley-maxlength="30" maxlength="30">
                                  <span class="error"><?php //echo form_error('name_of_bank');?></span>
                                </div>
                            </div>
                            
                           
                            <div class="form-group">
                            <label for="remarks " class="col-sm-3 control-label">Remarks </label>
                            <div class="col-sm-5">
                            <textarea style="width:100%; text-align:left;" name="remarks" id="remarks" class="control-label" data-parsley-maxlength="500" maxlength="500" ><?php echo set_value('remarks');?></textarea>
                              </div>
                          </div>
                            
						<div class="box-header with-border">
							<div class="col-sm-12"> 
								<h3 class="box-title" style="color:#333; width:100%; ">
								<center> &nbsp;<b>Offline / Online Batch</b> </center></h3>
							</div><br>
						</div><div class="form-group"></div>							
						
						<!--########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################-->
						<div class="form-group">								
							<label for="" class="col-sm-3 control-label">Offline / Online Batch <span class="mandatory-field">*</span></label>
							<div class="col-sm-5">
								<label class="radio-inline" style="padding-top: 0;margin-top: -8px; margin-right:20px;">
									<input type="radio" name="batch_online_offline_flag" id="batch_online_offline_flag0" checked <?php echo set_radio('batch_online_offline_flag', '0'); ?> value="0" required onchange="batch_online_users_show(this.value)"> Offline
								</label>
								<label class="radio-inline" style="padding-top: 0;margin-top: -8px;"> 
									<input type="radio" name="batch_online_offline_flag" id="batch_online_offline_flag1" <?php echo set_radio('batch_online_offline_flag', '1'); ?> value="1" required onchange="batch_online_users_show(this.value)"> Online
								</label>
								<span class="error"></span>
							</div>
						</div>
						
						<?php 
							$login_id_arr = $login_pass_arr = array();
							if(set_value('batch_online_login_ids') != "") { $login_id_arr = set_value('batch_online_login_ids'); }
							if(set_value('batch_online_login_pass') != "") { $login_pass_arr = set_value('batch_online_login_pass'); } 
						?>
						<div id="batch_online_users_outer" style="display:none;">
							<?php for($i=0; $i < 4; $i++)
							{	?>
								<div class="form-group">
									<label class="col-sm-3 control-label"><?php if($i == 0) { echo "User Details <span class='mandatory-field'>*</span>"; } else { echo "&nbsp"; }?></label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="batch_online_login_ids<?php echo $i; ?>" name="batch_online_login_ids[]" required="" value="<?php if(isset($login_id_arr[$i]) && $login_id_arr[$i] != "") { echo $login_id_arr[$i]; } ?>" autocomplete="off" maxlength="100" placeholder="Login ID *">
									</div>
									<div class="col-sm-3">
										<input type="password" class="form-control" id="batch_online_login_pass<?php echo $i; ?>" name="batch_online_login_pass[]" required="" value="<?php if(isset($login_pass_arr[$i]) && $login_pass_arr[$i] != "") { echo $login_pass_arr[$i]; } ?>" minlength="8" maxlength="100" autocomplete="off" placeholder="Password *">
										<span class="error_batch_online_login_pass<?php echo $i; ?>"></span>
									</div>
								</div>
							<?php } ?>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Name of the on-line training platform used <span class="mandatory-field">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="online_training_platform" name="online_training_platform" required="" value="<?php echo set_value('online_training_platform'); ?>" autocomplete="off" maxlength="250" placeholder="Name of the on-line training platform used *">
									<label class="error" id="err_online_training_platform" style="font-size: 0.85em; font-weight: 500; line-height: 1em; display: block; margin: 2px 0 0 0; "></label>
								</div>
							</div>
						</div>
						<!--########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################--->
						
                         </div><!--.box-body-->
                         </div><!--.box-info-->
                         
                        <!-- <div class="box box-info">
                             <div class="box-header with-border">
                                <h3 class="box-title">Security </h3>
                             </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="roleid" class="col-sm-3 control-label">Security Code <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-2">
                                      <input type="text" name="code" id="code" required class="form-control" data-parsley-pattern="/^[0-9a-zA-Z]+$/">
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
                            </div><!--.box-body
                         </div><!--.box-info--> 
                         
              <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                     <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">  
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                    </div>
              </div>
           </div>
        </div>
      </div>
     
      
      
    </section>
    </form>
  </div>
  
<!-- <link rel="stylesheet" href="//jonthornton.github.io/jquery-timepicker/jquery.timepicker.css"> -->
<!-- Updated JavaScript url -->
<style>
option:disabled {
  color: #999;
}
.days_count{
    font-size: 14px;   
}
.days_count span{
    font-size: 16px;
    font-weight: 900;
}

.time_count{
    font-size: 14px;   
}
.time_count span{
    font-size: 16px;
    font-weight: 900;
}
.mytimecount{
	display:none;	
}
</style>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/timepicker.css">
<!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/jQuery/jquery.timepicker.css">
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jquery.timepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/jQuery/jquery.timepicker.min.css">
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jquery.timepicker.min.js"></script> -->
<!-- <script src="<?php //echo base_url()?>assets/admin/plugins/jQuery/jquery.datepair.js"></script>

<script src="<?php //echo base_url()?>assets/admin/plugins/jQuery/datepair.js"></script> -->
<script src="https://www.jonthornton.github.io/jquery-timepicker/jquery.timepicker.js"></script>
<script src="https://www.jonthornton.github.io/Datepair.js/dist/jquery.datepair.js"></script>

<script src="https://www.jonthornton.github.io/Datepair.js/dist/datepair.js"></script>

<script type="text/javascript">
	<!--########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################--->
	function batch_online_users_show(flag)
	{
		if(flag == 1) 
		{ 
			$("#batch_online_users_outer").css("display","block"); 
			$("[name='batch_online_login_ids[]']").prop('required',true);
			$("[name='batch_online_login_pass[]']").prop('required',true);
			$("#online_training_platform").prop('required',true);
		}
		else 
		{ 
			$("#batch_online_users_outer").css("display","none"); 
			$("[name='batch_online_login_ids[]']").prop('required',false);
			$("[name='batch_online_login_pass[]']").prop('required',false);
			$("#online_training_platform").prop('required',false);
		}			
	}
	
	$(document).ready(function()
	{
    var selected_flag_val = $("input[type=radio][name='batch_online_offline_flag']:checked").val();
		if(typeof  selected_flag_val === 'undefined') { var flag = 0; } else { var flag = selected_flag_val; }
		batch_online_users_show(flag);
	});
	<!--########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################--->
 
 
// Count number of days Added by Manoj 
	function GetDays()
	{
        var batch_from = new Date(document.getElementById("batch_from").value);
        var batch_to = new Date(document.getElementById("batch_to").value);
        return parseInt((batch_to - batch_from) / (24 * 3600 * 1000));
}

	function chk_days()
	{
    if(document.getElementById("batch_to").value != ''){
        //document.getElementById("numdays2").value=GetDays();
		var days = GetDays();
		if($.isNumeric(days)){
			days = days + 1;
        	$("#numdays2").html(days+' Days');
		}else{
			$("#numdays2").html('0 Days');
		}
    } 
}
 
$("#numtime2").html('0  hrs');

	function conver_to_24_hr(time)
	{
	//var time = $("#starttime").val();
	var hours = Number(time.match(/^(\d+)/)[1]);
	var minutes = Number(time.match(/:(\d+)/)[1]);
	var AMPM = time.match(/\s(.*)$/)[1];
	if(AMPM == "PM" && hours<12) hours = hours+12;
	if(AMPM == "AM" && hours==12) hours = hours-12;
	var sHours = hours.toString();
	var sMinutes = minutes.toString();
	if(hours<10) sHours = "0" + sHours;
	if(minutes<10) sMinutes = "0" + sMinutes;
	console.log(sHours + ":" + sMinutes);
	return (sHours + ":" + sMinutes);
}

	function timediff()
	{
 var from_time = $('#timing_from').val();
 var to_time =   $('#timing_to').val();
 
var from_time_1 = from_time.replace("am", " AM");
var from_time_2 = from_time_1.replace("pm", " PM");
 
 var to_time_1 = from_time.replace("am", " AM");
var to_time_2 = to_time_1.replace("pm", " PM");
 
console.log(to_time_2);
 
var timeStart = new Date("01/01/2007 " + from_time_2);
var timeEnd = new Date("01/01/2007 " + to_time_2);

var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds

var minutes = diff % 60;
var hours = (diff - minutes) / 60;

console.log(diff);


var diff = ( new Date("1970-1-1 " + to_time_2) - new Date("1970-1-1 " + from_time_2) ) / 1000 / 60 / 60; 
 console.log(diff);
// var selectedDurationSpan = $(".ui-timepicker-wrapper.timing_to").find("li.ui-timepicker-selected").find("span.ui-timepicker-duration");

  var selectedDurationSpan = $(".ui-timepicker-with-duration").find(".ui-timepicker-list").find(".ui-timepicker-selected").find('.ui-timepicker-duration').html();
 
 
 //ui-timepicker-with-duration
 
 //ui-timepicker-with-duration
 //ui-timepicker-list
 //ui-timepicker-am ui-timepicker-selected
  
// var d2 = new Date('2038-01-19 03:14:00');
 //var d1 = new Date('2038-01-19 03:10:00');

 //var seconds =  (d2- d1)/1000;
		if(selectedDurationSpan != '' &&  selectedDurationSpan != 'undefined' && selectedDurationSpan != undefined)
		{			
//var str = "Visit Microsoft!";
if(selectedDurationSpan.length > 0){
var initial_time_str = selectedDurationSpan.replace("(", "");
var result_time_diff = initial_time_str.replace(")", "");
	 
 $("#numtime2").html(result_time_diff);
 }else{
	 $("#numtime2").html('2  hrs');
	 }
 }else{
	 $("#numtime2").html('2  hrs'); 
 }	
}

	function chk_from_time()
	{
	$("#numtime2").html('2  hrs');	
  var from_time =  $('#timing_from').val();
	var to_time =  $('#timing_to').val();
	if(from_time != '' &&  to_time != ''){
		if(from_time == to_time ){
			$('#timing_to').val('');	
			$("#numtime2").html('0  hrs');	
		}else{
			//$("#numtime2").html('0 hrs');
			timediff();				
		}      
	}
}
  
	$(document).ready(function() 
	{
  //alert($('#validdate').text());
  
		$('#batch_from').datepicker(
		{
       format: "yyyy-mm-dd",
       startDate: '+3d',//$('#validity_from').val(),
       endDate:'+5d',//$('#validity_to').val(),
       autoclose: true
      // endDate: 6,
        // beforeShowDay: noWeekendsOrHolidays
    }).attr('readonly', 'readonly');
    
		$('#batch_to').datepicker(
		{
       format: "yyyy-mm-dd",
       startDate: '+5d',//$('#validity_from').val(), //'+6d',
       endDate:$('#validity_to').val(),
       //endDate:$('#validity_to').val(),
       autoclose: true
        // beforeShowDay: noWeekendsOrHolidays
    }).attr('readonly', 'readonly');
// validation for no of candidates     


   // modify by Manoj MMM
   $('.datepairtimes .time').timepicker({
        'showDuration': true,
        'timeFormat': 'g:ia',
        'option': true,
				'explicitMode':true,
				minuteStep: 1,
				/* showInputs: false, */
				disableFocus: true
    });
    
//$('.datepairtimes').datepair();
   
  /*  $('#timing_from').click(function(e){
         document.onkeydown = function (e)
         
         
          
 {
  return true;
 }
    });
 
   $('#timing_from').timepicker({
   // timeFormat: 'h:mm p',
    interval: 60,
    minTime: '7',
    maxTime: '8:00pm',
    defaultTime: '7',
    startTime: '10:00',
    dynamic: false,
    dropdown: true,
    //showDuration: true,
    scrollbar: true

});     

 $('#timing_to').timepicker({
  //  timeFormat: 'h:mm p',
    interval: 60,
    minTime: '7:00am',
    maxTime: '8:00pm',
    defaultTime: '7',
    startTime: '10:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
}); */



 // on change of center get inspector master details and center details
    $('#center_id').change(function()
		{
      var center_id = $(this).val();
//alert(center_id);
      // AJAX request
      $.ajax({
        url:'<?=base_url()?>iibfdra/TrainingBatches/getcenterDetails',
        method: 'post',
        data: {center_id: center_id},
        dataType: 'json',
        success: function(response)
				{
//alert(response);
         // Remove options 
          //$('#sel_user').find('option').not(':first').remove();
          //$('#sel_depart').find('option').not(':first').remove();

          // Add options
          $.each(response,function(index,data)
					{
           // alert(data['state_code']);
           //  alert(data['state_name']);
            // $('#inspector_id').append('<option value="'+data['id']+'">'+data['inspector_name']+'</option>');
            $('#addressline1').val(data['address1']);
            $('#addressline2').val(data['address2']);
            $('#addressline3').val(data['address3']);
            $('#addressline4').val(data['address4']);
            //$('#ccity').val(data['city_name']);
            if(data['city']!='')
            {

                 $('#ccity').val(data['city_name']);

            }else{
                $('#ccity').val(data['location_name']);
            }
           
            $('#cccity').val(data['location_name']);
            $('#cdistrict').val(data['district']);
            $('#cstate').val(data['state_name']);
            $('#ccstate').val(data['state_code']);
            $('#cpincode').val(data['pincode']);
            $('#contact_person_name').val(data['contact_person_name']);
            $('#contact_person_phone').val(data['contact_person_mobile']);
            
            
            // custom validation code added by Manoj MMM    
            var validity_from_ck = '+3d';//data['validity_chk_from'];
            var validity_to_ck = '+5d';//data['center_validity_to'];
            //alert(validity_to_ck);
            // console.log(date_string);
            //var date_string = userDate;       
            $('#validity_from').val(validity_from_ck);
            
            $('#batch_from').val('');
            $('#batch_to').val('');
            
            $('#batch_from').datepicker('setStartDate', validity_from_ck);
            $('#batch_from').datepicker('setEndDate', validity_to_ck);
            $('#batch_to').datepicker('setStartDate', validity_from_ck);
            $('#batch_to').datepicker('setEndDate', data['center_validity_to']);
            
             $('.datepairtimes .time').timepicker({
                'showDuration': true,
                'timeFormat': 'g:ia',
                'option': true,
								'explicitMode':true,
								minuteStep: 1,
								/* showInputs: false, */
								disableFocus: true
            });
            
             $('.datepairtimes').datepair();            
            
          });
        }
     });
   });

		$('#timing_from').keypress(function() 
		{
    return false;
    });
		$('#timing_to').keypress(function() 
		{
    return false;
    });

		$('#batch_from').change(function() 
		{
  var date2 = $('#batch_from').datepicker('getDate', '+5d'); 
  var vval = $('#batch_from').val();
  //alert(date2);   
  if(vval != ''){                         
     date2.setDate(date2.getDate()+5);                              
     $('#batch_to').datepicker('setDate', date2);                               
     $('#batch_to').datepicker('setStartDate', date2);
         $('#batch_to').datepicker({
            autoclose: true, 
            format: 'dd-mm-yyyy',                                                       
            dateFormat: 'dd-mm-yyyy',                                       
            startDate: date2,
            minDate: date2
        }).attr('readonly', 'readonly');
    
    chk_days(); 
        
  }
});
    
    
// validation for no of candidates      
 $('input[name="total_candidates"]').keyup(function(e)
                                {
  if (/\D/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
  }
  
});

 $('input[name="total_candidates"]').change(function(e)
                                {
   if ($(this).val() < 10 ){
    $('span#error').text('No. of candidates should be greater than 10');
    $(this).val('10');
  }
  else if($(this).val() > 100 ){
$('span#error').text('No. of candidates are less than 100');
$(this).val('100');
  }
  else{
    $('span#error').text('');
  }
});
 //check wich type is selected       
		$("input[name='batch_type']").click(function() 
		{			
        var test = $(this).val();
        // alert(test);
        if(test == 'S'){
             $("div#type_div").show();
        }
        else{
             $("div#type_div").hide();
        }
       
    });

		$("#dateofbirth").change(function()
		{
            var sel_dob = $("#dateofbirth").val();
            if(sel_dob!='')
            {
                var dob_arr = sel_dob.split('-');
                if(dob_arr.length == 3)
                {
                    chkage(dob_arr[2],dob_arr[1],dob_arr[0]);   
                }
                else
                {   alert('Select valid date'); }
            }
        });
		
		function isUrlValid(url) 
		{
			return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
		}

		$('input[name="online_training_platform"]').keyup(function(e)
		{
			if(isUrlValid($(this).val()) == false)
			{
				$("#err_online_training_platform").text("Please enter valid url");
				return false;
			}
			else
			{
				$("#err_online_training_platform").text("");
				return true;
			};
			
		});
		
        //change captcha
		$('#new_captcha').click(function(event)
		{
            event.preventDefault();
            var sdata = {'captchaname':'draexamcaptcha'};
            $.ajax({
                type: 'POST',
                data: sdata,
                url: site_url+'iibfdra/DraExam/generatecaptchaajax/',
                success: function(res)
                {   
                    if(res!='')
                    {$('#captcha_img').html(res);
                    }
                }
            });
        });
		
        //if invalid captcha entered keep unchecked exam mode disabled
        if( $("input[name='exam_mode']:checked").length > 0) {
            $("input[name='exam_mode']:not(:checked)").attr('disabled', true);
        }
        // change gender on chnage of name subtitle 
       // addressline4
        
        $("body").on("contextmenu",function(e){
         return false;
        });
    });
    
// custome validtion add by manoj
	$(function()
	{    
    // disable space    
    /*$('#contact_person_phone').on('keypress', function(e) {
        //32 is key code for space
        if (e.which == 32)
            return false;
    });*/
 
    // $('.address_fields').keyup(function()
    // {
    //  var yourInput = $(this).val();
    //  re = /[`~!@#$%^&*()_|+-=?;:'",.<>\{\}\[\]\\\/]/gi;
    //  var isSplChar = re.test(yourInput);
    //  if(isSplChar)
    //  {
    //      var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
    //      $(this).val(no_spl_char);
    //  }
    // });
    //--------------------------------
    
});
    
	$("#contact_person_phone").keypress(function(event)
	{
            var x = $(this).val();
        if(x.indexOf('0')==0){
            $('#contact_person_phone').val('');
            return false;
        }   
        if (event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46) {
            return true;
        }else{
            return false;
        }
    });
    
</script> 
<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url()?>js/validation_dra_batch.js"></script>