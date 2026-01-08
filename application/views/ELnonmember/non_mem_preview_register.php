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
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
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
    action="<?php echo base_url()?>ELNonreg/addmember/">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
              <div style="float:right;">
                  <!--<a  href="javascript:window.history.go(-1);">Back</a>-->
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
			 /* $sess = $this->session->userdata['enduserinfo'];
			  print_r($sess);
			 */
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

                <div class="form-group">
                <label class="col-sm-3 control-label">Full Name</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['enduserinfo']['nameoncard'];?>
                    </div>
                </div>
                
                </div>
                <!--<div class="col-sm-3">
                	<label for="roleid" class="col-sm-3 control-label"><img src="<?php //echo $this->session->userdata['enduserinfo']['scannedphoto'];?>" height="100" width="100" ></label>
        
                </div>-->
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
                                foreach($states as $row1){ 	?>
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
        <?php 	if(count($idtype_master) > 0)
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
       			
           
			<br />
            <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
            <div class="col-sm-5 ">
                <?php echo $this->session->userdata['enduserinfo']['fee'];?>
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
            
            
            
        
            	 
               
       </div>
       
       <div class="box-footer">
            <div class="col-sm-4 col-xs-offset-3">
                <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment">
                </div>
            </div>
  </div>
             
        <!---->
         
             
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