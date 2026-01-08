<?php $this->load->view('nonmember/front-header-nm');?>
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Thank You for using IIBF Online Services. Your registration number and transaction details are forwarded to your registered mail id.
      </h1>
     
    </section>

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
            <?php //echo validation_errors(); ?>
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
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php } 
			 ?> 
            
             <div class="col-sm-9">
              
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Registration Number :</label>
                	<div class="col-sm-5">
                  <?php if(isset($result[0]['regnumber'])){echo $result[0]['regnumber'];}?>
                    </div>
                </div>
                
            
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount :</label>
                	<div class="col-sm-5">
                      Rs. <?php echo $payment_info[0]['amount'];?>
                    </div>
                </div>
                
            
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Number : </label>
                <div class="col-sm-5">
				<?php echo $payment_info[0]['transaction_no'];?>
             	</div>
                </div>
                
             
         
         <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Transaction Status </label>
                <div class="col-sm-5">
             <?php  if($payment_info[0]['status']==0)
			 {
					echo 'Fail';
			}
			else  if($payment_info[0]['status']==1)
			{
				echo 'Success';
			}
			else if($payment_info[0]['status']==2)
			{
				echo 'Pending';
			}?>
                </div>
            </div>
        </div>
       
        
          </div>
        </div> 
        </div>
      </div>
    </section>
    </form>
  </div>


<?php $this->load->view('nonmember/front-footer-nm');?>