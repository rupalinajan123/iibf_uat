<!DOCTYPE html>
<html>
	<head>
	<?php $this->load->view('google_analytics_script_common'); ?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>IIBF - FEDAI Non Member Data Collection</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		
    <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/dist/css/skins/_all-skins.min.css">		
    
    <script type="text/javascript" charset="utf8" src="<?php echo  base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<style>			
			.login-box-body a {
			line-height: 20px;
			}
			.short_logo {
			display: inline-block;
			float: left;
			margin: 0 0 0 20px;
			}
			.login-logo a {
			color: #619fda;
			font-weight: 600;
			text-align: center;
			font-size: 28px;
			line-height: 24px;
			display: inline-block;
			}
			.login-logo a small {
			font-size: 14px;
			color: #1d1d1d;
			}	
			.red {
			color: #f00;
			}
      
      h4.login_heading 
			{
				text-align: center;
				margin: 0 0 20px 0;
				font-weight: 600;
				border-bottom: 1px solid #1287c0;
				padding-bottom: 10px;
				color: #1287c0;
				font-size: 18px;
			}
			
			.login-box 
			{
				width: 100%;
				max-width: 1200px;
			}
			
			.login-logo, .register-logo
			{
				border-bottom: 1px solid #1287c0;
				padding-bottom: 7px;
			}

      .login-box-body { width: 100%; position: unset; padding: 0px 40px 10px 40px; }
      #non_member_data_table_wrapper .table, #non_member_data_table_wrapper th, #non_member_data_table_wrapper td { border:1px solid #ccc; border-collapse: collapse; }
      .table, #non_member_data_table_wrapper th { background-color: #eee; }
		</style>
	</head>
	
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
				<div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>
			</div>
			
			<div class="login-box-body">
				<h4 class="login_heading">FEDAI Non Member Data Collection Listing </h4>

        <table id="non_member_data_table" class="table table-bordered table-striped no-footer">
          <thead>
            <tr>
              <th class='text-center' style="width:100px;">Sr No</th>
              <th class='text-center'>Member Number</th>
              <th class='text-center'>Bank Name</th>
              <th class='text-center' style="width:180px;">Employee ID Card Image</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($nonmember_data as $value)
              { ?>
              <tr>
                <td class='text-center'><?php echo $i; ?></td>
                <td><?php echo $value['member_no']; ?></td>
                <td><?php echo $value['bank_name']; ?></td>
                <td class='text-center'><a class="btn btn-primary" href="<?php echo base_url('uploads/empidproof') . '/' . $value['empidproofphoto']; ?>" target="blank" style="padding:1px 10px 2px 10px;">View Employee ID Card</a></td>
              </tr>
            <?php $i++; } ?>
          </tbody>
        </table>
			</div>
		</div>
		
    <link href="<?php echo  base_url()?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?php echo  base_url()?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
    <link href="<?php echo  base_url()?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

    <!-- Data Tables -->
    <script src="<?php echo  base_url()?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo  base_url()?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
    <script src="<?php echo  base_url()?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
    <script src="<?php echo  base_url()?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

    <script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo  base_url()?>assets/admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo  base_url()?>assets/admin/plugins/fastclick/fastclick.js"></script>
    <script src="<?php echo  base_url()?>assets/admin/dist/js/app.min.js"></script>

    <script> $(document).ready(function() { $('#non_member_data_table').DataTable(); });</script>
	</body>
</html>