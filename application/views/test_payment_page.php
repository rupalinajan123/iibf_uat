
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/admin/bootstrap/css/bootstrap.min.css">
  <script src="<?php echo base_url(); ?>/assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
  <script src="<?php echo base_url(); ?>/assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
</head>
<body class="hold-transition skin-blue layout-top-nav">
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="" style="max-width: 500px;background: #eee;padding: 20px;margin: 20px auto;border: 1px solid #000;" autocomplete="off">

    <?php if ($this->session->flashdata('success') != '') { ?>
      <div class="alert alert-success alert-dismissible" id="success_id">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php }
    
    if ($this->session->flashdata('error') != '') { ?>
      <div class="alert alert-danger alert-dismissible" id="error_id">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php } ?>

    <section class="content">
      <div class="row">       
        <div class="col-md-12">
          <h4 style="font-size: 16px;text-align: center;margin: 0 0 15px 0;border-bottom: 1px solid #000;padding: 0 0 10px 0;">Test Billdesk Page - Bulk Payment</h4>
          
          <input class="form-control" type="text" name="fullname" id="fullname" value="" placeholder="Full Name" required><br>
          <input class="form-control" type="text" name="amount" id="amount" value="1" required readonly><br>
                  
          <div class="box-footer">
            <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment">
          </div>
        </div>
      </div>
    </section>
  </form>
</body>