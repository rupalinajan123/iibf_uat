<?php $this->load->view('nonmember/front-header-nm');?>
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Thank You for using IIBF Online Services. Your Registration application fail.
      </h1> 
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>

<form>
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info" style=" border: solid 1px #000;">
            <div class="box-header with-border">
              <h3 class="box-title">  <center><img src="<?php echo base_url()?>assets/images/logo1.png" width="1070"></center></h3>
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
                <label for="roleid" class="col-sm-3 control-label">Application Fail:</label>
                <div class="col-sm-9">
                <strong style="color:#000">Center capacity for <?php 
				if(isset($exam_name[0]['description']))
				{
					echo $exam_name[0]['description'];
				}?> has been full.</strong>
                </div>
                </div>
                
                
                <div class="form-group">
                    <div class="col-sm-10">
      
                          </div>
                           <label for="roleid" class="col-sm-3 control-label" >Transaction Number:</label>
            
           
             
				  <div class="col-sm-4">
                <?php   echo $payment_info[0]['transaction_no'];?>
                </div>
          
                </div>
                
                     
                
                      <div class="form-group">
                    <div class="col-sm-5">
           <p style="color:#FFF"> .</p>
                          </div>
                           <label for="roleid" class="col-sm-3 control-label" >Transaction Status:</label>
            
           
             
				  <div class="col-sm-4">
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
                
      
                
                <div class="form-group">
                        <div class="col-sm-5">
           <p style="color:#FFF"> .</p>
                          </div>
                <label for="roleid" class="col-sm-3 control-label" >Note:</label>
                <div class="col-sm-9">
                <div style="color:#F00">Refund for this application  will be initiated within 7 to 10 days!</div>
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