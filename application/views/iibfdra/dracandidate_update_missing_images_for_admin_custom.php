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

<!-- custom style for datepicker dropdowns -->
<style>
  .example {
    width: 33%;
    min-width: 370px;
    /* padding: 15px;*/
    display: inline-block;
    box-sizing: border-box;
    /*text-align: center;*/
  }

  .example select {
    padding: 10px;
    background: #ffffff;
    border: 1px solid #CCCCCC;
    border-radius: 3px;
    margin: 0 3px;
  }

  .example select.invalid {
    color: #E9403C;
  }

  .mandatory-field {
    color: #F00;
  }
  .main-footer { margin-left:0; }
</style>

<div class="content-wrapper" style="margin:0;">
  <section class="content-header">
    <h1> Admin : DRA examination application edit image form</h1>
  </section>

  <form class="form-horizontal" name="draExamEditFrm" id="draExamEditFrm" method="post" enctype="multipart/form-data" onsubmit="return dravalidateForm()">
    <input type="hidden" name="edit_candidate_flag_custom" id="edit_candidate_flag_custom" value="1">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
              <div class="pull-right">
                <a href="<?php echo base_url('iibfdra/candidate_list_missing_images_for_admin'); ?>" class="btn btn-warning">Back</a>
              </div>
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
              <?php }

              if (validation_errors() != '') { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo validation_errors(); ?>
                </div>
              <?php } ?>

              <div class="form-group">
                <label class="col-sm-3 control-label">Name</label>
                <div class="col-sm-9">
                  <?php echo $examRes["namesub"] . " " . $examRes["firstname"];
                  if ($examRes["middlename"] != "") {
                    echo " ".$examRes["middlename"];
                  }
                  if ($examRes["lastname"] != "") {
                    echo " ".$examRes["lastname"];
                  } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Candidate Address for communication</label>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Address line1 </label>
                <div class="col-sm-5"><?php echo $examRes["address1"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Address line2 </label>
                <div class="col-sm-5"><?php echo $examRes["address2"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Address line3 </label>
                <div class="col-sm-5"><?php echo $examRes["address3"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Address line4 </label>
                <div class="col-sm-5"><?php echo $examRes["address4"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">State </label>
                <div class="col-sm-5"><?php echo $examRes["state"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">District </label>
                <div class="col-sm-5"><?php echo $examRes["district"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">City</label>
                <div class="col-sm-3"><?php echo $examRes['city']; ?></option>
                </div>

                <label class="col-sm-2 control-label">Pincode</label>
                <div class="col-sm-2"><?php echo $examRes["pincode"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Date of Birth <span class="mandatory-field">*</span></label>
                <div class="col-sm-2 example"><?php echo $examRes["dateofbirth"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Sex (M/F) <span class="mandatory-field">*</span></label>
                <div class="col-sm-2"><?php echo $examRes["gender"]; ?></div>
              </div>
            </div>
          </div>

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Phone </label>
                <div class="col-sm-2"><?php echo $examRes["stdcode"]; ?></div>
                <div class="col-sm-2">Phone No <?php echo $examRes["phone"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Candidate Mobile No. </label>
                <div class="col-sm-5"><?php echo $examRes["mobile"]; ?></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Email ID </label>
                <div class="col-sm-5"><?php echo $examRes["email"]; ?></div>
              </div>
            </div>
          </div>

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Photograph and Signature</h3>
            </div>
            <div class="box-body">

              <h4>Note</h4>
              <ol>
                <li>Allowed Proof of Identity image Size - 10 to 25 KB</li>
                <li>Allowed Degree Certificate image Size - 50 to 100 KB</li>
                <li>Allowed Photo Size - 10 to 20 KB</li>
                <li>Allowed Signature Size - 10 to 20 KB</li>
              </ol>

              <div class="form-group idproof-wrap">
                <label for="id_proof" class="col-sm-3 control-label">Select Id Proof <span class="mandatory-field">*</span></label>
                <div class="col-sm-5">
                  <?php if (count($idtype_master) > 0) {
                    $i = 1;
                    foreach ($idtype_master as $idrow) {
                      if ($examRes["idproof"] == $idrow['id']) {
                        echo $idrow['name'];
                      }
                      $i++;
                    }
                  } ?>
                </div>
              </div>
              

              <div class="form-group">
                <label for="id_proof" class="col-sm-3 control-label">Proof of Identity <span class="required-spn">*</span></label>
                <div class="col-sm-5" id="exist_draidproofphoto">
                  <?php if ($examRes["idproofphoto"] == '') { ?>
                    <input type="file" name="draidproofphoto" id="draidproofphoto" autocomplete="off" required>
                  <?php } ?>

                  <input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto" value="<?= $examRes["idproofphoto"] ?>">
                  <div id="error_dob"></div>
                  <br>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span>
                </div>
                <img id="idproof_preview" height="100" width="100" src="<?php echo base_url() . 'uploads/iibfdra/' . $examRes["idproofphoto"]; ?>" />
              </div>

              <div class="form-group">
                <label for="quali_certificate" class="col-sm-3 control-label">Qualification Certificate <span class="required-spn">*</span></label>
                <div class="col-sm-5" id="exist_qualicertificate">
                  <?php if ($examRes["quali_certificate"] == '') { ?>
                    <input type="file" name="qualicertificate" id="qualicertificate" autocomplete="off" required>
                  <?php } ?>
                  (As per educational qualification selected above)
                  <input type="hidden" id="hiddenqualicertificate" name="hiddenqualicertificate" value="<?= $examRes["quali_certificate"] ?>">
                  <div id="error_qualicert"></div>
                  <br>
                  <div id="error_qualicert_size"></div>
                  <span class="qualicert_text" style="display:none;"></span>
                </div>
                <img src="<?php echo base_url() . 'uploads/iibfdra/' . $examRes["quali_certificate"]; ?>" id="qualicertificate_preview" height="100" width="100" />
              </div>

              <div class="form-group">
                <label for="photograph" class="col-sm-3 control-label">Photograph of the Candidate <span class="required-spn">*</span></label>
                <div class="col-sm-5" id="exist_drascannedphoto">
                  <?php if ($examRes["scannedphoto"] == '') { ?>
                    <input type="file" name="drascannedphoto" id="drascannedphoto" autocomplete="off" required>
                  <?php } ?>

                  <input type="hidden" id="hiddenphoto" name="hiddenphoto" value="<?= $examRes["scannedphoto"] ?>">
                  <div id="error_photo"></div>
                  <br>
                  <div id="error_photo_size"></div>
                  <span class="photo_text" style="display:none;"></span>
                </div>
                <img src="<?php echo base_url() . 'uploads/iibfdra/' . $examRes["scannedphoto"]; ?>" id="scanphoto_preview" height="100" width="100" />
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Signature of the Candidate <span class="required-spn">*</span></label>
                <div class="col-sm-5" id="exist_drascannedsignature">
                  <?php if ($examRes["scannedsignaturephoto"] == '') { ?>
                    <input type="file" name="drascannedsignature" id="drascannedsignature" autocomplete="off" required>
                  <?php } ?>

                  <input type="hidden" id="hiddenscansignature" name="hiddenscansignature" value="<?= $examRes["scannedsignaturephoto"] ?>">
                  <div id="error_signature"></div>
                  <br>
                  <div id="error_signature_size"></div>
                  <span class="signature_text" style="display:none;"></span>
                </div>
                <img src="<?php echo base_url() . 'uploads/iibfdra/' . $examRes["scannedsignaturephoto"]; ?>" id="signature_preview" height="100" width="100" />
              </div>
            </div>
          </div>

          <div class="box-footer">
            <div class="col-sm-4 col-xs-offset-3">
              <?php if ($examRes["idproofphoto"] == '' || $examRes["quali_certificate"] == '' || $examRes["scannedphoto"]  == '' || $examRes["scannedsignaturephoto"] == '') { ?>
                <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">
              <?php } ?>
              <a href="<?php echo base_url('iibfdra/candidate_list_missing_images_for_admin'); ?>" class="btn btn-warning">Back</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>

<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url() ?>js/validation_dra_batch.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#draExamEditFrm').parsley('validate');
  });
</script>

<?php $this->load->view('iibfdra/front-footer'); ?> 