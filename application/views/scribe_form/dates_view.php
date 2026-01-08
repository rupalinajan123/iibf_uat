 

 <!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('scribe_form/inc_header'); ?>
    
    <style type="text/css">
    .container{
      padding-top: 5%;
    }
    #register{
      font-size: 25px;
    }
    </style>
    
  </head>
  
  <body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
      <header class="main-header"> 
        <nav class="navbar navbar-static-top">
          <div class="short_logo"> <img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"> </div>
          <div class="login-logo"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
          <small>(An ISO 21001:2018 Certified)</small></a></div>
        </nav>
      </header>
      
      <div class="container">       
        
          <section class="content-header">
            <!-- <h1>SORRY! Registration is closed</h1> -->
            <form role="form" method="POST" action="<?php echo base_url('scibe_form/index')?>" name="add_date" id="add_date" enctype="multipart/form-data">
               <!-- FORM VALIDATIONS -->
            
                            <div class="form-group">
                              <label class="required"  for="start_date">First Name</label> <span class="required" style="color:red;">*</span>
                              <input type="text" value="<?php echo set_value('start_date'); ?>" class="form-control nospace" id="start_date" name="start_date" placeholder="First Name" >
                              <?php 
                              if(form_error('start_date')){
                              echo "<span style='color:red'>".form_error('start_date')."</span>";
                              }
                              ?>
                            </div>

                            <div class="form-group">
                              <label for="end_date">Last Name</label> <span class="required" style="color:red;">*</span>
                              <input type="text" value="<?php echo set_value('end_date'); ?>" class="form-control nospace" id="end_date" name="end_date" placeholder="Last Name" >
                              <?php 
                              if(form_error('end_date')){
                              echo "<span style='color:red'>".form_error('end_date')."</span>";
                              }
                            ?>
                            </div>
                            <div class="form-group"><div id="availabe"></div></div>
          <!-- Modal footer -->
            
                    <div class="modal-footer">
                        <a href="<?php echo base_url(); ?>scibe_form/" type="button" class="btn btn-default" >Cancel</a>
                        <button type="submit" class="btn btn-primary" name="submit" id="add-user-btn">Add </button>
                    </div>
                </form>
          </section>
          
        <?php $this->load->view('scribe_form/inc_footerbar'); ?>
      </div>
    </div>    
    
    <?php $this->load->view('scribe_form/inc_footer'); ?>

    </body>
  </html>       
<!-- Content Wrapper. Contains page content -->
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        a { color:white; }
    </style>
</head>
<body>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  

  <!-- ALERT FLASH MESSAGE  -->
    <div class="col-md-12">
        <?php if ($this->session->flashdata('error_message') != "") { ?>
        <div class="alert alert-danger alert-dismissable p-1" >
        <i class="fa fa-ban"></i>
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b>Alert!</b> <?php echo $this->session->flashdata('error_message'); ?> </div>

        <?php } ?>
        <?php if ($this->session->flashdata('success_message') != "") { ?>
        <div class="alert alert-success alert-dismissable" >
        <i class="fa fa-check"></i>
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b>Success!</b> <?php echo $this->session->flashdata('success_message'); ?> </div>
        <?php } ?>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                      <form role="form" method="POST" action="<?php echo base_url('scibe_form/index')?>" name="add_date" id="add_date" enctype="multipart/form-data">
               <!-- FORM VALIDATIONS -->
            
                        <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group">
                              <label class="required"  for="start_date">First Name</label> <span class="required" style="color:red;">*</span>
                              <input type="text" value="<?php echo set_value('start_date'); ?>" class="form-control nospace" id="start_date" name="start_date" placeholder="First Name" >
                              <?php 
                              if(form_error('start_date')){
                              echo "<span style='color:red'>".form_error('start_date')."</span>";
                              }
                              ?>
                            </div>

                            <div class="form-group">
                              <label for="end_date">Last Name</label> <span class="required" style="color:red;">*</span>
                              <input type="text" value="<?php echo set_value('end_date'); ?>" class="form-control nospace" id="end_date" name="end_date" placeholder="Last Name" >
                              <?php 
                              if(form_error('end_date')){
                              echo "<span style='color:red'>".form_error('end_date')."</span>";
                              }
                            ?>
                            </div>
                            <div class="form-group"><div id="availabe"></div></div>
                
                        </div>
                        </div>
          <!-- Modal footer -->
            
                    <div class="modal-footer">
                        <a href="<?php echo base_url(); ?>scibe_form/" type="button" class="btn btn-default" >Cancel</a>
                        <button type="submit" class="btn btn-primary" name="submit" id="add-user-btn">Add </button>
                    </div>
                </form>
                        
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- Password Toggle -->
<script>
function myFunction() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

let table;
$(function () {
})

</script>
   
    <!-- /.content -->
</div>

<script src="<?php echo base_url() ?>assets/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>