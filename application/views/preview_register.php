<style>
.modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
    width: 920px;
    min-width: 300px;   
}

#confirm .modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
  	width: 420px;
    min-width: 400px;   
}
.skin-blue .main-header .navbar {
	background-color:#fff;
}
body.layout-top-nav .main-header h1 {
	color:#0699dd;
	margin-bottom:0;
	margin-top:30px;
}
.container {
	position:relative;
}
.box-header.with-border {
	background-color:#7fd1ea;
	border-top-left-radius:0;
	border-top-right-radius:0;
	margin-bottom:10px;
}
.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
	margin-bottom:0 !important;
}
.box {
	border:none;
	box-shadow:none;
	border-radius:0;
	margin-bottom:0;
}
.nobg {
	background:none !important;
	border:none !important;
}
.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
	margin:0;
}
.blue_bg {
	background-color:#e7f3ff;
}
.m_t_15 {
	margin-top:15px;
}
.main-footer {
	padding-left:160px;
	padding-right:160px;
}
.content-header > h1 {
	font-size:22px;
	font-weight:600;
}
h4 {
	margin-top:5px;
	margin-bottom:10px !important;
	font-size:14px;
	line-height:18px;
	padding:0 5px;
	font-weight:600;
	text-align:justify;
}
.form-horizontal .control-label {
	padding-top:4px;
}
.pad_top_2 {
	padding-top:2px !important;
}
.pad_top_0 {
	padding-top:0px !important;
}

div.form-group:nth-child(odd) {
	background-color:#dcf1fc;
	padding:5px 0;
}

