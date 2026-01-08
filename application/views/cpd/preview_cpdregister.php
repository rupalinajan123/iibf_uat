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
</style>
 <?php 
//header('Cache-Control: must-revalidate');
//header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="container">
  <?php ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	 <h1 class="register"> 
		    CPD - REGISTRATION
		   </h1><br/>
      <h1>
      Please go through the given detail, correction may be made if necessary  <a href=<?php echo base_url();?> target="_blank"><span style="color:#F00">edit profile</span></a></h1>
      <br>
     
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="<?php echo base_url()?>Cpd/register">
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
						<?php echo $user_details[0]['namesub'];?>
                    </div>
                    <div class="col-sm-0">
                        <?php echo $user_details[0]['firstname'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                      <?php echo @$user_details[0]['middlename'];?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                      <?php echo @$user_details[0]['lastname'];?>
                    </div>
                </div>
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email *</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['email']?>
                      
                    </div>
                </div>  
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Contact No *</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['mobile']?> 
                    </div>
                </div>  
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1 *</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['address1'];?> 
                    </div>  
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                    <?php echo $user_details[0]['address2'];?>
                    </div>  
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                    <?php echo @$user_details[0]['address3'];?>  
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <?php echo @$user_details[0]['address4'];?>  
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District *</label>
                	<div class="col-sm-5">
                      <?php echo $user_details[0]['district']; ?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City *</label>
                	<div class="col-sm-5">
                      <?php echo $user_details[0]['city']?>
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
                        
                        
                    <!--<select class="form-control" id="state" name="state" required >
                        <option value="">Select</option>
                        <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                        <option value="<?php echo $row1['id'];?>" 
						<?php if($this->session->userdata['cpduserinfo']['state']==$row1['id']){echo  'selected="selected"';}?>><?php echo $row1['state_name'];?></option>
                        <?php } } ?>
                      </select>-->
                    
                    
      
                    </div><!--(Max 6 digits) -->
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode *</label>
                     <div class="col-sm-2">
                     <?php echo $user_details[0]['pincode'];?>
                    </div>
                    
                </div>
                <div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Qualification *</label>
						<div class="col-sm-4">
						  <?php if($user_details[0]['qualification']=='U'){echo  'Under Graduate';}?>
						  <?php if($user_details[0]['qualification']=='G'){echo  'Graduate';}?>
						  <?php if($user_details[0]['qualification']=='P'){echo  'Post Graduate';}?>
						</div>
                </div>
                 
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify *</label>
                	<div class="col-sm-5">
                    <?php 
					if($user_details[0]['qualification']=='U')
					{
						 if(count($undergraduate))
						 {
                            foreach($undergraduate as $row1)
							{ 	
                        		if($user_details[0]['specify_qualification']==$row1['qid']){echo  $row1['name'];}
                       		}
                       	 } 
					}?>
                   
                      <span class="error"><?php //echo form_error('eduqual1');?></span>
                    </div>
                    
                    <div class="col-sm-5"  <?php /*if($this->session->userdata['cpduserinfo']['optedu']=='G' && $this->session->userdata['cpduserinfo']['eduqual2']){echo 'style="display:block"';}else{echo 'style="display:none"';}*/?> id="GR">
					
					<?php 
					if($user_details[0]['qualification']=='G')
					{
						if(count($graduate))
						{
                            foreach($graduate as $row2)
							{ 	
                        		if($user_details[0]['specify_qualification']==$row2['qid']){echo  $row2['name'];}
                       		}
                       	} 
					}?>
                     
                      <span class="error"><?php //echo form_error('eduqual2');?></span>
                    </div>
                    
                    
                    <div class="col-sm-5"  <?php /*if($this->session->userdata['cpduserinfo']['optedu']=='P' && $this->session->userdata['cpduserinfo']['eduqual3']){echo 'style="display:block"';}else{echo 'style="display:none"';}*/?>id="PG">
                    <?php 
					if($user_details[0]['qualification']=='P')
					{
						 if(count($postgraduate))
						 {
                            foreach($postgraduate as $row3)
							{ 	
                        		if($user_details[0]['specify_qualification']==$row3['qid']){echo  $row3['name'];}
                       		  }
                       	   } 
					}?>
                    
                      <span class="error"><?php //echo form_error('eduqual3');?></span>
                    </div>
                </div>
				<div class="form-group">
					 <label for="roleid" class="col-sm-3 control-label">Designation *</label>
					  <div class="col-sm-5"  style="display:block" id="edu">
					  <?php if(count($designation))
					  {
						 foreach($designation as $designation_row)
						 {
							if($user_details[0]['designation']==$designation_row['dcode']){echo  $designation_row['dname'];}
							} 
					  } ?>
						<span class="error"><?php //echo form_error('designation');?></span>
					</div>
				</div>
				<div class="form-group">
					 <label for="roleid" class="col-sm-3 control-label">Bank Name *</label>
					  <div class="col-sm-5"   id="edu">
					  <?php if(count($institution_master))
					  {
						  foreach($institution_master as $institution_row)
							{ 	
								if($user_details[0]['associatedinstitute']==$institution_row['institude_id']){echo  $institution_row['name'];}
							}
					} ?>
								
						<span class="error"><?php //echo form_error('institutionworking');?></span>
					</div>
				</div>
		<?php
                        //get branch if office is blank
			$office = '';
			 if($user_details[0]['office'] !='')
			 {
			    $office = $user_details[0]['office'];
			 }
			 elseif($user_details[0]['branch'] !='')
			 {
			    $office = $user_details[0]['branch'];
			 }
			 else
			 {
			    $office = $user_details[0]['office'];
			 }
	        
	
			//$editedon = date('Y-m-d', strtotime($user_details[0]['createdon']));
			/*$editedon = date('Y-m-d', strtotime($user_details[0]['editedon']));
			if($editedon < "2016-12-29")
			{
				$office = $user_details[0]['branch'];
			}
			else if($editedon >= "2016-12-29")
			{
				if(is_numeric($user_details[0]['office']))
				{
					if($user_details[0]['branch']!='')
						$office = $user_details[0]['branch'];
					else
						$office = $user_details[0]['office'];
				}
				else
				{
					if($user_details[0]['branch']!='')
						$office = $user_details[0]['branch'];
					else
						$office = $user_details[0]['office'];
				}
			}*/
							
		?>
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Branch/Office address *</label>
                	<div class="col-sm-5">
				  <?php echo $user_details[0]['office'];?>
                    </div>
                </div>
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Number of years experience *</label>
                	<div class="col-sm-6">
						<?php echo $this->session->userdata['cpduserinfo']['experience'];?>
                    </div>
                </div>
					<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Fees *</label>
						<div class="col-sm-6">
						  <?php echo $this->session->userdata['cpduserinfo']['fees'];?>
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
				</div>
                
                <?php 
				$star='';
                if($this->session->userdata['cpduserinfo']['state']!='ASS' && $this->session->userdata['cpduserinfo']['state']!='JAM' && $this->session->userdata['cpduserinfo']['state']!='MEG')
				{
						$star='*';
					}
				?>
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