<?php $this->load->view('nonmember/front-header-nm');?>
   <div class="content-wrapper">


<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info" style=" border: solid 1px #000;">
            <div class="box-header with-border">
              <h3 class="box-title">  <img src="<?php echo base_url()?>assets/images/logo1.png"></h3>
              <br>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <h3>
              Thank You for using IIBF Online Services. Your transaction is under process. Once the transaction is successful, details will be sent to your registered email address. If you do not receive the details in 2 hrs please re-apply.
              </h3>
          </div>
        </div> 
        </div>
      </div>
    </section>
    </form>
  </div>


<?php $this->load->view('nonmember/front-footer-nm');?>