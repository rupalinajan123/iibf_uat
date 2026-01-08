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

    .main-footer { margin-left:0; }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<style type="text/css">
  tfoot {
    display: table-header-group;
  }

  .pp0,
  .pp1,
  .pp4,
  .pp5,
  .pp6,
  .pp7 {
    display: none;
  }

  #example1_processing {
    display: none !important;
  }

  #page_loader {
    background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0;
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 99999;
    display: none;
  }

  /* #page_loader .loading { margin: 0 auto; position: relative;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #064b86;border-bottom: 16px solid #064b86;width: 80px;height: 80px;-webkit-animation: spin 2s linear infinite;animation: spin 2s linear infinite;top: calc( 50% - 40px);} */
  #page_loader .loading {
    margin: 0 auto;
    position: relative;
    width: 80px;
    height: 80px;
    top: calc(50% - 40px);
    color: #fff;
    font-size: 30px;
  }

  @-webkit-keyframes spin {
    0% {
      -webkit-transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
    }
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>
<div id="page_loader">
  <div class="loading">Processing...</div>
</div>

<div class="content-wrapper" style="margin:0;">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Admin - Candidate list for updating images</h3>
          </div>

          <div class="box-body">
            <?php if ($this->session->flashdata('error') != '') { ?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
              </div>
            <?php }
            if ($this->session->flashdata('success') != '') { ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
              </div>
            <?php } ?>

            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="text-center no-sort" style="padding-right:8px;">S.No.</th>
                  <th class="text-center">Batch Code</th>
                  <th class="text-center">Batch Name</th>
                  <th class="text-center">Regnumber</th>
                  <th class="text-center">Candidate Name</th>
                  <th class="text-center">Address</th>
                  <th class="text-center">DOB</th>
                  <th class="text-center">Email</th>
                  <th class="text-center no-sort" style="padding-right:8px;">Action</th>
                </tr>
              </thead>

              <tbody>
                <?php if (count($candidate_data) > 0) {
                  $sr_no = 1;
                  foreach ($candidate_data as $res) { ?>
                    <tr>
                      <td class="text-center"><?php echo $sr_no; ?></td>
                      <td><?php echo $res['batch_code']; ?></td>
                      <td><?php echo $res['batch_name']; ?></td>
                      <td><?php echo $res['regnumber']; ?></td>
                      <td><?php echo $res['namesub'] . ' ' . $res['firstname'];
                          if ($res['middlename'] != "") {
                            echo ' ' . $res['middlename'];
                          }
                          if ($res['lastname'] != "") {
                            echo ' ' . $res['lastname'];
                          } ?></td>
                      <td><?php echo $res['address1'];
                          if ($res['address2'] != "") {
                            echo ', ' . $res['address2'];
                          }
                          if ($res['address3'] != "") {
                            echo ', ' . $res['address3'];
                          }
                          if ($res['address4'] != "") {
                            echo ', ' . $res['address4'];
                          } ?></td>
                      <td><?php echo date("d-M-Y", strtotime($res['dateofbirth'])); ?></td>
                      <td><?php echo $res['email_id']; ?></td>
                      <td class="text-center">
                        <a href="<?php echo base_url() . 'iibfdra/Candidate_list_missing_images_for_admin/editCandidate/' . $res['regid']; ?>" target="_blank">Edit </a> |
                        <a href="<?php echo base_url() . 'iibfdra/Candidate_list_missing_images_for_admin/viewApplicant/' . $res['regid']; ?>" target="_blank">View</a>
                      </td>
                    </tr>
                <?php $sr_no++;
                  }
                } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th class="text-center no-sort">S.No.</th>
                  <th class="text-center">Batch Code</th>
                  <th class="text-center">Batch Name</th>
                  <th class="text-center">Candidate Name</th>
                  <th class="text-center">Address</th>
                  <th class="text-center">DOB</th>
                  <th class="text-center">Email</th>
                  <th class="text-center no-sort">Action</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
  </form>
</div>

<!-- DataTables -->
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<!-- Data Tables -->

<script type="text/javascript">
  $(document).ready(function() {
    var userDataTable = $('#example1').DataTable({
      "serverSide": false,
      "lengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100]
      ],
      "language": {
        "lengthMenu": "_MENU_",
      },
      pageLength: 10,
      responsive: true,
      "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        },
        {
          "targets": [0],
          "className": "text-center"
        },
      ],
      "aaSorting": [],
      "stateSave": false,
    });
  });

  $(document).ajaxStart(function() {
    $("#page_loader").css("display", "block");
  });
  $(document).ajaxComplete(function() {
    $("#page_loader").css("display", "none");
  });
</script>

<?php $this->load->view('iibfdra/front-footer'); ?>