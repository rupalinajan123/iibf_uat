<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo  base_url()?>assets/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo  base_url()?>assets/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/AdminLTE.min.css">
  <link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/skins/_all-skins.min.css">
  
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script>
$(document).ready(function(){
	$(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf").hide();
});

</script>
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

<style>
.loading
{
    position: fixed;
    left: 50%;
    top: 35%;
    display: none;
   /* background: transparent url("../images/loading-big.gif");*/
    z-index: 1000;
    height: 31px;
    width: 31px;
}
</style>


</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url();?>admin/MainController" class="logo">
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
          <!-- User Account: style can be found in dropdown.less -->
           <li><img src="<?php echo base_url();?>assets/images/iibf_logo_black.png"></li>
          <li class="dropdown user user-menu">
            <a href="<?php echo  base_url()?>admin/login/Logout" class="dropdown-toggle" ><!--data-toggle="dropdown"-->
              <span class="hidden-xs"><?php if($this->session->userdata('username')!=''){ echo $this->session->userdata('username'); } ?></span>
              <i class="fa fa-sign-out"></i>
            </a>
            <!--<ul class="dropdown-menu">
               <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo  base_url()?>admin/login/Logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>-->
          </li>
          
        </ul>
      </div>
    </nav>
    
    <!--<div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif" width="120"></div>-->
    
  </header>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Duplicate Certificate
      </h1>
      <?php echo @$breadcrumb; ?>
    </section>
    <br />
	<div class="col-md-12">
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
    <?php } ?>
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
          <form class="form-horizontal" name="searchDate" id="searchDate" action="<?php echo base_url();?>admin/Dupcert_stats" method="post">
            <div class="box-header">
              <h3 class="box-title"></h3>
              <div class="pull-left">
                <div class="form-group">
                  
                    <label for="from_date" class="col-sm-2">From Date</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>" readonly>
                             <span class="error"><?php echo form_error('from_date');?></span>
                        </div>
                    
                  	<label for="to_date" class="col-sm-2">To Date</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" required value="<?php echo set_value('to_date');?>" readonly >
                             <span class="error"><?php echo form_error('to_date');?></span>
                        </div>
                    <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="return searchOnDate();">  
                    
                </div>
              </div>
             
              <div class="pull-right">
              	<!--<a href="<?php //echo base_url();?>admin/Report/download_success_bd" class="btn btn-warning" >Download</a>-->
                <!--<input type="submit" class="btn btn-warning" name="download" value="Download">-->
                <!--<a href="javascript:void(0);" class="btn btn-info" onclick="refreshDiv('');" value="Refresh">Refresh</a>-->
                <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
              </div>
            </div>
            </form>
            <!-- /.box-header -->
            <div class="box-body">
			 <?php if(!empty($data)){?>		
			  <table id="getdata" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <!--<th id="select">
                  	<input type="checkbox" name="check_list_all[]" id="select_all" value="1" class="chk" >
                  </th>
                 -->
                  <th id="srNo">Count</th>
                 
                </tr>
                </thead>
				
                <tbody class="no-bd-y" id="list"> 
				         <?php if($data == 'No data'){?>
                        <td><?php echo 'No Record Found'; ?></td> 
                        <?php } else { ?>
                        <td><?php echo $data; ?></td>        
		                <?php } ?>
                </tbody>
		
              </table>
			  <?php } ?>
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      
    </section>
   
  </div>
  
<!-- Data Tables -->

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(document).ready(function() 
{
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});
	
});


function searchOnDate()
{
	var fromDate = $("#from_date").val();
	var toDate = $("#to_date").val();
	if(fromDate=='' && toDate=='')
	{
		//alert('Please select atleast one date');
		alert('Please select dates');
		return false;
	}
	else if(fromDate=='' && toDate!='')
	{
		alert('Please select From Date');
		return false;
	}
	/*else
	{
		var perPage = $('#perPage').val();
		var searcharr = [];
		searcharr['field'] = 'date-BETWEEN';
		//'exam_code,description,qualifying_exam1,qualifying_part1,qualifying_exam2,qualifying_part2,qualifying_exam3,qualifying_part2,exam_type';
		searcharr['value'] = fromDate+'~'+toDate;
		paginate('',searcharr,perPage);
	}*/
}
/*$(function () {
	$("#getdata").DataTable();
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'admin/Dupcert_stats/getDcCounts';
	
	// Pagination function call
	//paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);
	
	$(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf ").hide();
	$("#listitems_filter").hide();
});
		*/
</script>
<?php $this->load->view('admin/includes/footer');?>