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
#confirmBox {
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
#confirmBox .button:hover {
	background-color: #ddd;
}
#confirmBox .message {
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
a.forget {
	color:#9d0000;
}
a.forget:hover {
	color:#9d0000;
	text-decoration:underline;
}
ol li {
	line-height:18px;
}
.example {
	text-align:left !important;
	padding:0 10px;
}
.content-header h1 {
    background-color: #7fd1ea;
    color: #fff;
    margin: 0 auto;
    padding: 5px 0;
    text-align: center;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
	<head>
		<?php $this->load->view('scribe_form/inc_header'); ?>
		
	</head>
<!-- Content Wrapper. Contains page content -->
<div class="container">

  <!-- Content Header (Page header) -->
   <section class="content-header box-header with-border" style="height: 48px; background-color: #1287C0; ">
      <h1 class="register"> Please go through the given details.<!-- <a  href="javascript:window.history.go(-1);" style="color:#0FF; font-size:25px" >Modify</a> --> </h1>
      <!-- <h1 class="register"> Please go through the given details.  <a href="<?php echo base_url();?>Scribe_form/getDetails_Scribe"  style="color:#0FF; font-size:25px" >Modify</a> </h1> -->
    </section>

   
    <br>
    <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>--> 
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12"> 
        <!-- Horizontal Form -->
        <div class="box box-info">
          <div class="box-header with-border header_blue">
           <h3 class="box-title ">Basic Details</h3>
            <div style="float:right;"> 
              <!--   <a  href="javascript:window.history.go(-1);">Back</a>--> 
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php //print_r($this->session->userdata);echo "<br>"; ?> 
    <?php //print_r($this->session->userdata['enduserinfo']['photoid_no']);echo "bjhcvjv";die?>  

    <!-- /.box-header --> 
    <!-- form start -->
    <div class="box-body">
      <?php //echo validation_errors(); ?>
      <?php if($this->session->flashdata('error')!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
        <?php echo $this->session->flashdata('error'); ?> </div>
      <?php } if($this->session->flashdata('success')!=''){ ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
        <?php echo $this->session->flashdata('success'); ?> </div>
      <?php } 
			 if(validation_errors()!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
        <?php echo validation_errors(); ?> </div>
      <?php } 
			 ?>
      <form class="form-horizontal" name="bankquestForm" id="bankquestForm"  method="post"   action="<?php echo base_url()?>Scribe_form/addspecial">
<?php//echo $this->session->userdata;die;?> 
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Membership No </label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['member_no']?> 
               
              <span class="error">
              <?php //echo form_error('email');?>
              </span> </div>
          </div>

          <div class="form-group">
          <label for="roleid" class="col-sm-4 control-label"> First Name *</label>
          <div class="col-sm-1"> <?php echo $this->session->userdata['enduserinfo']['sel_namesub'];?> 
          
          </div>
        <?php if(isset($this->session->userdata['enduserinfo']['firstname']))
		  {
			  echo  $this->session->userdata['enduserinfo']['firstname'];
		  }
		  else
		  {
			  echo ' ';
		  }?> 
    <!--(Max 30 Characters) -->
 
      </div>
      
      <div class="form-group">
          <label for="roleid" class="col-sm-4 control-label"> Middle Name</label>
          <div class="col-sm-5"> <?php if(isset($this->session->userdata['enduserinfo']['middlename']))
		  {
			  echo  $this->session->userdata['enduserinfo']['middlename'];
		  }
		  else
		  {
			  echo ' ';
		  }?> 
    	</div>
      </div>
            <div class="form-group">
          <label for="roleid" class="col-sm-4 control-label"> Last Name </label>
	        <div class="col-sm-1"> <?php if(isset($this->session->userdata['enduserinfo']['lastname']))
			  {
				  echo  $this->session->userdata['enduserinfo']['lastname'];
			  }
			  else
			  {
				  echo ' ';
			  }?> 
    		</div>
      </div>
    
      
        <div class="box-body">
            <div class="form-group">
	            <label for="roleid" class="col-sm-4 control-label">Exam Name *</label>
	            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['exam_name']?> 
	               
	              <span class="error">
	              <?php //echo form_error('email');?>
	              </span> 
	            </div>
            </div>

            <div class="form-group">
	            <label for="roleid" class="col-sm-4 control-label">Subject Name *</label>
	            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['subject_name']?> 
	               
	              <span class="error">
	              <?php //echo form_error('email');?>
	              </span> 
	            </div>
            </div>

            <div class="form-group">
	            <label for="roleid" class="col-sm-4 control-label">Exam Date *</label>
	            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['exam_date']?> 
	               
	              <span class="error">
	              <?php //echo form_error('email');?>
	              </span> 
	            </div>
            </div>
		  
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Email *</label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['email']?> 
               
              <span class="error">
              <?php //echo form_error('email');?>
              </span> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Mobile Number *</label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['mobile'];?> 
              
              <span class="error">
              <?php //echo form_error('mobile');?>
              </span> </div>
          </div>
        <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Center Name *</label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['selCenterName']?> 
               
              <span class="error">
              <?php //echo form_error('email');?>
              </span> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Center Code *</label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['selCenterCode']?> 
               
              <span class="error">
              <?php //echo form_error('email');?>
              </span> </div>
          </div>

          <!-- Benchmark Disability Code Start -->
			<!-- <div class="box-header with-border header_blue">
			  <h3 class="box-title">Disability</h3>
			</div> -->
			<div class="form-group">
			  <label for="roleid" class="col-sm-4 control-label">Person with Benchmark Disability</label>
			  <div class="col-sm-5">
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
				  <div class="col-sm-5">
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
				   <label for="roleid" class="col-sm-5 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scanned_vis_imp_cert'];?>" height="100" width="100" ></label>
				  </div>
				</div>
				<?php
				}
				?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-4 control-label">Orthopedically handicapped</label>
				  <div class="col-sm-5">
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
				   <label for="roleid" class="col-sm-5 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scanned_orth_han_cert'];?>" height="100" width="100" ></label>
					</div>
				</div>
				<?php 
			   		}
				?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-4 control-label">Cerebral palsy</label>
				  <div class="col-sm-5">
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
				  <div class="col-sm-5"> <label for="roleid" class="col-sm-5 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scanned_cer_palsy_cert'];?>" height="100" width="100" ></label>
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

		  <div class="box-header with-border header_blue">
			  <h3 class="box-title">Scribe Details</h3>
			</div>
		<!-- POOJA MANE : 28/07/2022 DESCRIPTION -->
		    <div class="form-group">
	          	<label for="description" class="col-sm-4 control-label">Request Type &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
	          		<div class="col-sm-5">
	          		<?php if($this->session->userdata['enduserinfo']['special_assistance'])
	          			{?>
		          	  		<p>Special Assistance&nbsp;</p>
					<?php } else{ ?>
						<p>&nbsp;</p>
					<?php } ?>
					<label for="special_assistance"> Special Assistance </label>
					<?php 
					if($this->session->userdata['enduserinfo']['extra_time']){?>
					  <p>Extra Time &nbsp;</p>
					<?php } else{?>
					<p>&nbsp;</p>
					<?php } ?>
					<label for="extra_time"> Extra Time </label>
					</div>  
	         </div>

          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Request description *</label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['description']?> 
               
              <span class="error">
              <?php //echo form_error('email');?>
              </span> </div>
          </div>	
        <!-- POOJA MANE : 28/07/2022 DESCRIPTION END -->

				
        </div>
        <div class="box-footer">
          <div class="col-sm-4 col-xs-offset-3">
            <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">
          </div>
        </div>
      </form>
    </div>
	<?php $this->load->view('scribe_form/inc_footerbar'); ?>
  </section>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 

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