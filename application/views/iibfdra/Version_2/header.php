<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Welcome to Indian Institute of Banking &amp; Finance - DRA Admin</title>
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
    <link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/skins/_all-skins.min.css">
    <!-- jQuery 2.2.3 -->
    <script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
    
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
		<?php $drauserdata = $this->session->userdata('dra_admin');
		//print_r($drauserdata);
		?>
        <!-- Logo -->
        <a href="<?php echo base_url();?>" class="logo"></a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?php echo $drauserdata['name'];?></span> 
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
			<div class="navbar-custom-menu">
                <ul class="nav navbar-nav navlogo">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li><img src="<?php echo base_url();?>assets/images/iibf_logo_black.svg"></li> 
                    <li class="dropdown user user-menu">
                        <a href="<?php echo  base_url()?>iibfdra/Version_2/admin/login/Logout" class="dropdown-toggle" ><!--data-toggle="dropdown"-->
                            <span class="hidden-xs"><?php if($drauserdata['username']!=''){ echo $drauserdata['username']; } ?>    <?php if(isset($drauserdata['admin_user_type'])){ echo ' ( '.$drauserdata['admin_user_type'].' ) '; } ?> </span>
                            <i class="fa fa-sign-out"></i>
                        </a>
                    </li>
                </ul>
			</div>
		</nav>
	</header>