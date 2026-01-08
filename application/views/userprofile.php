 <!-- Content Wrapper. Contains page content MEMBER-->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Ordinary Membership Registration Edit Page
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form  class="form-horizontal" name="edit_profile" id="edit_profile"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>home/profile/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>"> 
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
                    <?php echo validation_errors();   ?>
                </div>
              <?php } 
			 ?> 
             
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name <span style="color:#F00">*</span></label>
                <div class="col-sm-2">
                <?php 
				if($user_info[0]['namesub']=='')
				{?>
                	<select name="sel_namesub" id="sel_namesub" class="form-control" required>
                    <option value="" >Select</option>
                    <option value="Mr."  <?php if($user_info[0]['namesub']=='Mr.'){echo  'selected="selected"';}?>>Mr.</option>
                    <option value="Mrs." <?php if($user_info[0]['namesub']=='Mrs.'){echo  'selected="selected"';}?>>Mrs.</option>
                    <option value="Ms." <?php if($user_info[0]['namesub']=='Ms.'){echo  'selected="selected"';}?>>Ms.</option>
                    <option value="Dr."  <?php if($user_info[0]['namesub']=='Dr.'){echo  'selected="selected"';}?>>Dr.</option>
                    <option value="Prof." <?php if($user_info[0]['namesub']=='Prof.'){echo  'selected="selected"';}?>>Prof.</option>
                    </select>
                    <input type="hidden" name="hidd_sel_namesub" id="hidd_sel_namesub" value="<?php echo $user_info[0]['namesub'];?>">	
               <?php 
				}
				else
				{
					echo $user_info[0]['namesub'];?>
					<input type="hidden" name="sel_namesub" id="sel_namesub" value="<?php echo $user_info[0]['namesub'];?>">	
                    <input type="hidden" name="hidd_sel_namesub" id="hidd_sel_namesub" value="<?php echo $user_info[0]['namesub'];?>">	
				<?php 
				}?>
                </div>
                    
                     <div class="col-sm-2">
				    <?php 
					if($user_info[0]['firstname']=='')
					{?>
                    	<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo $user_info[0]['firstname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                        <input type="hidden" name="hidd_firstname" id="hidd_firstname" value="<?php echo $user_info[0]['firstname'];?>">
                     <?php 
					}
					else
					{
							echo $user_info[0]['firstname'];	?>
							<input type="hidden" name="firstname" id="firstname" value="<?php echo $user_info[0]['firstname'];?>">
                        	  <input type="hidden" name="hidd_firstname" id="hidd_firstname" value="<?php echo $user_info[0]['firstname'];?>">
                        
					<?php }?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                   
                     <?php
					 if($user_info[0]['firstname']=='')
					{ 
					
						if($user_info[0]['middlename']=='')
						{ 
							?>
						  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $user_info[0]['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
						  <input type="hidden" name="hidd_middlename" id="hidd_middlename" value="<?php echo $user_info[0]['middlename'];?>">
						 
						 <?php 
						}else
						{
						 echo $user_info[0]['middlename'];?>
						 <input type="hidden" name="middlename" id="middlename" value="<?php echo $user_info[0]['middlename'];?>">
						<input type="hidden" name="hidd_middlename" id="hidd_middlename" value="<?php echo $user_info[0]['middlename'];?>">
						<?php 
						}
					}else
					{
						if($user_info[0]['middlename']=='')
						{  ?>
                      
						  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $user_info[0]['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" readonly="readonly">
						  <input type="hidden" name="hidd_middlename" id="hidd_middlename" value="<?php echo $user_info[0]['middlename'];?>" readonly="readonly">
						 
						 <?php 
						}else
						{
						 echo $user_info[0]['middlename'];?>
						 <input type="hidden" name="middlename" id="middlename" value="<?php echo $user_info[0]['middlename'];?>">
						<input type="hidden" name="hidd_middlename" id="hidd_middlename" value="<?php echo $user_info[0]['middlename'];?>">
						<?php 
						}
					}
					?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                    <?php 
					if($user_info[0]['middlename']=='')
					{?>
                    <span class="characters">(Max 30 Characters)</span>
                    <?php 
					}?>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                    <?php //echo $user_info[0]['lastname'];?>
                     <?php 
					 if($user_info[0]['firstname']=='')
					{ 
					
						if($user_info[0]['lastname']=='')
						{?>
						  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo $user_info[0]['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
						  <input type="hidden" name="hidd_lastname" id="hidd_lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<?php 
						 }else
						{
						 echo $user_info[0]['lastname'];?>
						 <input type="hidden" name="lastname" id="lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<input type="hidden" name="hidd_lastname" id="hidd_lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<?php 
						}
					}else
					{
							if($user_info[0]['lastname']=='')
						{?>
						  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo $user_info[0]['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" readonly="readonly">
						  <input type="hidden" name="hidd_lastname" id="hidd_lastname" value="<?php echo $user_info[0]['lastname'];?>" readonly="readonly">
						<?php 
						 }else
						{
						 echo $user_info[0]['lastname'];?>
						 <input type="hidden" name="lastname" id="lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<input type="hidden" name="hidd_lastname" id="hidd_lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<?php 
						}
					}
					?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div>
                    <?php 
					if($user_info[0]['lastname']=='')
					{?>
                    		<span class="characters">(Max 30 Characters)</span>
                    <?php 
					}?>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name as to appear on Card <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <?php 
                $disp_full_name = '';
                if($user_info[0]['displayname'] != "") 
                { 
                  $disp_full_name = $user_info[0]['displayname']; 
					  }
					  else
					  {
                  $disp_full_name .= $user_info[0]['firstname']; 
                  if($user_info[0]['middlename'] != "") 
                  { 
                    $disp_full_name .= " ".$user_info[0]['middlename']; 
                  } 
                  
                  if($user_info[0]['lastname'] != "") 
                  { 
                    $disp_full_name .= " ".$user_info[0]['lastname']; 
                  } 
                }
                
                if($disp_full_name=='')
                  {?>
                    <input type="text" class="form-control" id="nameoncard" name="nameoncard" placeholder="Name as to appear on Card" required value="<?php echo $disp_full_name;?>"  data-parsley-maxlength="35" >
                    <input type="hidden" name="hidd_nameoncard" id="hidd_nameoncard" value="<?php echo $disp_full_name;?>">
                  <?php }
                  else
                  {
                    echo $disp_full_name;	?> 
                    <input type="hidden" name="nameoncard" id="nameoncard" value="<?php echo $disp_full_name;?>">
                    <input type="hidden" name="hidd_nameoncard" id="hidd_nameoncard" value="<?php echo $disp_full_name;?>">
                  <?php } ?>
                      <span class="error"><?php //echo form_error('nameoncard');?></span>
                    </div><!--(Max 35 Characters) -->
                </div>
                
                </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>
            </div>

          <div class="sub_title">
              <h6 class="box-title">Office/Residential Address for communication (Pl do not repeat the name of the Applicant, Only Address to be typed)</h6>
            </div>
                        
            
            <div class="box-body">
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1 <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $user_info[0]['address1'];?>"  data-parsley-maxlength="30" >
                      <input type="hidden" name="" id="addressline1_hidd" value="<?php echo $user_info[0]['address1'];?>">
                      <span class="error"><?php //echo form_error('addressline1');?></span>
                    </div>
                   <span class="characters">(Max 30 Characters)</span>
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $user_info[0]['address2'];?>"  data-parsley-maxlength="30" >
                      <input type="hidden" name="" id="addressline2_hidd" value="<?php echo $user_info[0]['address2'];?>">
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div>
                   <span class="characters">(Max 30 Characters)</span>
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $user_info[0]['address3'];?>"  data-parsley-maxlength="30" >
                      <input type="hidden" name="" id="addressline3_hidd" value="<?php echo $user_info[0]['address3'];?>">
                      <span class="error"><?php //echo form_error('addressline3');?></span>
                    </div>
                   <span class="characters">(Max 30 Characters)</span>
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $user_info[0]['address4'];?>" data-parsley-maxlength="30" >
                        <input type="hidden" name="" id="addressline4_hidd" value="<?php echo $user_info[0]['address4'];?>">
                      <span class="error"><?php //echo form_error('addressline4');?></span>
                    </div>
                   <span class="characters">(Max 30 Characters)</span>
                    
                    
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo $user_info[0]['district'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                        <input type="hidden" name="" id="district_hidd" value="<?php echo $user_info[0]['district'];?>">
                      <span class="error"><?php //echo form_error('district');?></span>
                    </div>
                   <span class="characters">(Max 30 Characters)</span>
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $user_info[0]['city'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                        <input type="hidden" name="" id="city_hidd" value="<?php echo $user_info[0]['city'];?>">
                      <span class="error"><?php //echo form_error('city');?></span>
                    </div>
                   <span class="characters">(Max 30 Characters)</span>
                </div>
                
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State <span style="color:#F00">*</span></label>
                	<div class="col-md-2 col-sm-5">
                    <select class="form-control" id="state" name="state" required  onchange="javascript:checksate(this.value)">
                        <option value="">Select</option>
                        <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                        <option value="<?php echo $row1['state_code'];?>" 
						<?php if($user_info[0]['state']==$row1['state_code']){echo  'selected="selected"';}?>><?php echo $row1['state_name'];?></option>
                        <?php } } ?>
                      </select>
                     <input type="hidden" name="" id="state_hidd" value="<?php echo $user_info[0]['state'];?>">
                    
      
                    </div>
					 
                     <label for="roleid" class="col-sm-2 control-label pin-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                   
                     <div class="col-md-2 col-sm-5">
                     <input class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required="" value="<?php echo $user_info[0]['pincode'];?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-editcheckpin data-parsley-type="number" autocomplete="off" type="text" data-parsley-trigger-after-failure="focusout">
                     
                        
                         <input type="hidden" name="" id="pincode_hidd" value="<?php echo $user_info[0]['pincode'];?>">
                         <span class="error"><?php //echo form_error('pincode');?></span>
                    </div>
                    <span class="characters-2">(Max 6 digits)</span>
                </div>
              
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <?php if($user_info[0]['dateofbirth']=='' || $user_info[0]['dateofbirth'] == "0000-00-00"){?>
                    	 <input type="hidden" id="dob1" name="dob" required  value="">
                         <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value="" />
                    <?php } else {  
							echo date('d-M-Y',strtotime($user_info[0]['dateofbirth']));?>
                      		<!--<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo $user_info[0]['dateofbirth'];?>">-->
                       <input type="hidden" name="dob_hidd" id="dob_hidd" value="<?php echo $user_info[0]['dateofbirth'];?>">
                       <input type="hidden" name="dob1" id="dob1" value="<?php echo $user_info[0]['dateofbirth'];?>" />
                    <?php } 
                        $min_year = date('Y', strtotime("- 18 year"));
                        $max_year = date('Y', strtotime("- 80 year"));
                    ?>
                    <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                    <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                    <span id="dob_error" class="error"></span>
                    </div>
                </div>
                
                <?php 
				if($user_info[0]['gender']!='')
				{?>
                    <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                     <?php if($user_info[0]['gender']=='female'){echo  'Female';}?>
                     <?php if($user_info[0]['gender']=='male'){echo  'Male';}?>
                    </div>
                </div>
                <input type="hidden" name="gender" id="gender" value="<?php echo $user_info[0]['gender'];?>">
               <?php 
				}
				else
				{?>	
                	 <div class="form-group">
              		 <label for="roleid" class="col-sm-3 control-label">Gender <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                      <input type="radio" class="minimal cls_gender" id="female"   name="gender"  required value="female" <?php 
					  if($user_info[0]['gender']=='female'){echo  'checked="checked"';}?>>
                     Female
                   <input type="radio" class="minimal cls_gender" id="male"  name="gender"  required value="male" <?php 
					  if($user_info[0]['gender']=='male'){echo  'checked="checked"';}?>>
                     Male
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
                </div>
                
                <input type="hidden" name="" id="gender" value="<?php echo $user_info[0]['gender'];?>">
                <?php 
				}?>
                 <input type="hidden" name="" id="hidd_gender" value="<?php echo $user_info[0]['gender'];?>">
                
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification <span style="color:#F00">*</span></label>
                	<div class="col-sm-4">
                      <input type="radio" class="minimal" id="U"   name="optedu"  value="U" <?php if($user_info[0]['qualification']=='U'){echo  'checked="checked"';}?>                      onclick="changedu(this.value)" >
                        Under Graduate
                        <input type="radio" class="minimal" id="G"   name="optedu"  value="G" <?php if($user_info[0]['qualification']=='G'){echo  'checked="checked"';}?>
                         onclick="changedu(this.value)" >
                        Graduate
                        <input type="radio" class="minimal" id="P"   name="optedu"  value="P"  <?php if($user_info[0]['qualification']=='P'){echo  'checked="checked"';}?>
                        onclick="changedu(this.value)" >
                        Post Graduate
                      <span class="error"><?php //echo form_error('optedu');?></span>
                    </div>
                </div>
                  <input type="hidden" name="" id="optedu_hidd" value="<?php echo $user_info[0]['qualification'];?>">
                 
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify <span style="color:#F00">*</span></label>
                
              <div class="col-sm-5" <?php if($user_info[0]['specify_qualification']!='' || $user_info[0]['qualification']!=''){echo 'style="display:none"';}else
			  {echo 'style="display:block"';}?>  id="edu">
           	  <select id="eduqual" name="eduqual" class="form-control" <?php if($user_info[0]['specify_qualification']=='' && $user_info[0]['qualification']==''){echo 'required';}?>>
				<option value="" selected="selected">--Select--</option>
				</select>
            </div>
                    
                    
                	<div class="col-sm-5"  <?php if($user_info[0]['qualification']=='U'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> id="UG">
                      <select class="form-control" id="eduqual1" name="eduqual1" <?php if($user_info[0]['qualification']=='U'){echo 'required';}?> >
                        <option value="">--Select--</option>
                        <?php if(count($undergraduate)){
                                foreach($undergraduate as $row1){ 	?>
                        <option value="<?php echo $row1['qid'];?>" <?php if($user_info[0]['specify_qualification']==$row1['qid']){echo  'selected="selected"';}?>><?php echo $row1['name'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php //echo form_error('eduqual1');?></span>
                       <input type="hidden" name="hdd_eduqual1" id="hdd_eduqual1" value="<?php echo $user_info[0]['specify_qualification'];?>">
                    </div>
                    
                    <div class="col-sm-5"  <?php if($user_info[0]['qualification']=='G'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> id="GR">
                      <select class="form-control" id="eduqual2" name="eduqual2" <?php if($user_info[0]['qualification']=='G'){echo 'required';}?> >
                        <option value="">--Select--</option>
                        <?php if(count($graduate)){
                                foreach($graduate as $row2){ 	?>
                        <option value="<?php echo $row2['qid'];?>" <?php if($user_info[0]['specify_qualification']==$row2['qid']){echo  'selected="selected"';}?>><?php echo $row2['name'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php //echo form_error('eduqual2');?></span>
                       <input type="hidden" name="hdd_eduqual2" id="hdd_eduqual2" value="<?php echo $user_info[0]['specify_qualification'];?>">
                    </div>
                    
                   
                    
                    <div class="col-sm-5"  <?php if($user_info[0]['qualification']=='P'){echo 'style="display:block"';}else{echo 'style="display:none"';}?>id="PG">
                      <select class="form-control" id="eduqual3" name="eduqual3" <?php if($user_info[0]['qualification']=='P'){echo 'required';}?>>
                        <option value="">--Select--</option>
                        <?php if(count($postgraduate)){
                                foreach($postgraduate as $row3){ 	?>
                        <option value="<?php echo $row3['qid'];?>" <?php if($user_info[0]['specify_qualification']==$row3['qid']){echo  'selected="selected"';}?>><?php echo $row3['name'];?></option>
                        <?php } } ?>
                      </select>
                      
                      <span class="error"><?php //echo form_error('eduqual3');?></span>
                    </div>
                      <input type="hidden" name="hdd_eduqual3" id="hdd_eduqual3" value="<?php echo $user_info[0]['specify_qualification'];?>">
                </div>
                  <input type="hidden" name="specify_q" id="specify_q" value="<?php echo $user_info[0]['specify_qualification'];?>">
                
                <div class="form-group">
                                            <label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" id="bank_emp_id" name="bank_emp_id" placeholder="Employee Id"  value="<?php echo $user_info[0]['bank_emp_id'];?>"  data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required >
                                                <span class="error">
											       <?php //echo form_error('city');?>
											    </span> 
											</div>
											<input type="hidden" name="" id="bank_emp_id_hidd" value="<?php echo $user_info[0]['bank_emp_id'];?>">
										</div>
                
              
              <div class="form-group">
                  <?php if($user_info[0]['associatedinstitute'] != "") { ?>
             <label for="roleid" class="col-sm-3 control-label">Bank/Institution working <span style="color:#F00">*</span></label>
                  <?php } ?>
              <div class="col-sm-5"  style="display:block" id="edu">
           	  <?php 
			  /*?><select id="institutionworking" name="institutionworking" class="form-control" required>
				 <option value="">--Select--</option>
                 <?php if(count($institution_master)){
                                foreach($institution_master as $institution_row){ 	?>
                        <option value="<?php echo $institution_row['institude_id'];?>" 
						<?php if($user_info[0]['associatedinstitute']==$institution_row['institude_id']){echo  'selected="selected"';}?>><?php echo $institution_row['name'];?></option>
                        <?php } } ?>
				</select><?php */?>
        <!--Changes by:- pooja  9-8-2017-->
                <?php 
				$associatedinstitute='';
				if($user_info[0]['associatedinstitute']!='')
				{
					$this->db->where('institution_master.institution_delete', 0);
					$associatedinstitute_arr=$this->master_model->getRecords('institution_master',array('institude_id'=>$user_info[0]['associatedinstitute'])); 
					if(count($associatedinstitute_arr) >0)
					  {
						  echo $associatedinstitute_arr[0]['name'];
					   }
				}
				?>
                 <input type="hidden" name="institutionworking" id="institutionworking" value="<?php echo $user_info[0]['associatedinstitute'];?>" >
                 <input type="hidden" name="institutionworking_hidd" id="institutionworking_hidd" value="<?php echo $user_info[0]['associatedinstitute'];?>">
                <span class="error"><?php //echo form_error('institutionworking');?></span>
            </div>
         </div>
         
         <?php 
         	$branch = '';
			$branch_name = '';
			if($user_info[0]['editedon']=='0000-00-00 00:00:00')
			{
				if($user_info[0]['branch']!='')
						$branch = $user_info[0]['branch'];
					else
						$branch = $user_info[0]['office'];
			}
			else if($user_info[0]['editedon'] < "2016-12-29 00:00:00")
			{
				$branch = $user_info[0]['branch'];
			}
			else if($user_info[0]['editedon'] >= "2016-12-29")
			{
				
				if(is_numeric($user_info[0]['office']))
				{
					if($user_info[0]['branch']!='')
						$branch = $user_info[0]['branch'];
					else
						$branch = $user_info[0]['office'];
				}
				else
				{
					if($user_info[0]['office']!='')
						$branch = $user_info[0]['office'];
					else
						$branch = $user_info[0]['branch'];
				}
			
			}
			
			
         ?>
                
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Branch/Office <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="office" name="office" placeholder="Branch/Office" required value="<?php echo $branch;?>"  data-parsley-maxlength="20" >
                        <input type="hidden" name="" id="office_hidd" value="<?php echo $branch;?>">
                      <span class="error"><?php //echo form_error('office');?></span>
                    </div>
                    <span class="characters">(Max 20 Characters)</span>
                </div>
                
                <div class="form-group">
             <label for="roleid" class="col-sm-3 control-label">Designation <span style="color:#F00">*</span></label>
              <div class="col-sm-5"  style="display:block" id="edu">
           	  <select id="designation" name="designation" class="form-control" required>
				 <option value="">--Select--</option>
                 <?php if(count($designation)){
                                foreach($designation as $designation_row){ 	?>
                        <option value="<?php echo $designation_row['dcode'];?>" 
						<?php if(trim($user_info[0]['designation'])==$designation_row['dcode']){echo  'selected="selected"';}?>><?php echo $designation_row['dname'];?></option>
                        <?php } } ?>
				</select>
                  <input type="hidden" name="" id="designation_hidd" value="<?php echo $user_info[0]['designation'];?>">
                <span class="error"><?php //echo form_error('designation');?></span>
            </div>
         </div>
                
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of joining Bank/Institution  <span style="color:#F00">*</span></label>
                	<div class="col-sm-3">
                      <input type="text" class="form-control pull-right" id="doj"  name="doj" placeholder="Date of joining Bank/Institution" required value="<?php echo date('m/d/Y',strtotime($user_info[0]['dateofjoin']));?>">
                       <input type="hidden" name="" id="doj_hidd" value="<?php echo date('m/d/Y',strtotime($user_info[0]['dateofjoin']));?>">
                      <span class="error"><?php //echo form_error('doj');?></span>
                    </div>
                </div>-->
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of joining Bank/Institution  <span style="color:#F00"><span style="color:#F00">*</span></span></label>
                	<div class="col-md-4 col-sm-9">
					
                      <!--<input type="text" class="form-control pull-right" id="doj"  name="doj" placeholder="Date of joining Bank/Institution" required value="<?php echo set_value('doj');?>" >-->
                      
                    <div class="example">
                        <input type="hidden" id="doj1" name="doj" value="<?php if($user_info[0]['dateofjoin']!=''){echo date('Y-m-d',strtotime($user_info[0]['dateofjoin']));}?>">
                        <input type="hidden" name="" id="doj_hidd" value="<?php if($user_info[0]['dateofjoin']!=''){echo date('m/d/Y',strtotime($user_info[0]['dateofjoin']));}?>"> 					<!--Below field for validation purpose-->
                         <input type="hidden" name="" id="doj_hidd_validate" value="<?php if($user_info[0]['dateofjoin']!=''){echo date('Y-m-d',strtotime($user_info[0]['dateofjoin']));}?>">
                    </div>
                       <span id="doj_error" class="error"></span>
                    </div>
                </div>

                <?php 
                /* --------------------- EMAIL VERIFCATION CODE BY POOJA MANE ON : 2024-11-14---------------------*/
                  $mobile_verify_status = 'yes';
                  $email_verify_status = 'yes';
                  if ($email_verify_status == 'yes') {
                    $emailStatus = true;
                  }

                  if ($mobile_verify_status == 'yes') {
                    $mobileStatus = true;
                  }

                  ?>

                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                    <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-editemailcheck  autocomplete="off" type="text" data-parsley-trigger-after-failure="focusout" readonly>
                       <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-info send-otp" id="send_otp_btn" data-type='send_otp' <?php if($emailStatus == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button>
                        <a class="btn btn-info" id="reset_btn" href="javascript:void(0)" <?php if($emailStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Email</a>
                     </div>
                </div>

                <div class="form-group verify-otp-section" style="display:none;">
                <label for="roleid" class="col-sm-3 control-label">OTP <span style="color:#F00">*</span></label>
                <div class="row">
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="otp" name="otp" placeholder="OTP" onKeyPress="if(this.value.length==6) return false;" value="<?php echo set_value('otp'); ?>">
                  </div>
                  <div class="col-sm-4">
                    <button type="button" class="btn btn-info verify-otp" data-verify-type='email'>Verify OTP </button>
                    <button type="button" class="btn btn-info send-otp" data-type='resend_otp'>Resend OTP</button>
                  </div>  
                </div>  
              </div>

                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"></label>
                  <div class="col-sm-5">
                    <span style="color:#F00;font-size:small;">(Please check correctness of your Email id and Mobile number. Please change the same here if required. Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail.)</span>
                    </div>
                </div>
                <?php

                /* --------------------- EMAIL VERIFCATION CODE BY POOJA MANE ON : 2024-11-14---------------------*/


                /*<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-editemailcheck  autocomplete="off" type="text" data-parsley-trigger-after-failure="focusout">
                    
                      
                       <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                           <span style="color:#F00;font-size:small;">(Please check correctness of your Email id and Mobile number. Please change the same here if required. Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail.)</span>
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>*/
              ?>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone </label>
                	<div class="col-md-2 col-sm-3">
                      STD Code
                      <input type="text" class="form-control " id="stdcode"  name="stdcode" placeholder="STD Code"  value="<?php echo $user_info[0]['stdcode'];?>" data-parsley-type="number" data-parsley-maxlength="4" data-parsley-trigger-after-failure="focusout">
                      <input type="hidden" name="" id="stdcode_hidd" value="<?php echo $user_info[0]['stdcode'];?>" >
                      <span class="error"><?php //echo form_error('stdcode');?></span>
                    </div>
                    <div class="col-md-2 col-sm-3">
                    Phone No
                      <input type="text" class="form-control pull-right" id="phone"  name="phone" placeholder="Phone No"  data-parsley-minlength="7"
                      data-parsley-type="number" data-parsley-maxlength="12"    value="<?php echo $user_info[0]['office_phone'];?>" data-parsley-trigger-after-failure="focusout">
                        <input type="hidden" name="" id="phone_hidd" value="<?php echo $user_info[0]['office_phone'];?>">
                      <span class="error"><?php //echo form_error('phone');?></span>
                    </div>
                </div>
                 
                 
                 <?php /* --------------------- Mobile VERIFCATION CODE BY POOJA MANE ON : 2024-11-14---------------------*/ ?>

                  <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-editmobilecheck data-parsley-trigger-after-failure="focusout" required readonly>
                      <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-info send-otp-mobile" id="send_otp_btn_mobile" data-type='send_otp' <?php if($mobileStatus == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button>
                      <a class="btn btn-info" id="reset_btn_mobile" href="javascript:void(0)" <?php if($mobileStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Mobile No.</a>
                    </div>
                </div>
                <div class="form-group verify-otp-section-mobile" style="display:none;">
                <label for="roleid" class="col-sm-3 control-label">OTP <span style="color:#F00">*</span></label>
                <div class="row">
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="otp_mobile" name="otp_mobile" placeholder="OTP" onKeyPress="if(this.value.length==6) return false;" value="<?php echo set_value('otp'); ?>">
                  </div>
                  <div class="col-sm-4">
                    <button type="button" class="btn btn-info verify-otp-mobile" data-verify-type='mobile'>Verify OTP </button>
                    <button type="button" class="btn btn-info send-otp-mobile" data-type='resend_otp'>Resend OTP</button>
                  </div>  
                </div>  
              </div>

              <?php /* --------------------- EMAIL VERIFCATION CODE BY POOJA MANE ON : 2024-11-14---------------------*/ ?>
                
                
                   <?php 
				$flag=1;
				$mendatory_display='display:block';
				if($user_info[0]['state']=='ASS' || $user_info[0]['state']=='JAM' || $user_info[0]['state']=='MEG')
				{
					$mendatory_display='display:none';
					$flag=0;
				}
				else
				{
					$mendatory_display='display:block';
					$flag=1;
				}
				
				?>
                
            	  <div class="form-group">
              	  <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number
                 <?php /*?> <div style="color:#F00;<?php echo $mendatory_display;?>"id="mendatory_state">*</div><?php */?>
                  </label>
                	<div class="col-sm-5">
						<?php
                        if($user_info[0]['aadhar_card']=='')
                        {?>
                            <?php /*?><input type="text" class="form-control " id="aadhar_card"  name="aadhar_card" placeholder="Aadhar Card Number" 
                            data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12"
                             <?php if($flag==1){echo 'required';}?> value="<?php echo $user_info[0]['aadhar_card'];?>" data-parsley-trigger-after-failure="focusout" >
                            <input type="hidden" id="aadhar_card_hidd"  name="aadhar_card_hidd" value="<?php  echo $user_info[0]['aadhar_card']?>"><?php */?>
                            
                            <input type="text" class="form-control " id="aadhar_card"  name="aadhar_card" placeholder="Aadhar Card Number" 
                            data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12"
                            value="<?php echo $user_info[0]['aadhar_card'];?>" data-parsley-trigger-after-failure="focusout" >
                            <input type="hidden" id="aadhar_card_hidd"  name="aadhar_card_hidd" value="<?php  echo $user_info[0]['aadhar_card']?>">
                        <?php 
                        }
						else
						{
							 echo $user_info[0]['aadhar_card'];?>
                             <input type="hidden" id="aadhar_card"  name="aadhar_card" value="<?php  echo $user_info[0]['aadhar_card']?>">
                             <input type="hidden" id="aadhar_card_hidd"  name="aadhar_card_hidd" value="<?php  echo $user_info[0]['aadhar_card']?>">
						<?php 
						}?>
                      <span class="error"><?php //echo form_error('idNo');?></span>
                    </div>
                </div>
                
                <!-- Disability Code Start -->
                <div class="form-group">
			  <label for="roleid" class="col-sm-3 control-label">Person with Benchmark Disability</label>
			  <div class="col-sm-5">
				<input value="Y" name="benchmark_disability" id="benchmark_disability" type="radio" <?php if($user_info[0]['benchmark_disability']=='Y'){echo  'checked="checked"';} ?> class="benchmark_disability_y">
				Yes
				<input value="N" name="benchmark_disability" id="benchmark_disability" type="radio" <?php if($user_info[0]['benchmark_disability']=='N'){echo  'checked="checked"';} /*elseif($user_info[0]['benchmark_disability']!='Y'){ echo  'checked=""'; }*/?> class="benchmark_disability_n">
				No <span class="error"></span> </div>
			</div>
                <div id="benchmark_disability_div" style="display:none;">
			
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Visually impaired</label>
				  <div class="col-sm-5">
					<input value="Y" name="visually_impaired" id="visually_impaired" type="radio" class="visually_impaired_y" <?php if($user_info[0]['visually_impaired']=='Y'){echo  'checked="checked"';} ?> >
					Yes
					<input value="N" name="visually_impaired" id="visually_impaired" type="radio" class="visually_impaired_n" <?php if($user_info[0]['visually_impaired']=='N'){echo  'checked="checked"';} elseif($user_info[0]['visually_impaired']!='Y'){ echo  'checked=""'; }?> >
					No <span class="error"></span> </div>
				</div>
				<div class="form-group"  id="vis_imp_cert_div" style="display:none;">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
				  <div class="col-sm-5">
					
					<?php 
				  if($user_info[0]['visually_impaired']=='Y')
				  {
				  ?>
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/v_'.$this->session->userdata('regnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  <?php
				  }
				  else
				  {
				  ?>
					<input  type="file" name="scanned_vis_imp_cert" id="scanned_vis_imp_cert" required  style="word-wrap: break-word;width: 100%;">
					
					
					<?php
				  }
				  ?>
				  <input type="hidden" id="hidden_vis_imp_cert" name="hidden_vis_imp_cert">
          <note class="form_note">Note: Please upload only .jpg, .jpeg file upto 100KB.</note>
					<div id="error_vis_imp_cert"></div>
					<br>
					<div id="error_vis_imp_cert_size"></div>
					<span class="vis_imp_cert_text" style="display:none;"></span> <span class="error"> </span> </div>
				</div>
				
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Orthopedically handicapped</label>
				  <div class="col-sm-5">
					<input value="Y" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio"  <?php if($user_info[0]['orthopedically_handicapped']=='Y'){echo  'checked="checked"';} ?> class="orthopedically_handicapped_y">
					Yes
					<input value="N" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio"  <?php if($user_info[0]['orthopedically_handicapped']=='N'){echo  'checked="checked"'; } elseif($user_info[0]['orthopedically_handicapped']!='Y'){ echo  'checked=""'; }?> class="orthopedically_handicapped_n">
					No <span class="error"></span> </div>
				</div>
				<div class="form-group" id="orth_han_cert_div" style="display:none;">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
				  <div class="col-sm-5">
				  <?php 
				  if($user_info[0]['orthopedically_handicapped']=='Y')
				  {
				  ?>
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/o_'.$this->session->userdata('regnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  <?php
				  }
				  else
				  {
				  ?>
				   	<input  type="file" name="scanned_orth_han_cert" id="scanned_orth_han_cert" required  style="word-wrap: break-word;width: 100%;">
					
				  <?php
				  }
				  ?>
				  <input type="hidden" id="hidden_orth_han_cert" name="hidden_orth_han_cert">
          <note class="form_note">Note: Please upload only .jpg, .jpeg file upto 100KB.</note>
				  <div id="error_orth_han_cert"></div>
					<br>
					<div id="error_orth_han_cert_size"></div>
					<span class="orth_han_cert_text" style="display:none;"></span> <span class="error"> </span> </div>
				</div>
				
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Cerebral palsy</label>
				  <div class="col-sm-5">
					<input value="Y" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php if($user_info[0]['cerebral_palsy']=='Y'){echo  'checked="checked"';} ?>  class="cerebral_palsy_y">
					Yes
					<input value="N" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php if($user_info[0]['cerebral_palsy']=='N'){echo  'checked="checked"';} elseif($user_info[0]['cerebral_palsy']!='Y'){ echo  'checked=""'; } ?>  class="cerebral_palsy_n">
					No <span class="error"></span> </div>
				</div>
				<div class="form-group" id="cer_palsy_cert_div" style="display:none;">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
				  <div class="col-sm-5">
				  <?php 
				  if($user_info[0]['cerebral_palsy']=='Y')
				  {
				  ?>
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/c_'.$this->session->userdata('regnumber').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  <?php
				  }
				  else
				  {
				  ?>
				  
					<input  type="file" name="scanned_cer_palsy_cert" id="scanned_cer_palsy_cert" required  style="word-wrap: break-word;width: 100%;">
					
					
					<?php
				  }
				  ?>
				  <input type="hidden" id="hidden_cer_palsy_cert" name="hidden_cer_palsy_cert">
          <note class="form_note">Note: Please upload only .jpg, .jpeg file upto 100KB.</note>
					<div id="error_cer_palsy_cert"></div>
					<br>
					<div id="error_cer_palsy_cert_size"></div>
					<span class="cer_palsy_cert_text" style="display:none;"></span> <span class="error"> </span> </div>
				</div>
			
			</div>
				<script>
				
				$(document).ready(function() {

				$(document).on("click", ".benchmark_disability_y", function() {
					alert('If Yes, then select following disability criteria and Attach scan copy of PWD certificate.');
				});
			 });	
				</script>
				<script src="<?php echo base_url();?>js/disability.js?<?php echo time(); ?>"></script> 
				<!-- Disability Code End -->
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 text-center">
                <div style="min-height: 100px;">
              	<?php 
				
				
				$this->db->select('mem_photo,mem_sign,mem_proof');
				$kyc_info = $this->master_model->getRecords('member_kyc', array('regnumber' => $this->session->userdata('regnumber')));
				
			    if(is_file(get_img_name($this->session->userdata('regnumber'),'p')))
				{?>
              			  <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('regnumber'),'p');?><?php echo '?'.time(); ?>"  style="max-width:100%; max-height:100px;">
                <?php 
				}else if(count($kyc_info) > 0)
				{
					if($kyc_info[0]['mem_photo']==1)
					{?>
						<img src="<?php echo base_url();?>assets/images/kyc-pending.png<?php echo '?'.time(); ?>" height="100" width="100" >					
					<?php }
					else
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
					<?php }
				}
				else
				{?>
             	   <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
			<?php 	}?>
                </div>
			 <label for="roleid" class="col-sm-12 text-center" style="line-height: 18px;">Uploaded Photo</label>
                </label>
               <label for="roleid" class="col-sm-3 text-center">
               <div style="min-height: 100px;">
               <?php 
			   	if(is_file(get_img_name($this->session->userdata('regnumber'),'s')))
				{?>
             	  <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('regnumber'),'s');?><?php echo '?'.time(); ?>" style="max-width:100%; max-height:100px;">
                <?php 
				}else if(count($kyc_info) > 0)
				{
					if($kyc_info[0]['mem_sign']==1)
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >					
					<?php 
					}
					else
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
					<?php 
					}
				}
				else
				{?>
                	 <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                <?php 
				}?>
               </div>
               <label for="roleid" class="col-sm-12 text-center" style="line-height: 18px;">Uploaded Signature</label>
      </label>
      <label for="roleid" class="col-sm-3 text-center">
      <div style="min-height: 100px;">
                <?php
			 if(is_file(get_img_name($this->session->userdata('regnumber'),'pr')))
				{?>
            <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('regnumber'),'pr');?><?php echo '?'.time(); ?>" style="max-width:100%; max-height:100px;">
                 <?php 
				}else if(count($kyc_info) > 0)
				{
					if($kyc_info[0]['mem_proof']==1)
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >					
					<?php 
					}
					else
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
					<?php
					 }
				}
				else
				{?>
               		  <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                  <?php 
				}?>
      </div>
				 <label for="roleid" class="col-sm-12 text-center" style="line-height: 18px;">Uploaded ID Proof</label>
        </label>

        <label for="roleid" class="col-sm-3 text-center">
        <div style="min-height: 100px;">
            <?php
            if(is_file(get_img_name($this->session->userdata('regnumber'),'declaration')))
            {?>
              <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('regnumber'),'declaration');?><?php echo '?'.time(); ?>" style="max-width:100%; max-height:100px;">
            <?php 
            }else if(count($kyc_info) > 0)
            {
              if($kyc_info[0]['mem_declaration']==1)
              {?>
                <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >         
              <?php 
              }
              else
              {?>
                <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
              <?php
              }
            }
            else
            {?>
              <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
            <?php 
            }?>
        </div>
           <label for="roleid" class="col-sm-12 text-center" style="line-height: 18px;">Uploaded Declaration </label>
        </label>
      </div>
                
            <div class="form-group">
           
              <?php /*if(!file_exists('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_info[0]['idproofphoto']) ||$user_info[0]['scannedphoto']=='' || $user_info[0]['scannedsignaturephoto']=='' ||  $user_info[0]['idproofphoto']=='')
			{*/
			if(!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || !is_file(get_img_name($this->session->userdata('regnumber'),'declaration')))
			{?>
            	<label for="roleid" class="col-sm-3 text-center"><a href="<?php echo base_url();?>home/editimages/" class="btn btn-warning">Edit Images</a></label>
        	<?php 
			}?>
            </div>
            
            <input type="hidden" name="scannedphoto1_hidd" id="scannedphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('regnumber'),'p');?>">
            <input type="hidden" name="scannedsignaturephoto1_hidd" id="scannedsignaturephoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('regnumber'),'s');?>">
            <input type="hidden" name="idproofphoto1_hidd" id="idproofphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('regnumber'),'pr');?>">
            <input type="hidden" name="declarationphoto_hidd" id="declarationphoto_hidd" value="<?php echo get_actual_img_name($this->session->userdata('regnumber'),'declaration');?>">
            
            
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    	<img src="<?php echo base_url();?>uploads/photograph/<?php echo $user_info[0]['scannedphoto'];?>">
                        <input  type="file" class="form-control" name="scannedphoto" id="scannedphoto" required>
                          <div id="error_photo"></div>
                     <br>
                     <div id="error_photo_size"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                    </div>
                </div>-->
                
                
                 <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                  		<img src="<?php echo base_url();?>uploads/scansignature/<?php echo $user_info[0]['scannedsignaturephoto'];?>">
                        <input  type="file" class="form-control" name="scannedsignaturephoto" id="scannedsignaturephoto" required>
                    <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                </div>-->
                
                <!-- <?php if($user_info[0]['idproof']!='')
        				{?>
                         	<div class="form-group">
                              <label for="roleid" class="col-sm-3 control-label">Select Id Proof <span style="color:#F00">*</span></label>
                            <div class="col-sm-5">
                             <?php if(count($idtype_master) > 0)
                    					{
                    						foreach($idtype_master as $idrow)
                    						{?>
                    		                   	    <?php if($user_info[0]['idproof']==$idrow['id']){echo  $idrow['name'];}?>
                                     	  <?php 
                    				 	  }
                    				   }?>
                              <input type="hidden" name="idproof" id="idproof" value="<?php echo $user_info[0]['idproof'];?>">	
                              <input type="hidden" name="idproof_hidd" id="idproof_hidd" value="<?php echo $user_info[0]['idproof'];?>">	
                              <span class="error"><?php //echo form_error('idproof');?></span>
                            </div>
                          </div>
                
                        <?php 
        				}
        				else
        				{?>
                         	 <div class="form-group">
                            <label for="roleid" class="col-sm-3 control-label">Select Id Proof <span style="color:#F00">*</span></label>
                                <div class="col-sm-5">
                                <?php if(count($idtype_master) > 0)
                                {
                                    $i=1;
                                    foreach($idtype_master as $idrow)
                                    {?>
                                       <input name="idproof" value="<?php echo $idrow['id'];?>" type="radio" class="minimal" 
                                       <?php if(set_value('idproof')){echo set_radio('idproof', $idrow['id'], TRUE);}?> 
                                       <?php if($i==count($idtype_master)){echo 'required';}?>>
                                       <?php echo $idrow['name'];?><br>
                               <?php 
                               $i++;}
                               }?>
                                  <span class="error"><?php //echo form_error('idproof');?></span>
                            </div>
                        </div>
                        
                        <input type="hidden" name="idproof_hidd" id="idproof_hidd" value="<?php echo $user_info[0]['idproof'];?>">	
                        <?php 
        				}?> -->
                <!--  idproof_hidd set static 4(type idproof), confirmed by prafull-->
                <input type="hidden" name="idproof" id="idproof" value="4">	
                 

                
                   <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your id proof <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <img src="<?php echo base_url();?>uploads/idproof/<?php echo $user_info[0]['idproofphoto'];?>" height="100" width="100">
                        <input  type="file" class="form-control" name="idproofphoto" id="idproofphoto" required>
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>-->
                
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-10 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
            <div class="col-sm-2 pad_top_4">
               <input value="Y" name="optnletter" id="optnletter" checked="" type="radio" <?php if($user_info[0]['optnletter']=='Y'){echo  'checked="checked"';}?>  >Yes
                <input value="N" name="optnletter" id="optnletter" type="radio"  <?php if($user_info[0]['optnletter']=='N'){echo  'checked="checked"';}?>>No
           	   <input type="hidden" name="" id="optnletter_hidd" value="<?php echo $user_info[0]['optnletter'];?>">
              <span class="error"><?php //echo form_error('optnletter');?></span>
    		        </div>
		   </div>-->
           
             <?php if($user_info[0]['optnletter']=='Y')
			   {?>
                     <div class="form-group">
                <label for="roleid" class="col-sm-10 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
            <div class="col-sm-2">
             Yes
               <input type="hidden" name="optnletter" id="optnletter" value="<?php echo $user_info[0]['optnletter'];?>">
               
              <span class="error"><?php echo form_error('optnletter');?></span>
    		        </div>
		   </div>
              <?php }
			else
			{?>
				<div class="form-group">
                <label for="roleid" class="col-sm-8 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
            <div class="col-sm-2">
              	 <input value="Y" name="optnletter" class="arflag" id="optnletter1" checked="" type="radio" <?php if($user_info[0]['optnletter']=='Y'){echo  'checked="checked"';}?>  >Yes
                <input value="N" name="optnletter" class="arflag" id="optnletter2" type="radio"  <?php if($user_info[0]['optnletter']=='N'){echo  'checked="checked"';}?>>No
           	  
               <input type="hidden" name="" id="optnletter" value="<?php echo $user_info[0]['optnletter'];?>">
               <input type="hidden" name="" id="optnletter_hidd" value="<?php echo $user_info[0]['optnletter'];?>">
              <span class="error"><?php echo form_error('optnletter');?></span>
    		        </div>
		   </div>
			<?php }
		   ?>
           <input type="hidden" name="" id="optnletter_hidd" value="<?php echo $user_info[0]['optnletter'];?>">
	</div>
             
           
              <div class="box-footer">
                  <div class="col-sm-2 col-sm-offset-5">
                   <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save" onclick="javascript:return checkEdit();">
	              </div>
           </div>
           <?php /* --------------------- EMAIL VERIFCATION CODE BY POOJA MANE ON : 2024-11-14---------------------*/ ?>
           <input type="hidden" id="email_verify_status" name="email_verify_status" value="<?php echo $email_verify_status; ?>">
          <input type="hidden" id="mobile_verify_status" name="mobile_verify_status" value="<?php echo $mobile_verify_status; ?>">
          <?php /* --------------------- EMAIL VERIFCATION CODE BY POOJA MANE ON : 2024-11-14---------------------*/ ?>
    	</div>
     </div>
  </div>
     
      
      
    </section>
    </form>
  </div>
  
<script src="<?php echo base_url();?>js/validation.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>

<script>
	$(document).ready(function() 
	{
	//	var dtable = $('.dataTables-example').DataTable();
	   
	   //$(".DTTT_button_print")).hide();
		//<span style="color:#F00">*</span>$('#datepicker').datepicker({
		 //  autoclose: true
		// });<span style="color:#F00">*</span>	
		
		<?php if($user_info[0]['dateofbirth']=='' || $user_info[0]['dateofbirth'] == "0000-00-00"){?>
		$(function() {
			$("#dob1").dateDropdowns({
				submitFieldName: 'dob1',
				minAge: 0,
				maxAge:79
			});
			// Set all hidden fields to type text for the demo
			//$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
		});
	<?php } ?>	
		
		$(function() {
			$("#doj1").dateDropdowns({
				submitFieldName: 'doj1',
				minAge: 0,
				maxAge:59
			});
		});	  
		
		
		
		 
	});
	
	
	
	$(document).ready(function() {
 
    $('input[type=radio][name=optnletter]').change(function() {
       $('#optnletter').val(this.value);
	});
	
	   /*$('input[type=radio][name=idproof]').change(function() {
       $('#idproof').val(this.value);
	});*/
	
	 $('input[type=radio][name=gender]').change(function() {
       $('#gender').val(this.value);
	});
	
	
	
	
	
	
	
});
	
	function editUser(id,roleid,Name,Username,Email){
		$('#id').val(id);
		$('#roleid').val(roleid);
		$('#name').val(Name);
		$('#username').val(Username);
		$('#emailid').val(Email);
		$('#btnSubmit').val('Update');
		$('#roleid').focus();
		$('#password').removeAttr('required');
		$('#confirmPassword').removeAttr('required');
		
	}
	
	function changedu(dval)
	{
	var UGid = document.getElementById('UG');
	var GRid = document.getElementById('GR');
	var PGid = document.getElementById('PG');
	var EDUid = document.getElementById('edu');

	if(dval == 'U')
	{
		$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
		
		if(UGid != null) {
		//	alert('UG');
			document.getElementById('UG').style.display = "block";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else if(dval == 'G')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "block";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	
	}
	else if(dval == 'P')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "block";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
}
	
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
	
//set datepicker on id's
//$("#datepicker,#doj").keypress(function(event) {event.preventDefault();});
});
</script>
  <style>
    .form_note {
	color: #026775;
	font-weight: 500;
	font-size: 12px;
	line-height: 15px !important;
	display: block;
	margin: 4px 0 0 0;
}
  .pad_top_7_w {
	  width:3%
	 }
	 .sub_title .box-title {
		 font-size:16px;
		 padding-left:15px;
		 color:#078ed1;
		}
  </style>
  <?php if(!is_file('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !is_file('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) || !is_file('./uploads/idproof/'.$user_info[0]['idproofphoto']) || !is_file('./uploads/declaration/'.$user_info[0]['declaration']) || $user_info[0]['mobile']=='' || $user_info[0]['email']=='')
			{
				?>
            <script>
		$(document).ready(function(){
				$('#update_profile').modal('show');
				});
			</script>
<?php }?>
<!--Disable Mouse Right Click-->
<script type="text/javascript">
$(document).ready(function () {
    //Disable full page
    $("body").on("contextmenu",function(e){
        return false;
    });
    
    //Disable part of page
    $("#id").on("contextmenu",function(e){
        return false;
    });

  ///////////////////// Declaration form validation done by Pratibha Borse on 28 March 22 //////////////////////

    $( "#declaration" ).change(function() {
    //var filesize1=this.files[0].size/1024<8;
    var filesize2=this.files[0].size/1024>300;
    var flag = 1;
    //$("#p_dob_proof").hide();
    
    var declartion_proof_image=document.getElementById('declaration');
    var declaration_proof_im=declartion_proof_image.value;
    var ext3=declaration_proof_im.substring(declaration_proof_im.lastIndexOf('.')+1);
    
    if(declartion_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG' && ext3!='jpeg' && ext3!='JPEG')
    {
      $('#error_declaration').show();
      $('#error_declaration').fadeIn(300);  
      document.getElementById('error_declaration').innerHTML="Upload JPG or jpg file only.";
      setTimeout(function(){
      $('#error_declaration').css('color','#B94A48');
       document.getElementById("declaration").value = "";
       $('#hiddendeclaration').val('');
      //$('#error_bussiness_image').fadeOut('slow');
      },30);
      flag = 0;
      $(".declaration_proof_text").hide();
    }else if(filesize2){
      $('#error_declaration_size').show();
      $('#error_declaration_size').fadeIn(300); 
      document.getElementById('error_declaration_size').innerHTML="File size should be maximum 300KB.";
      setTimeout(function(){
      $('#error_declaration_size').css('color','#B94A48');
      document.getElementById("declaration").value = "";
       $('#hiddendeclaration').val('');
      //$('#error_bussiness_image').fadeOut('slow');
      },30);
      flag = 0;
      $(".declaration_proof_text").hide();
    }

    if(flag=='1')
    {
      $('#error_declaration_size').html('');
      $('#error_declaration').html('');
      var files = !!this.files ? this.files : [];
      if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
  
      if (/^image/.test( files[0].type)){ // only image file
        var reader = new FileReader(); // instance of the FileReader
        reader.readAsDataURL(files[0]); // read the local file
        reader.onloadend = function(){ // set image data as background of div
        $('#hiddendeclaration').val(this.result);
        }
      }
      
       readURL(this,'image_upload_declaration_preview');
      return true;
    }
    else
    {
       return false;
     }
  });
});
</script>
<script>
document.onkeydown = function(e) {
      // keycode for F5 function
      if (e.keyCode === 123) {
        return false;
      }
     };
</script>
<script>
  /* --------------------- EMAIL VERIFCATION CODE BY POOJA MANE ON : 2024-11-14---------------------*/
  $('.verify-otp').click(function() 
  {
    var otp         = $('#otp').val();
    var verify_type = $(this).attr('data-verify-type');
    var email     = $('#email').val();
      var type      = 'verify_otp';
      
      var data = {};
      data.email     = email;
      data.otp       = otp;
      data.verify_type = verify_type;
    if (otp != '' && otp != undefined) 
    {
      send_verify_otp(type,data,this)       
    } else {
      alert('Please enter the OTP.');
    } 
  })

  $('.verify-otp-mobile').click(function() 
  {
    var otp         = $('#otp_mobile').val();
    var verify_type = $(this).attr('data-verify-type');
    var mobile    = $('#mobile').val();
      var type      = 'verify_otp';
      
      var data = {};
      data.mobile    = mobile;
      data.otp       = otp;
      data.verify_type = verify_type;
    if (otp != '' && otp != undefined) 
    {
      send_verify_otp_mobile(type,data,this)        
    } else {
      alert('Please enter the OTP.');
    } 
  })

  $('#reset_btn').click(function() {
    $('#email').attr('readonly',false);
    $('#email').val('');
    $('#send_otp_btn').show();
    $('#reset_btn').hide();
    $('.verify-otp-section').hide();
    emailVerify = false;
    $('#email_verify_status').val('no');
    $('#otp').val('');
  })

  $('#reset_btn_mobile').click(function() {
    $('#mobile').attr('readonly',false);
    $('#mobile').val('');
    $('#send_otp_btn_mobile').show();
    $('#reset_btn_mobile').hide();
    $('.verify-otp-section-mobile').hide();
    mobileVerify = false;
    $('#mobile_verify_status').val('no');
    $('#otp_mobile').val('');
  })

  function send_verify_otp(type,data,selector) {
    $.ajax({
      type: 'POST',
      url: site_url + 'Home/send_otp/',
      data : {'email':data.email,'type':type,'otp':data.otp,'verify_type':data.verify_type},
      beforeSend: function(xhr) {
          $(selector).attr('disabled',true).text('Processing..')  
        },
      async: true,
      success: function(otp_response) {
        var json_otp_response = JSON.parse(otp_response);
        if (json_otp_response.status) {
          if (type == 'send_otp') {
            $('#send_otp_btn').hide();
            $('#send_otp_btn').attr('disabled',false).text('Get OTP')
            $('.verify-otp-section').show();
            $('#reset_btn').show(); 
          } else if (type == 'resend_otp') {
            $(selector).attr('disabled',false).text('Resend OTP')
            $('.verify-otp-section').show();
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
            $('.verify-otp-section').hide();
            emailVerify = true;
            $('#email_verify_status').val('yes');
          }

          $('.email-id').removeClass('parsley-error');
          $('.email-id').addClass('parsley-success');
          $('#email').attr('readonly',true);
          
          alert(json_otp_response.msg);
        } else {
          if (type == 'send_otp') {
            $(selector).attr('disabled',false).text('Get OTP')
          } else if (type == 'resend_otp') { 
            $(selector).attr('disabled',false).text('Resend OTP')
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
          } 
          alert(json_otp_response.msg); 
        }
        $('#otp').val('');
      }
    });
  }

  function send_verify_otp_mobile(type,data,selector) {
    $.ajax({
      type : 'POST',
      url  : site_url + 'Home/send_otp_mobile/',
      data : {'mobile':data.mobile,'type':type,'otp':data.otp,'verify_type':data.verify_type},
      beforeSend: function(xhr) {
          $(selector).attr('disabled',true).text('Processing..')  
        },
      async: true,
      success: function(otp_response) {
        var json_otp_response = JSON.parse(otp_response);
        if (json_otp_response.status) {
          if (type == 'send_otp') {
            $('#send_otp_btn_mobile').hide();
            $('#send_otp_btn_mobile').attr('disabled',false).text('Get OTP')
            $('.verify-otp-section-mobile').show();
            $('#reset_btn_mobile').show();  
          } else if (type == 'resend_otp') {
            $(selector).attr('disabled',false).text('Resend OTP')
            $('.verify-otp-section-mobile').show();
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
            $('.verify-otp-section-mobile').hide();
            emailVerify = true;
            $('#mobile_verify_status').val('yes');
          }

          $('.mobile').removeClass('parsley-error');
          $('.mobile').addClass('parsley-success');
          $('#mobile').attr('readonly',true);
          
          alert(json_otp_response.msg);
        } else {
          if (type == 'send_otp') {
            $(selector).attr('disabled',false).text('Get OTP')
          } else if (type == 'resend_otp') { 
            $(selector).attr('disabled',false).text('Resend OTP')
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
          } 
          alert(json_otp_response.msg); 
        }
        $('#otp_mobile').val('');
      }
    });
  }

  $('.send-otp').click(function() {
      var email = $('#email').val();
      var type  = $(this).attr('data-type');
      var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Regular expression for email format
      
      if (type == 'resend_otp') {
        $('#otp').val('');
      }
      var data = {};
      data.email       = email;
      data.otp       = '';
      data.verify_type = '';
        
      if (email.trim() != '') {
          if (emailRegex.test(email)) {
              send_verify_otp(type,data,this)
          } else {
            $('.email-id').addClass('parsley-error');
              $('#email').focus();
              alert('Please enter a valid email address.');
          }
      } else {
        $('.email-id').addClass('parsley-error');
          $('#email').focus();
          alert('Please enter email id first.');
      }
  })

  $('.send-otp-mobile').click(function() {
      var mobile = $('#mobile').val();
      var type  = $(this).attr('data-type');
      
      // if($.isNumeric(mobile) && mobile.length === 10){
        //     $("#result").text("Valid mobile number");
        // } else {
        //     $("#result").text("Invalid mobile number");
        // }

      if (type == 'resend_otp') {
        $('#otp_mobile').val('');
      }
      var data = {};
      data.mobile      = mobile;
      data.otp       = '';
      data.verify_type = '';
        
      if (mobile.trim() != '') {
          if (mobile.length == 10 && $.isNumeric(mobile) && !mobile.includes('.')) {
              send_verify_otp_mobile(type,data,this)
          } else {
            if ( !$.isNumeric(mobile) || mobile.includes('.')) {
              $('.mobile').addClass('parsley-error');
                $('#mobile').focus();
                alert('Characters and special characters not allowed.');
            } else {
              $('.mobile').addClass('parsley-error');
                $('#mobile').focus();
                alert('Please enter a atleast 10 digit mobile no.');
            }
          }
      } else {
        $('.mobile').addClass('parsley-error');
          $('#mobile').focus();
          alert('Please enter mobile no. first.');
      }
  })
/* --------------------- EMAIL VERIFCATION CODE BY POOJA MANE ON : 2024-11-14---------------------*/
</script>
