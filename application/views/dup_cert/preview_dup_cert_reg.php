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
	font-weight:400;
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
</style>
 <?php 
//header('Cache-Control: must-revalidate');
//header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="container">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	   <h1 class="register"> 
		   Application for Duplicate Certificate(NOT Applicable for Examination held after 1-Oct-2019)
		   </h1><br/>
		   <?php if($this->session->userdata['userinfo']['is_dra_mem'] == '1'){?>
      <h1>
      Please go through the given detail, correction may be made if necessary  <a href=<?php if(!empty($user_details[0]['registrationtype'])){if($user_details[0]['registrationtype']=='NM'){echo base_url('nonmem');}else{echo base_url();}}else{echo base_url();} ?> target="_blank"><span style="color:#F00">edit profile</span></a></h1>
	  <?php } else { ?>
	  <h1> Please go through the given detail, correction may be made if necessary.<a  href="javascript:window.history.go(-1);">Modify</a> </h1>
	  <?php } ?>
     <!--<a  href="javascript:window.history.go(-1);">Modify</a>-->
   
      
      
      <br>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="<?php echo base_url()?>DupCert/register">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
         
            <!-- /.box-header -->
            <!-- form start -->
            
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
			<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
                 <div style="float:right;">
           <!--   <a  href="javascript:window.history.go(-1);">Back</a>-->
            </div>
            </div>
			<?php if($this->session->userdata['userinfo']['is_dra_mem'] == '1') { ?>
			 <div class="box-body">
				<div class="form-group">
					  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
					  <div class="col-sm-6">
					<?php  $username=$user_details[0]['firstname'].' '.$user_details[0]['middlename'].' '.$user_details[0]['lastname'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
		               echo $userfinalstrname;?>
                      
						<!--<input type="text" class="form-control" id="name" name="name" placeholder="Name Of Candidate"  value="<?php echo set_value('name');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">-->
						<span class="error"><?php //echo form_error('nameOfBank');?></span> </div>
				</div>
				
			    <div class="form-group">
					 <label for="roleid" class="col-sm-3 control-label">Examination (select the correct name)*</label>
					  <div class="col-sm-5"  style="display:block" id="edu">
					  <?php  if(count($exam_name))
					  {
						 foreach($exam_name as $exams_row)
						 {
							if($this->session->userdata['userinfo']['exam_name']==$exams_row[0]['description']){echo  $exams_row[0]['description'];}
							} 
						
							
					} ?>
						<span class="error"><?php //echo form_error('designation');?></span>
					</div>
				</div>	
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email *</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['email']?>
                      
                    </div>
                </div>  
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile *</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['mobile']?>
                      
                    </div>
                </div>  
                
				<!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['address1'];?>
                    </div>  
                </div>-->
                
               <!-- <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['address2'];?>
                    </div>  
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['address3'];?>  
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <?php echo $user_details[0]['address4'];?>  
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District *</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['userinfo']['district']; ?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City *</label>
                	<div class="col-sm-5">
                      <?php echo $user_details[0]['city']; ?>
                    </div>
                </div>
                
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State *</label>
                	<div class="col-sm-2">
                  <?php  
							//echo $states[0]['state_name'];
							if(count($states) > 0){
								foreach($states as $row1){
								    if($user_details[0]['state'] == $row1['state_code'])
								    {
										echo $row1['state_name'];
								    }
								}
							
							}
				  ?>
                        
                    
                    </div>
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode *</label>
                     <div class="col-sm-2">
                     <?php echo $user_details[0]['pincode'];?>
                    </div>
                    
                </div>-->
				<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Fees *</label>
						<div class="col-sm-6">
						  <?php echo $this->session->userdata['userinfo']['fees'];?>
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
				</div>
				
                <?php 
				$star='';
                if($this->session->userdata['userinfo']['state']!='ASS' && $this->session->userdata['userinfo']['state']!='JAM' && $this->session->userdata['userinfo']['state']!='MEG')
				{
						$star='*';
				}
				?>
            </div>
			<?php }else{ ?>
			<div class="box-body">
				<div class="form-group">
					  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
					  <div class="col-sm-6">
					<?php  $username=$this->session->userdata['userinfo']['firstname'].' '.$this->session->userdata['userinfo']['middlename'].' '.$this->session->userdata['userinfo']['lastname'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
		               echo $userfinalstrname;?>
                      
						<!--<input type="text" class="form-control" id="name" name="name" placeholder="Name Of Candidate"  value="<?php echo set_value('name');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">-->
						<span class="error"><?php //echo form_error('nameOfBank');?></span> 
					</div>
				</div>
				
			    <div class="form-group">
					 <label for="roleid" class="col-sm-3 control-label">Examination (select the correct name)*</label>
					  <div class="col-sm-5"  style="display:block" id="edu">
					  <?php if(count($exams))
					  {
						 foreach($exams as $exams_row)
						 {
							if($this->session->userdata['userinfo']['sel_exam']==$exams_row['exam_code']){echo  $exams_row['description'];}
							} 
					  } ?>
						<span class="error"><?php //echo form_error('designation');?></span>
					</div>
				</div>	
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email *</label>
                	<div class="col-sm-5">
                    <?php //echo $user_details[0]['email']?>
                    <?php echo $this->session->userdata['userinfo']['email']?>
                      
                    </div>
                </div>  
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile *</label>
                	<div class="col-sm-5">
                    <?php //echo $user_details[0]['mobile']?>
                    <?php echo $this->session->userdata['userinfo']['mobile']?>
                      
                    </div>
                </div>  
           
                
               <!-- <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1</label>
                	<div class="col-sm-5">
                    <?php //echo $user_details[0]['address2'];?>
                    <?php echo $this->session->userdata['userinfo']['addressline1'];?>
                    </div>  
                </div>
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                    <?php //echo $user_details[0]['address2'];?>
                    <?php echo $this->session->userdata['userinfo']['addressline2'];?>
                    </div>  
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                    <?php //echo $user_details[0]['address3'];?>   
                    <?php echo $this->session->userdata['userinfo']['addressline3'];?>   
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <?php //echo $user_details[0]['address4'];?>  
                      <?php echo $this->session->userdata['userinfo']['addressline4'];?>  
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District *</label>
                	<div class="col-sm-5">
                      <?php //echo $user_details[0]['district']?>
                      <?php echo $this->session->userdata['userinfo']['district'];?>
                    </div>
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City *</label>
                	<div class="col-sm-5">
                      <?php //echo $user_details[0]['city']?>
                      <?php echo $this->session->userdata['userinfo']['city'];?>
                    </div>
                </div>
                
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State *</label>
                	<div class="col-sm-2">
                    <?php  //echo $this->session->userdata['userinfo']['state']; 
							if(count($states) > 0){
								foreach($states as $row1){
								    if($this->session->userdata['userinfo']['state'] == $row1['state_code'])
								    {
										echo $row1['state_name'];
								    }
								}
							
							}
				    ?> </div>
					
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode *</label>
                     <div class="col-sm-2">
                     <?php //echo $user_details[0]['pincode'] ;?>
                     <?php echo $this->session->userdata['userinfo']['pincode']; ?>
                    </div>
                    
                </div>-->
				<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Fees *</label>
						<div class="col-sm-6">
						  <?php echo $this->session->userdata['userinfo']['fees'];?>
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
				</div>
				
                <?php 
				$star='';
                if($this->session->userdata['userinfo']['state']!='ASS' && $this->session->userdata['userinfo']['state']!='JAM' && $this->session->userdata['userinfo']['state']!='MEG')
				{
						$star='*';
				}
				?>
            </div>
			<?php } ?>
             
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