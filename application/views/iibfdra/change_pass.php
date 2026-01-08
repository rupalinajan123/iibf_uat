  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

      DRA Institute Change Password Page

      </h1>

    </section>

	<form class="form-horizontal" name="instchangepass" id="instchangepass"  method="post" action="">

    <section class="content">

      <div class="row">

       

        <div class="col-md-12">

          <!-- Horizontal Form -->

           <!-- Basic Details box closed-->

 		<div class="box box-info">

       	 <div class="box-header with-border">

            <div style="float:right;">

            Note : Minimum is 6 and Maximum is 16 Characters

            </div>

            </div>

          <div class="box-body">

            

            <?php //echo validation_errors(); ?>

              <?php if($this->session->flashdata('error')!=''){?>

                <div class="alert alert-danger alert-dismissible">

                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    <?php echo $this->session->flashdata('error'); ?>

                </div>

              <?php } if($this->session->flashdata('success')!=''){ ?>

                <div class="alert alert-success alert-dismissible">

                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                <?php echo $this->session->flashdata('success'); ?>

              </div>

             <?php } 

			 if(validation_errors()!=''){?>

                <div class="alert alert-danger alert-dismissible">

                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    <?php echo validation_errors(); ?>

                </div>

              <?php } ?> 

              <div class="form-group">

                <label for="current_password" class="col-sm-3 control-label">Current Password</label>

                	<div class="col-sm-5">

                        <input  type="password" class="form-control" name="current_pass" id="current_pass"  autocomplete="off" data-parsley-minlength="4" data-parsley-maxlength="16" required>

                    	<div id="error_password"></div>

                    </div>

                </div>

                

                

                <div class="form-group">

                	<label for="newpassword" class="col-sm-3 control-label"> New Password </label>

                	<div class="col-sm-5">

                        <input  type="password" class="form-control" name="txtnpwd" id="txtnpwd"  autocomplete="off" required  data-parsley-minlength="6" data-parsley-maxlength="16">

                     	<div id="error_txtnpwd"></div>

                    </div>

                </div>

                

                <div class="form-group">

                	<label for="retype_newpassword" class="col-sm-3 control-label">Re-type New Password </label>

                	<div class="col-sm-5">

                        <input  type="password" class="form-control" name="txtrpwd" id="txtrpwd" required data-equalto="#txtnpwd" data-parsley-minlength="6" data-parsley-maxlength="16">

                        <div id="error_txtrpwd"></div>

                    </div>

                </div>

            </div>

            <div class="box-footer">

            	<div class="col-sm-4 col-xs-offset-3">

                	<input type="submit" class="btn btn-info" name="btn_password" id="btn_password" value="Submit">

                </div>

            </div>

         </div>

      </div>

   </div>

</section>

</form>

</div>

<script type="text/javascript">

	$('#instchangepass').parsley('validate');

</script>

  