#confirmBox
{
    display: none;
    background-color: #eee;
    border-radius: 5px;
    border: 1px solid #aaa;
    position: fixed;
    width: 300px;
    left: 50%;
    margin-left: -150px;
    padding: 6px 8px 8px;
    box-sizing: border-box;
    text-align: center;
	z-index:1;
	box-shadow:0 1px 3px #000;
}
#confirmBox .button {
    background-color: #ccc;
    display: inline-block;
    border-radius: 3px;
    border: 1px solid #aaa;
    padding: 2px;
    text-align: center;
    width: 80px;
    cursor: pointer;
}
#confirmBox .button:hover
{
    background-color: #ddd;
}
#confirmBox .message
{
    text-align: left;
    margin-bottom: 8px;
}
.form-group {
	margin-bottom:10px;
}
.form-horizontal .form-group {
	margin-left:0;
	margin-right:0;

}
.form-control {
	border-color:#888;
}
.form-horizontal .control-label {
	font-weight:normal;
}
a.forget  {color:#9d0000;}
a.forget:hover {color:#9d0000; text-decoration:underline;}
ol li {
	line-height:18px;
}
.example {
	text-align:left !important;
	padding:0 10px;
}

.form-horizontal .control-label {
  font-weight: normal;
  text-align: left;
}
</style>
 <?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="container">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Please go through the given detail, correction may be made if necessary.
     <a  href="javascript:window.history.go(-1);">Modify</a>
      
		
      </h1>
      <br>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="<?php echo base_url()?>register/addmember/">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
                 <div style="float:right;">
           <!--   <a  href="javascript:window.history.go(-1);">Back</a>-->
            </div>
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
                <label for="roleid" class="col-sm-3 control-label">First Name *</label>
                	<div class="col-sm-1">
                  <?php echo $this->session->userdata['enduserinfo']['sel_namesub'];?>
                    <!--<select name="sel_namesub" id="sel_namesub" class="form-control">
                    <option value="Mr." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Mr.'){echo  'selected="selected"';}?>>Mr.</option>
                    <option value="Mrs." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Mrs.'){echo  'selected="selected"';}?>>Mrs.</option>
                    <option value="Ms." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Ms.'){echo  'selected="selected"';}?>>Ms.</option>
                    <option value="Dr." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Dr.'){echo  'selected="selected"';}?>>Dr.</option>
                    <option value="Prof." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Prof.'){echo  'selected="selected"';}?>>Prof.</option>
                    </select>-->
                    </div><!--(Max 30 Characters) -->
                    
                     <div class="col-sm-0">
                        <?php echo $this->session->userdata['enduserinfo']['firstname'];?>
                        <!--<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required 
                        value="<?php echo $this->session->userdata['enduserinfo']['firstname']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['middlename'];?>
                      <!--<input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $this->session->userdata['enduserinfo']['middlename']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['lastname'];?>
                      <!--<input type="text" class="form-control" id="middlename" name="lastname" placeholder="Last Name"  value="<?php echo $this->session->userdata['enduserinfo']['lastname']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Full Name *</label>
                	<div class="col-sm-5">
                     <?php echo $this->session->userdata['enduserinfo']['nameoncard'];?>
                      <!--<input type="text" class="form-control" id="nameoncard" name="nameoncard" placeholder="Full Name" required value="<?php echo $this->session->userdata['enduserinfo']['nameoncard']?>"  data-parsley-maxlength="35" >-->
                      <span class="error"><?php //echo form_error('nameoncard');?></span>
                    </div><!--(Max 35 Characters) -->
                </div>
				
                </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
                 
              <h3 class="box-title">Contact Details</h3>
           
            </div>

            <div class="box-header with-border">
              <h6 class="box-title">Office/Residential Address for communication (Pl do not repeat the name of the Applicant, Only Address to be typed)</h6>
            </div>
                        
            
            <div class="box-body">
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1 *</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['addressline1'];?>
                      <!--<input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $this->session->userdata['enduserinfo']['addressline1']?>"  data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('addressline1');?></span>
                    </div>
                   <!-- (Max 30 Characters) -->
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['addressline2'];?>
                      <!--<input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $this->session->userdata['enduserinfo']['addressline2']?>"  data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div>
                   <!-- (Max 30 Characters) -->
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['addressline3'];?>
                      <!--<input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $this->session->userdata['enduserinfo']['addressline3']?>"  data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('addressline3');?></span>
                    </div>
                    <!--(Max 30 Characters) -->
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['addressline4'];?>
                      <!--<input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $this->session->userdata['enduserinfo']['addressline4']?>" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('addressline4');?></span>
                    </div>
                   <!-- (Max 30 Characters) -->
                    
                    
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District *</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['district']?>
                      <!--<input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo $this->session->userdata['enduserinfo']['district']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('district');?></span>
                    </div>
                   <!-- (Max 30 Characters) -->
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City *</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['city']?>
                      <!--<input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $this->session->userdata['enduserinfo']['city']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('city');?></span>
                    </div>
                   <!-- (Max 30 Characters) -->
                </div>
                
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State *</label>
                	<div class="col-sm-2">
                  <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                       	<?php if($this->session->userdata['enduserinfo']['state']==$row1['state_code']){echo  $row1['state_name'];}?>
                        <?php } } ?>
                        
                    <!--<select class="form-control" id="state" name="state" required >
                        <option value="">Select</option>
                        <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                        <option value="<?php echo $row1['id'];?>" 
						<?php if($this->session->userdata['enduserinfo']['state']==$row1['id']){echo  'selected="selected"';}?>><?php echo $row1['state_name'];?></option>
                        <?php } } ?>
                      </select>-->
                    
                    
      
                    </div><!--(Max 6 digits) -->
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode *</label>
                   
                     <div class="col-sm-2">
                     <?php echo $this->session->userdata['enduserinfo']['pincode'];?>
                        <!--<input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo $this->session->userdata['enduserinfo']['pincode']?>"  data-parsley-maxlength="6" maxlength="6" size="6">-->
                         <span class="error"><?php //echo form_error('pincode');?></span>
                    </div>
                    
                </div>
 
 <!------------------------------| Permenent Address : Added By Bhushan|--------------------------->
               <div class="box box-info">
                 <div class="box-header with-border">
              		<h3 class="box-title">Permanent Address Details</h3>
               	 </div> 
               
                <div class="form-group">
               	 <label for="roleid" class="col-sm-3 control-label">Address line1 *</label>
                	<div class="col-sm-5">
                    	<?php echo $this->session->userdata['enduserinfo']['addressline1_pr'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['addressline2_pr'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['addressline3_pr'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['addressline4_pr'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District *</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['district_pr']?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City *</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['city_pr']?>
                    </div>
                </div>
                
              	<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State *</label>
                    <div class="col-sm-2">
                    <?php
                    if(count($states) > 0){
                        foreach($states as $row1){ 	 
                            if($this->session->userdata['enduserinfo']['state_pr']== $row1['state_code']){
                                echo  $row1['state_name'];
                            } 
                        } 
                    } 
                    ?>
                    </div>
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode *</label>
                     <div class="col-sm-2">
                     <?php echo $this->session->userdata['enduserinfo']['pincode_pr'];?>
                    </div>
               	</div>
              </div>
             
<!-----------------------------------------| Close Permenent Address Tab |------------------------------------->
              
              
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>
                	<div class="col-sm-2">
                    <?php echo $this->session->userdata['enduserinfo']['dob'];?>
                      <!--<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo $this->session->userdata['enduserinfo']['dob'];?>">-->
                      <span class="error"><?php //echo form_error('dob');?></span>
                    </div>
                </div>
                
                
                    <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender *</label>
       
                    <div class="col-sm-2">
                	<?php if($this->session->userdata['enduserinfo']['gender']=='female'){echo 'Female';}?>
                    <?php if($this->session->userdata['enduserinfo']['gender']=='male'){echo  ' Male';}?>
                    </div>
                </div>
                
                
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification *</label>
                	<div class="col-sm-4">
                 	  <?php if($this->session->userdata['enduserinfo']['optedu']=='U'){echo  'Under Graduate';}?>
                      <?php if($this->session->userdata['enduserinfo']['optedu']=='G'){echo  'Graduate';}?>
						<?php if($this->session->userdata['enduserinfo']['optedu']=='P'){echo  'Post Graduate';}?>
                        
                    <!--  <input type="radio" class="minimal" id="U"   name="optedu"  checked="checked" value="U" onclick="changedu(this.value)" 
					  <?php if($this->session->userdata['enduserinfo']['optedu']=='U'){echo  'checked="checked"';}?>>
                        Under Graduate
                        <input type="radio" class="minimal" id="G"   name="optedu"  value="G" onclick="changedu(this.value)" 
                         <?php if($this->session->userdata['enduserinfo']['optedu']=='G'){echo  'checked="checked"';}?>>
                        Graduate
                        <input type="radio" class="minimal" id="P"   name="optedu"  value="P"   onclick="changedu(this.value)" 
						<?php if($this->session->userdata['enduserinfo']['optedu']=='P'){echo  'checked="checked"';}?>>
                        Post Graduate-->
                      <span class="error"><?php //echo form_error('optedu');?></span>
                    </div>
                </div>
                 
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify *</label>
                    
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
                    <!--  <select class="form-control" id="eduqual1" name="eduqual1" <?php if($this->session->userdata['enduserinfo']['optedu']=='U'){echo 'required';}?> >
                        <option value="">--Select--</option>
                        <?php /*if(count($undergraduate)){
                                foreach($undergraduate as $row1){ 	?>
                        <option value="<?php echo $row1['qid'];?>" 
						<?php if($this->session->userdata['enduserinfo']['eduqual1']==$row1['qid']){echo  'selected="selected"';}?>><?php echo $row1['name'];?></option>
                        <?php } }*/ ?>
                      </select>-->
                      <span class="error"><?php //echo form_error('eduqual1');?></span>
                    </div>
                    
                    <div class="col-sm-5"  <?php /*if($this->session->userdata['enduserinfo']['optedu']=='G' && $this->session->userdata['enduserinfo']['eduqual2']){echo 'style="display:block"';}else{echo 'style="display:none"';}*/?> id="GR">
					
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
                    
					
                      <!--<select class="form-control" id="eduqual2" name="eduqual2" <?php if($this->session->userdata['enduserinfo']['optedu']=='G'){echo 'required';}?> >
                        <option value="">--Select--</option>
                        <?php /*if(count($graduate)){
                                foreach($graduate as $row2){ 	?>
                        <option value="<?php echo $row2['qid'];?>" 
						<?php if($this->session->userdata['enduserinfo']['eduqual2']==$row2['qid']){echo  'selected="selected"';}?>><?php echo $row2['name'];?></option>
                        <?php } }*/ ?>
                      </select>-->
                      <span class="error"><?php //echo form_error('eduqual2');?></span>
                    </div>
                    
                    
                    <div class="col-sm-5"  <?php /*if($this->session->userdata['enduserinfo']['optedu']=='P' && $this->session->userdata['enduserinfo']['eduqual3']){echo 'style="display:block"';}else{echo 'style="display:none"';}*/?>id="PG">
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
                    
                      <!--<select class="form-control" id="eduqual3" name="eduqual3" <?php if($this->session->userdata['enduserinfo']['optedu']=='P'){echo 'required';}?>>
                        <option value="">--Select--</option>
                        <?php /*if(count($postgraduate)){
                                foreach($postgraduate as $row3){ 	?>
                        <option value="<?php echo $row3['qid'];?>" 
						<?php if($this->session->userdata['enduserinfo']['eduqual3']==$row3['qid']){echo  'selected="selected"';}?>><?php echo $row3['name'];?></option>
                        <?php } }*/ ?>
                      </select>-->
                      <span class="error"><?php //echo form_error('eduqual3');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
						<label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00">*</span></label>
						<div class="col-sm-5">
						<?php echo $this->session->userdata['enduserinfo']['bank_emp_id'];?>
						</div>
					</div>
              
              <div class="form-group">
             <label for="roleid" class="col-sm-3 control-label">Bank/Institution working *</label>
              <div class="col-sm-5"   id="edu">
              <?php if(count($institution_master))
			  {
				  foreach($institution_master as $institution_row)
					{ 	
                  		if($this->session->userdata['enduserinfo']['institution']==$institution_row['institude_id']){echo  $institution_row['name'];}
                      }
			} ?>
                        
           	  <!--<select id="institutionworking" name="institutionworking" class="form-control" required>
				 <option value="">--Select--</option>
                 <?php /*if(count($institution_master)){
                                foreach($institution_master as $institution_row){ 	?>
                        <option value="<?php echo $institution_row['institude_id'];?>" 
						<?php if($this->session->userdata['enduserinfo']['institution']==$institution_row['institude_id']){echo  'selected="selected"';}?>><?php echo $institution_row['name'];?></option>
                        <?php } } */?>
				</select>-->
                <span class="error"><?php //echo form_error('institutionworking');?></span>
            </div>
         </div>
                
         <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Branch/Office *</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['office'];?>
                    <!--  <input type="text" class="form-control" id="office" name="office" placeholder="Branch/Office" required value="<?php //echo $this->session->userdata['enduserinfo']['office'];?>"  data-parsley-maxlength="20" >-->
                      <span class="error"><?php //echo form_error('office');?></span>
                    </div>
                   <!-- (Max 20 Characters) -->
                </div>
                
                
                
                <div class="form-group">
             <label for="roleid" class="col-sm-3 control-label">Designation *</label>
              <div class="col-sm-5"  style="display:block" id="edu">
           	  <?php if(count($designation))
			  {
              	 foreach($designation as $designation_row)
				 {
                	if($this->session->userdata['enduserinfo']['designation']==$designation_row['dcode']){echo  $designation_row['dname'];}
                    } 
              } ?>
              <!--<select id="designation" name="designation" class="form-control" required>
				 <option value="">--Select--</option>
                 <?php /*if(count($designation)){
                                foreach($designation as $designation_row){ 	?>
                        <option value="<?php echo $designation_row['dcode'];?>" 
						<?php if($this->session->userdata['enduserinfo']['designation']==$designation_row['dcode']){echo  'selected="selected"';}?>><?php echo $designation_row['dname'];?></option>
                        <?php } }*/ ?>
				</select>-->
                <span class="error"><?php //echo form_error('designation');?></span>
            </div>
         </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of joining Bank/Institution  *</label>
                	<div class="col-sm-3">
                    <?php echo $this->session->userdata['enduserinfo']['doj']?>
                      <!--<input type="text" class="form-control pull-right" id="doj"  name="doj" placeholder="Date of joining Bank/Institution" required 
                      value="<?php echo $this->session->userdata['enduserinfo']['doj']?>">-->
                      <span class="error"><?php //echo form_error('doj');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email *</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['email']?>
                      <!--<input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo $this->session->userdata['enduserinfo']['email']?>"  data-parsley-maxlength="30" required>-->
                      <!--(Enter valid and correct email ID to receive communication)-->
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>  
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone </label>
                	<div class="col-sm-3">
                      STD Code :
                      <?php echo $this->session->userdata['enduserinfo']['stdcode'];?>
                      <!--<input type="text" class="form-control pull-right" id="stdcode"  name="stdcode" placeholder="STD Code"  value="<?php echo $this->session->userdata['enduserinfo']['stdcode'];?>">-->
                      <span class="error"><?php //echo form_error('stdcode');?></span>
                    </div>
                    <div class="">
                    Phone No :
                    <?php echo $this->session->userdata['enduserinfo']['phone'];?>
                      <!--<input type="text" class="form-control pull-right" id="phone"  name="phone" placeholder="Phone No"  data-parsley-minlength="7"
                      data-parsley-type="number" data-parsley-maxlength="12"    value="<?php echo $this->session->userdata['enduserinfo']['phone'];?>">-->
                      <span class="error"><?php //echo form_error('phone');?></span>
                    </div>
                </div>
                 
                 
                 
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile *</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['mobile'];?>
                      <!--<input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $this->session->userdata['enduserinfo']['mobile'];?>"  required>-->
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>  
                <?php 
				$star='';
                if($this->session->userdata['enduserinfo']['state']!='ASS' && $this->session->userdata['enduserinfo']['state']!='JAM' && $this->session->userdata['enduserinfo']['state']!='MEG')
				{
						$star='*';
					}
				?>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number<?php //echo $star;?></label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['aadhar_card'];?>
                      <span class="error"><?php //echo form_error('idNo');?></span>
                    </div>
                </div>
                
                
              <!--  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph *</label>
                	<div class="col-sm-5">
                        <img src="<?php echo $this->session->userdata['enduserinfo']['scannedphoto'];?>" > 
                          <div id="error_photo"></div>
                     <br>
                     <div id="error_photo_size"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your id proof *</label>
                	<div class="col-sm-5">
                        <img src="<?php echo $this->session->userdata['enduserinfo']['idproofphoto'];?>" > 
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen *</label>
                	<div class="col-sm-5">
                  <img src="<?php echo $this->session->userdata['enduserinfo']['scannedsignaturephoto'];?>" > 
                         
                    <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                </div>-->
                
                <div class="form-group">
                	
									<label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedphoto'];?>" height="100" width="100" ></label>
										
									<label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedsignaturephoto'];?>" height="100" width="100"></label>
											
									<label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['idproofphoto'];?>"  height="100" width="100"></label>

									<label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['declarationform'];?>"  height="100" width="100"></label>
								</div>  
                
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Uploaded Photo</label>
        			   <label for="roleid" class="col-sm-3 control-label">uploaded Signature</label>
                    	<label for="roleid" class="col-sm-3 control-label">Uploaded ID Proof</label>
                    	<label for="roleid" class="col-sm-3 control-label">Uploaded Declaration Form</label>
        	</div>
                
                <!-- <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label">Select Id Proof *</label>
                	<div class="col-sm-5">
                    <?php if(count($idtype_master) > 0)
											{
												foreach($idtype_master as $idrow)
												{?>
																				<?php if($this->session->userdata['enduserinfo']['idproof']==$idrow['id']){echo  $idrow['name'];}?>
																<?php 
												}
											}?>
                     
                     	
                      <span class="error"><?php //echo form_error('idproof');?></span>
                    </div>
                </div> -->
                
                 
                  <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">ID No.*</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['enduserinfo']['idNo'];?>
                       <input type="text" class="form-control pull-right" id="idNo"  name="idNo" placeholder="ID No." required value="<?php echo $this->session->userdata['enduserinfo']['idNo'];?>">
                      <span class="error"><?php //echo form_error('idNo');?></span>
                    </div>
                </div>-->
                 
                 
                  
            <!-- Benchmark Disability Code Start -->
			<div class="box-header with-border header_blue">
			  <h3 class="box-title">Disability</h3>
			</div>
			<div class="form-group">
			  <label for="roleid" class="col-sm-4 control-label">Person with Benchmark Disability</label>
			  <div class="col-sm-2">
			   <?php 
			   if($this->session->userdata['enduserinfo']['benchmark_disability']=='Y'){echo  'Yes';}
			   if($this->session->userdata['enduserinfo']['benchmark_disability']=='N'){echo  'No';}
			  ?>
				</div>
			</div>
			<?php 
			   if($this->session->userdata['enduserinfo']['benchmark_disability']=='Y'){ 
			  ?>
			<div id="benchmark_disability_div">
				<div class="form-group">
				  <label for="roleid" class="col-sm-4 control-label">Visually impaired</label>
				  <div class="col-sm-2">
					<?php 
						if($this->session->userdata['enduserinfo']['visually_impaired']=='Y'){echo  'Yes';}
						if($this->session->userdata['enduserinfo']['visually_impaired']=='N'){echo  'No';}
					?>
				 </div>
				</div>
				
				<?php 
						if($this->session->userdata['enduserinfo']['visually_impaired']=='Y'){
				?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5">
				   <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scanned_vis_imp_cert'];?>" height="100" width="100" ></label>
				  </div>
				</div>
				<?php
				}
				?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-4 control-label">Orthopedically handicapped</label>
				  <div class="col-sm-2">
				  <?php 
			   		if($this->session->userdata['enduserinfo']['orthopedically_handicapped']=='Y'){echo  'Yes';}
			   		if($this->session->userdata['enduserinfo']['orthopedically_handicapped']=='N'){echo  'No';}
			  	  ?>
				  </div>
				</div>
				<?php 
			   		if($this->session->userdata['enduserinfo']['orthopedically_handicapped']=='Y')
					{
				?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5">
				   <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scanned_orth_han_cert'];?>" height="100" width="100" ></label>
					</div>
				</div>
				<?php 
			   		}
				?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-4 control-label">Cerebral palsy</label>
				  <div class="col-sm-2">
				   <?php 
			   		if($this->session->userdata['enduserinfo']['cerebral_palsy']=='Y'){echo  'Yes';}
			   		if($this->session->userdata['enduserinfo']['cerebral_palsy']=='N'){echo  'No';}
			  		?>
				  </div>
				</div>
				<?php
				if($this->session->userdata['enduserinfo']['cerebral_palsy']=='Y'){
				?>
				<div class="form-group" id="cer_palsy_cert_div">
				  <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5"> <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scanned_cer_palsy_cert'];?>" height="100" width="100" ></label>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			
			<?php
			}
			?>	
		  <!-- Benchmark Disability Code End -->
                   
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-10 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
                	<div class="col-sm-2">
                       <?php if($this->session->userdata['enduserinfo']['optnletter']=='Y'){echo  'Yes';}?> 
						<?php if($this->session->userdata['enduserinfo']['optnletter']=='N'){echo  'No';}?>
                      <span class="error"><?php //echo form_error('optnletter');?></span>
                    </div>
                </div>
            </div>
             
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment">
                    </div>
              </div>
             </div>
                
             
             
        </div>
      </div>
     
      
      
    </section>
    </form>
  </div> 
  </div>
  
  <script>
  $(document).ready(function(){
	 function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}
createCookie('member_register_form','1','1');
	
	

	 });
  
  </script>