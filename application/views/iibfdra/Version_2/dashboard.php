<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1></h1>
    </section>
    <section class="content">
	    <div class="row">
    	    <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info"  style="margin-bottom: 0px;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dashboard Overview: Important Guidelines and Policies for Exam Application and Payment Process.</h3>
                    </div>
                    <!-- form start -->
                    <div class="box-body">
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
                            <div class="col-sm-12">
                                <p><b>1. Exam Application Submission:</b></p>  
                                <ul>
                                  <li>The exam applications must be entered first. After entering the application forms, the payment should be made online.</li>
                                  <li>Upto 525 candidates can be selected while generating the proforma invoice and making payment.</li>
                                </ul>

                                <p><b>2. Queries Related to Payment:</b></p>  
                                
                                <ul>
                                  <li>For any inquiries regarding payments or other concerns, please direct your mails to the following email addresses:</li>
                                  <ol>
                                     <p> 1. je.exm1@iibf.org.in</p>
                                     <p> 2. je.exm2@iibf.org.in</p>
                                     <p> 3. dracell@iibf.org.in</p> 
                                  </ol>  
                                </ul>							
                            </div>
                        </div>
                    </div>
                </div> <!-- Basic Details box closed-->
        	</div>
    	</div>
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info" >
                    <div class="box-header with-border">
                        <h3 class="box-title">Kindly Note the Following:</h3>
                    </div>
                    <!-- form start -->
                    <div class="box-body"> 
                        <div class="form-group">
                            <div class="col-sm-12">
                                <p><b>1. No Refund/Adjust Policy:</b></p>  
                                <ul>
                                  <li>It is important to note that once payment has been made, the fees are neither refundable nor subject to adjustment under any circumstances.</li>
                                </ul>

                                <p><b>2. Right to Accept or Reject Enrollment:</b></p>  
                                
                                <ul>
                                  <li>The Indian Institute of Banking and Finance (IIBF) reserves the right to accept or reject your enrollment for the DRA application, without providing any reason.</li>
                                </ul>

                                <p><b>3. Accuracy of Information:</b></p>  
                                
                                <ul>
                                  <li>Your application will be processed based on the information provided by you. In case any details are found to be incorrect or false, your enrollment for the DRA examination may be cancelled.</li>
                                </ul> 
                                <br>
                                <a href="<?php echo base_url('/iibfdra/Version_2/InstituteHome/dashboard2'); ?>"> <p>Please click here to know how to make the payment</p> </a>                            
                            </div>
                        </div>
                    </div>
                </div> <!-- Basic Details box closed-->
            </div>
        </div>
	</section>
</div>