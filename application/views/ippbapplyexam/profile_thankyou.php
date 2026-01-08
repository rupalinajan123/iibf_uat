<?php $this->load->view('nonmember/front-header-nm');?>
<style media="print"> 
@media print {
    @page {
      size: auto !important;   /* auto is the initial value */
      margin: 0 !important;  /* this affects the margin in the printer settings */
    }
    a[href]:after {
        content: none !important;
    } 
  }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Thank You for using IIBF Online Services. Your registration number and transaction details are forwarded to your registered mail id.
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
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
            
             <div class="col-sm-9" id="printAcknowId" >
              
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">registration Number :</label>
                	<div class="col-sm-5">
                  <?php if(isset($result[0]['regnumber'])){echo $result[0]['regnumber'];}?>
                    </div>
                </div>
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Password : </label>
                	<div class="col-sm-5">
                 	<?php echo $password;?>
                    </div>
                </div>
                
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name :</label>
                	<div class="col-sm-5">
                  <?php 
				 			 echo $exam_info[0]['description'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount :</label>
                	<div class="col-sm-5">
                      <?php echo $payment_info[0]['amount'];?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode :</label>
                	<div class="col-sm-2">
                    <?php 
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					echo $mode?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium :</label>
       		    <div class="col-sm-2">
					<?php echo $medium[0]['medium_description'];?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center :</label>
                	<div class="col-sm-4">
                 		<?php echo $exam_info[0]['center_name'];?>
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

            <!-- <div style="text-align:left">
                  <note><b>Note:</b><br></note>
                  <note><b>Admit letter shall be available on IIBF website one week before the examination date.</b></note>
                  <br>
                  <note><b>Certificate shall be issued to the candidates who clear the examination and their KYC verification is successfully done.</b></note>
                </div>
                <br><br> -->

        </div>
        <?php 
		if($payment_info[0]['status']==1)
			{?>
			<div class="box-footer ">
          <!-- <div class="col-sm-2 col-xs-offset-5">
          <a href="<?php echo  base_url()?>CSCNonreg//logout/">Logout</a>
          </div> -->
           <div class="col-sm-12">
       	   <a class="noprintelm"  href="<?php echo  base_url()?>admitcard/getadmitdashboard">Download Admitcard</a>
          &nbsp;&nbsp;
       	   <a class="noprintelm" href="<?php echo  base_url()?>NonMember/Dashboard/">Home</a>
           &nbsp;&nbsp;<a class="noprintelm" href="javascript:void(0);" onClick="printAcknowDiv('printAcknowId')">Save Acknowledgement</a>
          </div>
           </div>
			<?php 
			}
		?>
        
          </div>
        </div> 
        </div>
      </div>
    </section>
    </form>
  </div>


 <script>
    $(".noprintelm").show();
    function printAcknowDiv(divName){
      $(".noprintelm").hide();
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;

      document.body.innerHTML = printContents;

      window.print();

      document.body.innerHTML = originalContents;
      $(".noprintelm").show();
    }
  </script>

<?php $this->load->view('nonmember/front-footer-nm');?>