<!DOCTYPE html>
<html>
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
<script>var site_url="<?php echo base_url();?>";</script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/AdminLTE.min.css">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/skins/_all-skins.min.css">
  
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!--<script src="<?php echo base_url()?>js/jquery.js"></script>-->
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
 <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/iCheck/all.css">
  <!-- <script src="<?php //echo base_url()?>js/validation.js"></script> -->
  <link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
  .navbar-custom-menu {
	  width:95%;
	  padding:5px 0;
	 }
	 ul.navlogo {
		 width:100%;
		}
	 ul.navlogo li:first-child {
		 float:left;
		 display:inline-block;
		 color:#2ea0e2;
		 text-transform:uppercase;
		 font-size:24px;
		 line-height:42px;
		}
	ul.navlogo li:last-child {
		 float:right;
		 display:inline-block;
		}
	.skin-blue .main-header .navbar .nav > li > a {
			color:#2ea0e2;
		}
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    
    <a href="javascript:void(0)" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IIBF</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IIBF</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only"><?php echo $this->session->userdata('name');?></span> 
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
     
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav navlogo">
        <li><img src="<?php echo base_url();?>assets/images/iibf_logo_black.png"></li>
          <li class="dropdown user user-menu">
          	<?php if( $this->session->userdata('dra_institute') ) { ?>
                <a href="<?php echo  base_url()?>iibfdra/InstituteLogin/logout" class="dropdown-toggle" >
                  <span class="hidden-xs">Logout</span>
                  <i class="fa fa-sign-out"></i>
                </a>
            <?php } ?>
          </li>
        </ul>
      </div>
      
    </nav>
  </header>

<?php $drainstdata = $this->session->userdata('dra_institute');
if ($drainstdata) {
?>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <p><span style="color: #b8c7ce;"><?php echo $drainstdata['institute_name']; ?></span></p>
        <p></p>
      </div>
    </section>
  </aside>
<?php } ?>

<style>
.control-label {
	font-weight: bold !important;
}
label {      
      border-color: #80808059;
}
.types {
    color: green;
    font-weight: 800;
}
.status_div{
 font-weight: 800 !important;
}

.status {
  color: #223fcc;
  font-weight: 800;
}
.myview .form-group{
	clear:both;
}


