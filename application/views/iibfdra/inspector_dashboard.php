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
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dashboard</h3>
                    </div>
                    <!-- /.box-header -->
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
                                <p><b>IMPORTANT</b></p>      
                               <!-- <p>Payment of Examination Fees should be made through Debit card / Credit card / Internet Banking. Payment also can be made through NEFT / RTGS if number of Examination applications are 20 or more.</p>-->
                               <ol>
  <li><span lang="EN-IN" xml:lang="EN-IN">Exam Applications should be entered first and then payment to be made/deposited via NEFT/RTGS.</span></li>
  <li><span lang="EN-IN" xml:lang="EN-IN">After making/depositing the payment inform IIBF immediately.</span></li>
    <li><span lang="EN-IN" xml:lang="EN-IN">Provide following Information to IIBF for further processing.(vahitha@iibf.org.in, sonali@iibf.org.in & govind@iibf.org.in)</span></li>

</ol>
<p><span lang="EN-IN" xml:lang="EN-IN">a) Payment Date </span></p>
<p><span lang="EN-IN" xml:lang="EN-IN">b) Payment Amount </span></p>
<p><span lang="EN-IN" xml:lang="EN-IN">c) UTR No.</span></p>
<p><span lang="EN-IN" xml:lang="EN-IN">d) Total Number of applications </span></p>
<p><span lang="EN-IN" xml:lang="EN-IN">e) TDS </span></p>
<p><span lang="EN-IN" xml:lang="EN-IN">f) GST</span></p>                                
                                <!--<center><b>IMPORTANT NOTICE:</b></center> 
                                
                                <p>Certificate Examination for DRA and DRA Tele-callers.</p>
                                
                                <p>At present Institute is conducting the above examinations in offline mode (paper and pencil). It has been now decided to hold the above 2 examinations in <b>Online Mode</b> as under:</p>
                                <ol>
                                    <li>Certificate Examination for DRA Tele-callers - With effect from <b>June 2015</b> examinations (May 2015 examination will be held in paper and pencil mode)</li>
                                    <li>Certificate Examination for DRA - With effect from <b>August 2015</b> examinations (May, June & July 2015 examinations will be held in paper and pencil mode)</li>
                                </ol>
                                <p>The Training Institutes/Candidates are requested to note the above.</p>-->
                            </div><!--(Max 30 Characters) -->
                        </div>
                    </div>
                </div> <!-- Basic Details box closed-->
        	</div>
    	</div>
	</section>
</div> 