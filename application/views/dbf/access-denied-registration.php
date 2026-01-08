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
.content-wrapper {
    border-bottom: 1px solid #1287c0;
    border-left: 1px solid #1287c0;
    border-right: 1px solid #1287c0;
    width: 60%;
	margin:0 auto 10px !important;
	padding:0 10px;
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
.content-header {
	padding:0;
	margin-bottom:10px;
}
.nobg {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
  border: medium none !important;
}
.email {
	line-height:18px !important;
}
.box-body {
  padding: 0;
}
.example {
	text-align:left !important;
}
.example select {
	padding:5px 10px !important;
	border:1px solid #888 !important;
	border-radius:0 !important;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="register">
      Examination Application(Registration) for DB & F category candidates 
      (Please read "Instructions to Applicants" before filling up the form) </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
    
      <!-- Modal -->
 	 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      
      
    </div>
  </div>
	<form class="form-horizontal" name="nonmemAddForm" id="nonmemAddForm"  method="post"  enctype="multipart/form-data">
   
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          Pl note that if you have  already registered for any examination under Non-member Category in the past,  the same Registration Number allotted to you can be used for registering for other examinations(other than DB&F Exam) applicable for Non-members as per the eligibility criteria given.  Already Registered candidates has to apply for examinations by login using their USER ID and PASSWORD already provided -  <a href=<?php echo base_url();?>nonmem><span style="color:#090">Click here for Login</span></a><br/>
        <span style="color:#F00">Enter your details carefully, correction may not be possible later.</span>
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Access Denied</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
            <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible" id="error_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible" id="error_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php } 
			 ?> 
             <div class="box-body">
               
                
                
                
                
                <div class="form-group">
                	<div class="col-sm-12">
                      <?php echo $check_eligibility;?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div>
                </div>
                
                
                
                
                </div>
                
               </div> <!-- Basic Details box closed-->
                 
        
    
        </div>
      </div>
     <div class="modal fade" id="confirm"  role="dialog" >
  
</div>
</section>
</form>
  </div>
  

<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">

<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url();?>js/validation.js"></script>
  





 


 
