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
<form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>RELApplyexam/add_record/">

<input type="hidden" name="regid" id="regid" value="<?php //echo $this->session->userdata('mregid_applyexam');?>"> 
<input type="hidden" name="processPayment" id="processPayment" value="1"> 
     
   
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
             <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	 <?php echo $admitcard_info[0]['mem_mem_no'];?>
                    </div>
                </div>-->
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name </label>
                     <div class="col-sm-3">
					<?php echo $admitcard_info[0]['mam_nam_1'];?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                </div>
               
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email </label>
                     <div class="col-sm-3">
					<?php if(isset($member_info[0]['email'])){echo $member_info[0]['email'];}?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile </label>
                     <div class="col-sm-3">
					<?php if(isset($member_info[0]['mobile'])){echo $member_info[0]['mobile'];}?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
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
                     <?php echo str_replace("\\'","",html_entity_decode($exam_name[0]['description']));?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                
                <input type="hidden" name="selCenterName" id="selCenterName" value="<?php echo $center_code;?>" />
                <input type="hidden" name="eprid" id="eprid" value="<?php echo $exam_period;?>" />
                <input type="hidden" name="examcode" id="examcode" value="<?php echo $exam_code;?>" />
                
               
				
                    <div class="form-group grayDiv">
                          <label for="roleid" class="col-sm-3 control-label"><strong class="black_clr">Subject(s)</strong></label>
                            <div class="col-sm-4 text-center">Venue</div>
                            <div class="col-sm-2 text-center">Date</div>
                            <div class="col-sm-2 text-center">Time</div>
                            
                            
                      </div>
                    
                    <?php  
						if(count($compulsory_subjects) > 0){
							$i=1;
					   		foreach($compulsory_subjects as $subject){
					?>           
                    <div class="form-group borderDiv">
                      <label for="roleid" class="col-sm-3 control-label">
                      	<strong class="black_clr"><?php echo $subject_name[0]['subject_description']?></strong>
                      </label>
                        <div class="col-sm-4 text-center">
                        	<select onchange="venue(this.value,'date_<?php echo $i;?>','time_<?php echo $i;?>','<?php echo $subject['subject_code']?>','seat_capacity_<?php echo $i;?>');" name="venue[<?php echo $subject['subject_code']?>]" id="venue_<?php echo $i;?>" required >
                            	<option value="">-Select Venue-</option>
                                <?php 
									if(count($venue_detail) > 0){
										foreach($venue_detail as $row){
								?>
                                <option value="<?php echo $row['venue_code']?>"><?php echo $row['venue_name'];?></option>
                                <?php  } }?>
                            </select>
                        </div>
                        
                        <div class="col-sm-2 text-center">
                        <select name="date[<?php echo $subject['subject_code']?>]" id="date_<?php echo $i;?>" class="form-control date_cls" required  onchange="date(this.value,'venue_<?php echo $i;?>','time_<?php echo $i;?>');">
                        	<option value="">Select</option>
                        </select>
                        </div>
                        
                        <div class="col-sm-2 text-center">
                        <select name="time[<?php echo $subject['subject_code']?>]" id="time_<?php echo $i;?>" class="form-control time_cls" required onchange="time(this.value,'venue_<?php echo $i;?>','date_<?php echo $i;?>','seat_capacity_<?php echo $i;?>');">
                        	<option value="">Select</option>
                        </select>
                        
                        </div>
						
						
						 <div id="seat_capacity_<?php echo $i;?>">-</div>
                    </div>
                     
                    <?php $i++; } }?>           
                <?php
                	if($this->session->userdata('exmcd_elapplyexam') == 1600 || $this->session->userdata('exmcd_elapplyexam') == 16000){
				?>    
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label> 
                        <div class="col-sm-3">
                       
                           <input type="radio" name="elearning_flag" id="elearning_flag_Y" value="Y" >YES
						   <input type="radio" name="elearning_flag" id="elearning_flag_N" value="N" checked="checked">NO
						   
                        </div>
                 </div>
                <div class="form-group" id="showfee" style="display:none">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5 ">
                     <?php echo $fee_detail[0]['elearning_fee_amt'].' + GST AS APPLICABLE';?>
                     <input type="hidden" name="el_fee_amt" id="el_fee_amt" value="" />
                     <input type="hidden" name="tot_el_fee_amt" id="tot_el_fee_amt" value="<?php echo $fee_detail[0]['elearning_igst_amt_total']?>" />
                     <div id="error_dob"></div>
                    </div>
                </div>
                
                 <?php }else{?> 
                  <input type="hidden" name="elearning_flag" id="elearning_flag_N" value="N">
                 <?php }?>
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                	<div class="col-sm-5 ">
                    <?php 
						echo $exam_period1;
             		?>
                 	  <div id="error_dob"></div>
                    </div>
                </div>
			     <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label">Medium *</label>
                	<div class="col-sm-2"><?php echo 'English'; ?></div>
                </div>
   
  				 <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label">Centre Name *</label>
                	<div class="col-sm-2"><?php echo 'Remote Proctored Exam';?></div>
                 </div>
                
                <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label">Centre Code *</label>
                	<div class="col-sm-2">
                    <?php echo  '997';?>
                    </div>
                  </div>
                
                <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label">Exam Mode *</label>
                	<div class="col-sm-2"><?php echo 'Online'; ?></div>
        	     </div>
					
                <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label">Do you intend to use the services of a scribe ?</label>
                    <div class="col-sm-3">
                     <?php 
						 if($admitcard_info[0]['scribe_flag']=='Y'){
							echo 'Yes';
						 }else{
							echo 'No';
						 }
					?>
                    </div>
                </div>  
             	<div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                 	 <input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Submit"> 
                   </div>
               </div>
             </div>
      </div>
  </div>
</div>
</section>
</form>
</div>
<script>
$(document).ready(function(){
	$("#elearning_flag_Y").click(function(){
		$("#showfee").show();
		$("#el_fee_amt").val(<?php echo $fee_detail[0]['elearning_fee_amt'];?>);
	});
	$("#elearning_flag_N").click(function(){
		$("#showfee").hide();
		$("#el_fee_amt").val(0);
	});
});
</script>
<!-- Data Tables -->




 

