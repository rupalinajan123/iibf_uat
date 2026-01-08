<!-- Content Wrapper. Contains page content -->
<div class="container">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Membership Details</h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
    <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data">
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-info">
              <div class="box-body">
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"></label>
                  <div class="col-sm-6"><br>
                    <div style="text-align:justify;width:600px;">
                      <p>Your application saved successfully.</p>
                      <p><strong>Your Membership No is</strong> <?php echo $application_number;?> <strong>and Your password is </strong><?php echo $password?></p>
                      <p>Please note down your Membership No and Password for further reference.</p>
                      <p>You may print or save membership registration page for further reference.</p>
                      <p>Please ensure proper Page Setup before printing.</p>
                      <p>You can save system generated application form as PDF for future reference</p>
                    </div><br>
                    <div style="text-align:left">
                      <a href="<?php echo base_url()?>register/pdf/">Save as pdf</a> &nbsp; &nbsp;
                      <?php /* <a href="javascript:void(0);" id="print_id" onClick="Register_profile_preview();">Continue</a> */ ?>
                      <a href="<?php echo base_url();?>" style="margin-left:15px;">Login</a>
                      &nbsp; &nbsp;
                      <a href="<?php echo base_url()?>uploads/Publications _List_IT.xlsx" class='publication' data-attr=<?php echo $application_number;?>  target="_blank">Publications</a>
                    </div>
                  </div>
                </div>
              </div>
            </div> <!-- Basic Details box closed-->
          </div>
        </div>
      </section>
    </form>
  </div>
</div>
<!-- Div to print  -->