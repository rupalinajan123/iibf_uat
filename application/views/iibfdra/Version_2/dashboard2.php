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
                        <h3 class="box-title">DRA online Payment process Flow:</h3>
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

                       <!--  <div class="form-group">
                        <div class="col-sm-12">
                            <a href="<?php echo base_url('/iibfdra/Version_2/InstituteHome/dashboard'); ?>" style="float: right;" class="btn btn-primary" href="">Back</a>
                        </div> -->

                        <div class="form-group">

                            <div class="col-sm-12">
                                <a href="<?php echo base_url('/iibfdra/Version_2/InstituteHome/dashboard'); ?>" style="float: right;" class="btn btn-primary" href="">Back</a>
                                <p><b>1. Apply for DRA Exam:</b></p>  
                                <ul>
                                  <li>Select the “Apply for DRA Exam” menu and add the candidates.</li>
                                  <li>After adding the candidates, select the Edit operation button.</li>
                                  <li>Choose the appropriate centre and medium of exam for the candidates.</li>
                                  <li>Click on the Submit button to confirm the details.</li>
                                  <li>Select the candidates by ticking the checkbox for further processing.</li>
                                </ul>

                                <p><b>2. Freeze Candidate List for Payment:</b></p>  
                                <ul>
                                  <li>Click on the “Preview and Generate Proforma Invoice” button located at the top.</li>  
                                  <li>The preview proforma Invoice button allows to view and print the proforma invoice. </li>
                                  <li>Generate Proforma Invoice button allows to generate the Proforma Invoice.</li>
                                  <li>After clicking the Generate Proforma button, the selected candidate list is frozen and ready for payment processing.</li>
                                </ul>

                                <p><b>3. Make Payment:</b></p>  
                                <ul>
                                  <li>To proceed with the payment, select the “Proforma Invoice Payment” menu.</li>  
                                  <li>Click on the “Make Payment” button on the right side for the respective proforma.</li>
                                  <li>You will be redirected to the payment page to complete the payment process.</li>
                                </ul>	

                                <p><b>4. Pre-Payment Guidelines:</b></p>  
                                <ul>
                                  <li>Please ensure all details are thoroughly checked before making the payment for any proforma invoice.</li>  
                                  <li>Once the payment is made, the fees are non-refundable and not subject to any adjustments or cancellations.</li>
                                </ul>   

                                <p><b>5. View Transaction Details:</b></p>  
                                <ul>
                                  <li>After a successful payment the transaction details and receipt can be found in the “Transaction Detail” menu for your reference.</li>  
                                  <li>The Tax Invoice will be available in “Transaction Detail” within 7-10 working days in case of GST registered entity.</li>
                                </ul>   						
                            </div>
                        </div> 
                    </div>    
                    </div>
                     
                </div> <!-- Basic Details box closed-->
        	</div>
    	
	</section>
</div>