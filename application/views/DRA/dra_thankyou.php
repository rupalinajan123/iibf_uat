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
<div class="container">
<div class="content-wrapper" style="border:solid 1px #000; ">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding:0px;">
	  <h1 class="register">DRA Acknowledgment</h1>
      <!--<h1>
      Thank You for using IIBF Online Services. Your registration number and transaction details are forwarded to your registered mail id.
      </h1>-->
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>

<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="">
    <section class="content">
    <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <!--<div class="box-header with-border">
              <h3 class="box-title">  <img src="<?php echo base_url()?>assets/images/logo1.png"></h3>
              <br>
            </div>-->
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
               
            
               <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Registration Number :</label>
                	<div class="col-sm-5">
                  <?php if(isset($user_info[0]['member_no'])){echo @$user_info[0]['member_no'];}?>
                    </div>
                </div>-->
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Institute Name :</label>
                	<div class="col-sm-5">
                  <?php echo @$user_info[0]['inst_name'];?>
                    </div>
                </div>
                
                 <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Password : </label>
                	<div class="col-sm-5">
                 	<?php echo @$password;?>
                    </div>
                </div>-->
                
               
                

                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount :</label>
                	<div class="col-sm-5">
                      <?php echo @$payment_info[0]['amount'];?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Number : </label>
                <div class="col-sm-5">
				<?php echo @$payment_info[0]['transaction_no'];?>
             	</div>
                </div>
                
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Date :</label>
       		    <div class="col-sm-2">
					<?
                                 echo date('d-M-Y',strtotime($payment_info[0]['date']));
                       ?>
                    </div>
                </div>
             
         
         <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Transaction Status </label>
                <div class="col-sm-5">
             <?php  if(@$payment_info[0]['status']==0)
			 {
					echo 'Fail';
			}
			else  if(@$payment_info[0]['status']==1)
			{
				echo 'Success';
			}
			else if(@$payment_info[0]['status']==2)
			{
				echo 'Pending';
			}?>
                </div>
         </div>
       
       <?php 
	   if(@$payment_info[0]['status']==1)
	   {?>
       	 <div class="box-footer">
          <!--<div class="col-sm-2 col-xs-offset-5">
          <a href="<?php echo  base_url()?>Nonreg/logout/">Logout</a>
          </div>-->
           <div class="col-sm-2 col-xs-offset-5">
       	   <a href="<?php echo base_url() ?>DraRegister" >Home</a>
          </div>
           </div>
       <?php 
	   }?> 
		  </div>
        </div> 
        </div>
      </div>
    </section>
    </form>
  </div>
  </div>