</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> Candidate Preview </h1>
   
  </section>
    <section class="content">
      <div class="row myview">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Added Candidate Preview</h3>
              <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/Candidate_list_missing_images" class="btn btn-warning"> Back </a> </div>
            </div>
            <div class="box-body" style="padding-left: 45px">
            
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">NAME :</label>
                      <div class="col-sm-5"> <?=$examRes["namesub"]." ".$examRes["firstname"].' '.$examRes["middlename"].' '.$examRes["lastname"];?></div>
                    </div>
                    
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-7 control-label">CANDIDATE ADDRESS FOR COMMUNICATION :</label>
                     
                    </div>

                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">ADDRESS LINE-1 :</label>
                      <div class="col-sm-5"><?=$examRes["address1"];?></div>
                    </div>

                    <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">ADDRESS LINE-2 :</label>
                      <div class="col-sm-5"><?=$examRes["address2"];?></div>
                    </div>

                    <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">ADDRESS LINE-3 :</label>
                      <div class="col-sm-5"><?=$examRes["address3"];?></div>
                    </div>

                    <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">ADDRESS LINE-4 :</label>
                      <div class="col-sm-5"><?=$examRes["address4"];?></div>
                    </div>
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">STATE :</label>
                      <div class="col-sm-5"><?=$examRes["state_name"];?></div>
                    </div>
                    
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">DISTRICT :</label>
                      <div class="col-sm-5"><?php echo  $examRes["district"];?></div>
                    </div>
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">CITY :</label>
                      <div class="col-sm-5"><?php echo $examRes["city"];?></div>
                    </div>
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">PINCODE :</label>
                      <div class="col-sm-5" ><?php echo  $examRes["pincode"];?></div>
                    </div>
                       
                      <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">DATE OF BIRTH :</label>
                      <div class="col-sm-5"><?php echo  $examRes["dateofbirth"];?></div>
                    </div>
                       
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">SEX(M/F) :</label>
                      <div class="col-sm-5"><?php echo  ucfirst($examRes["gender"]);?></div>
                    </div>
                    
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">PHONE NO. :</label>
                      <div class="col-sm-5"><?php echo  $examRes["stdcode"].'-'.$examRes["phone"];?></div>
                    </div>
                    
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">MOBILE NO. :</label>
                      <div class="col-sm-5"><?php echo  $examRes["mobile_no"];?></div>
                    </div>
                    
                    
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">EMAIL ID :</label>
                      <div class="col-sm-5"><?php echo  $examRes["email_id"];?></div>
                    </div>
                      <?php
                                    $institute_name = '';
                                    $drainstdata = $this->session->userdata('dra_institute');
                                    if( $drainstdata ) {
                                        $institute_name = $drainstdata['institute_name'];   
                                        $institute_code = $drainstdata['institute_code'];
                                    }
                                    ?>
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">NAME OF TRAINING INSTITUTE :</label>
                      <div class="col-sm-5"><?php echo  $institute_name;?></div>
                    </div>
                    
                    
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">CENTER NAME :</label>
                      <div class="col-sm-5"><?php echo  $examRes["city_name"];?></div>
                    </div>
                    
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">BATCH NAME :</label>
                      <div class="col-sm-5"><?php echo  $examRes["batch_name"];?></div>
                    </div>
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">TRAINING PERIOD :</label>
                      <div class="col-sm-5">From <?php echo  $examRes["batch_from_date"];?>  To <?php echo  $examRes["batch_to_date"];?></div>
                    </div>
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">EDUCATIONAL QUALIFICATION :</label>
                      <div class="col-sm-5">
                        <?php if($examRes["qualification"] == 'twelth'){ echo '12th';} elseif($examRes["qualification"] == 'tenth'){echo '10th';}elseif ($examRes["qualification"] == 'graduate') {
                         echo 'Graduation';
                        } ?>
                      </div>
                    </div>

                      
            </div>
          </div>

           <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Image Uploaded</h3>
                            </div>
                            <div class="box-body">       
                                 <div class="form-group idproof-wrap">
                                     <div class="col-sm-3">
                                         <?php if( !empty( $examRes["scannedphoto"] ) ) { ?>
                                            <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["scannedphoto"];?>" style="width: 100%;height: auto;"/>
                                         <?php } ?>
                                     </div> 
                                     <div class="col-sm-3">
                                        <?php if( !empty( $examRes["scannedsignaturephoto"] ) ) { ?>
                                            <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["scannedsignaturephoto"];?>" style="width: 100%;height: auto;" />
                                         <?php } ?>
                                     </div>
                                     <div class="col-sm-2">
                                        <?php if( !empty( $examRes["idproofphoto"] ) ) { ?>
                                            <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["idproofphoto"];?>" style="width: 100%;height: auto;" />
                                         <?php } ?>
                                     </div>
                                   <?php 
								   // commented by Manoj 
								   /*?>  <div class="col-sm-2">
                                        <?php if( !empty( $examRes["training_certificate"] ) ) { ?>
                                            <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["training_certificate"];?>" style="width: 100%;height: auto;" />
                                         <?php } ?>
                                     </div><?php */?>
                                     <div class="col-sm-2">
                                        <?php if( !empty( $examRes["quali_certificate"] ) ) { ?>
                                            <img src="<?php echo base_url().'uploads/iibfdra/'.$examRes["quali_certificate"];?>" style="width: 100%;height: auto;" />
                                         <?php } ?>
                                     </div>
                                 </div>
                          </div><!--.box-body-->
                      </div><!--.box-info-->
        </div>
      </div>
    </section>
</div>
<script type="text/javascript">
 $(document).ready(function() {
    
     $("body").on("contextmenu",function(e){
        return false;
    });
 });
</script>

<?php $this->load->view('iibfdra/front-footer'); ?> 