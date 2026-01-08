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

    <h1 class="register"> DB&F To JAIIB Conversion </h1>

    <div style="float:right;">
                  <a  href="javascript:window.history.go(-1);">Modify Details</a>
              </div>
  </section>

  
  <form class="form-horizontal" name="nonmemAddForm" id="nonmemAddForm" action="<?php echo base_url()?>Dbftojaiib/addmember/"  method="post"  enctype="multipart/form-data">

    <input  type="hidden" class="exam_form_field" name="regnumber" id="regnumber" value="<?php echo $regnumber;?>">


    <section class="content">

      <div class="row">

        <div class="col-md-12"> 

          
          <!-- Horizontal Form -->

          <div class="box box-info">

         
            <!-- form start -->

            

            <?php //echo validation_errors(); ?>

            <?php if($this->session->flashdata('error')!=''){?>

            <div class="alert alert-danger alert-dismissible" id="error_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 

              <?php echo $this->session->flashdata('error'); ?> </div>

            <?php } if($this->session->flashdata('success')!=''){ ?>

            <div class="alert alert-success alert-dismissible" id="success_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 

              <?php echo $this->session->flashdata('success'); ?> </div>

            <?php } 

			 if(validation_errors()!=''){?>

            <div class="alert alert-danger alert-dismissible" id="error_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 

              <?php echo validation_errors(); ?> </div>

            <?php } 

			 ?>
          <div class="box-header with-border">

          <h3 class="box-title">Basic Details:</h3>

          </div>
            <div class="box-body">

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">DB&F Registration Number <span style="color:#f00">*</span></label>

               

                <div class="col-sm-5">
                 
                 <?php echo $enduserinfo['dbf_regnumber'] ?>

                  <span class="error dbf_regnumber_error">


                  </span> </div>

              </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Full Name</label>

                <div class="col-sm-5">

                  <?php echo $enduserinfo['display_name'] ?>

                  <span class="error">

                  <?php //echo form_error('display_name');?>

                  </span> </div> </div>

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Date Of Birth</label>

                <div class="col-sm-5">

                <?php echo $enduserinfo['dateofbirth'] ?>

                  <span class="error">

                  <?php //echo form_error('lastname');?>

                  </span> 
                </div>
              </div>
              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Mobile Number</label>

                <div class="col-sm-5">

                <?php echo $enduserinfo['mobile'] ?>

                  <span class="error">

                  <?php //echo form_error('lastname');?>

                  </span> 
                </div>
              </div>
              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Email Id</label>

                <div class="col-sm-5">

                <?php echo $enduserinfo['email'] ?>

                  <span class="error">

                  <?php //echo form_error('lastname');?>

                  </span> 
                </div>
              </div>

            </div>

          </div>


          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">Certificate Details:</h3>

            </div>

            

            <div class="box-body">

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">DB&F Certificate Number</label>

                <div class="col-sm-5 "> 
                <?php echo $enduserinfo['certificate_number'] ?>

                  <div id="error_certificate_number"></div>


                  <span class="certificate_number" style="display:none;"></span> <span class="error">

                  <?php //echo form_error('idproofphoto');?>

                  </span> </div>

              </div>


              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">DB&F Certificate Date</label>

                <div class="col-sm-5">

                <?php echo $enduserinfo['certificate_date'] ?>

                </div>

              </div>

              

            </div>

          </div>

          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">Bank appointment/Joining Details:</h3>

            </div>

            

            <div class="box-body">

              


              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Bank appointment /Joining Letter</label>

                <div class="col-sm-5">

                <?php if(isset($this->session->userdata['enduserinfo']['offer_letter']))  {
                  ?>
                  <a href="<?php echo $this->session->userdata['enduserinfo']['offer_letter'] ?>" target="_blank"> View File</a>
                  <?php 
                }  ?>

                </div>

                </div>

                <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Date of Bank Appointment letter</label>

                <div class="col-sm-5">

                <?php echo $enduserinfo['bank_letter_date'] ?>

                </div>
                </div>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Date of Bank Joining</label>

                  <div class="col-sm-5">

                  <?php echo $enduserinfo['bank_joining_date'] ?>

                  </div>
                


              </div>



            </div>

          </div>


          
          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">Fee Details:</h3>

            </div>

            

            <div class="box-body">

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>

                <div class="col-sm-5 "> 
                    Rs. <?php 
                    if($enduserinfo['state']=='MAH')
                    echo $enduserinfo['fee']['cs_tot']; 
                    else echo $enduserinfo['fee']['igst_tot']; ?>
                    


                 </div>

              </div>



            </div>

          </div>



          <div class="box box-info">

         
            

            <div class="box-footer">

              <div class="col-sm-4 col-sm-offset-2"> <button type="submit" name="btnSubmit" class="btn btn-info" id="preview">Proceed for Payment</button> 


              </div>

            </div>

          </div>

        </div>

      </div>


    </section>

  </form>

</div>

<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">

<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 

<script src="<?php echo base_url();?>js/cscvalidation.js"></script> 

<script type="text/javascript">

  <!--var flag=$('#usersAddForm').parsley('validate');-->



</script> 

