<style>
.dra-regclose {
	color: #FF0000;
	font-size: 18px;
	text-align: center;
	padding:10px 0px;
}
</style>
<?php
$this->load->view('iibfdra/front-header'); ?>
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
              			<h3 class="box-title">DRA Exams</h3>
            		</div><!-- /.box-header -->
            		<!-- form start -->
            		<div class="box-body">
                    	<div class="row">
                            <div class="col-sm-12">
                            	<div class="row" style="margin:0;">
                                <?php
								$count = 0; $flg = 0; $exhtml = '';
                                if(count($exams) > 0) {
									//$comp_currdate = date('Y-m-d H:i:s');
									$comp_currdate = date('Y-m-d');
                                    foreach($exams as $exam) { 
                                        //$comp_frmdate = $exam['exam_from_date'].' '.$exam['exam_from_time'];
										//$comp_todate = $exam['exam_to_date'].' '.$exam['exam_to_time'];
										$comp_frmdate = $exam['exam_from_date'];
										$comp_todate = $exam['exam_to_date'];
                                        if( strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate) ) { //display dra exam link only when any one exam is active
											
												if( $count == 0 || $flg != 1 ) { ?>
													<h3 style="margin-top:0px;"><a href="<?php echo base_url().'iibfdra/InstituteLogin'?>"><span style="color:#F00">DRA Exam - <span style="font-size:18px;">Click here for registration</span> </span></a></h3>
												<?php } $flg = 1; 
										} //Display exam details irrespective of it is active or not
										//exam date added on 01-02-2017
										
										elseif($count == 0){ ?>
										<h3 style="margin-top:0px;"><a href="<?php echo base_url().'iibfdra/InstituteLogin'?>"><span style="color:#F00">DRA Exam - <span style="font-size:18px;">Click here for login</span> </span></a></h3>
										<?php }//Display exam details irrespective of it is active or not
										
										$exhtml .='<div class="col-sm-6">
											<p><b>'.$exam['description'].' SCHEDULE</b></p>
											<p>Date of Exam (Exam Code: '.$exam['exam_code'].'): '.date_format(date_create($exam['exam_date']),"d-m-Y").'</p>
											<p>Date of Commencement of Application: '.date_format(date_create($exam['exam_from_date']),"d-m-Y").'</p>
											<p>Date of Closure of Application : '.date_format(date_create($exam['exam_to_date']),"d-m-Y").' <span style="color:#F00"><!--(Extended from 3rd Feb 2017 till 10th feb 2017)--></span></p>
										</div>';
                                        $count++;
                                     }	
									 if( $flg == 0 ) {
										echo "<div class='col-sm-12 dra-regclose'>Registration closed.</div>";	 
									 }
									 echo $exhtml;
                                }?>
                                </div><!--.row-->
                                <p><b>Note: 1.</b> Provision of payment of application fee through Debit card/ Credit card / Internet Banking is available.</p>
                                <h4>IMPORTANT NOTICE:</h4>  
                                <p>Certificate Examination for DRA and DRA Tele-callers.</p>
                                <p>At present Institute is conducting the above examinations in offline mode (paper and pencil). It has been now decided to hold the above 2 examinations in <b>Online Mode</b> as under:</p>
                                <ol>                            
                                    <li>Certificate Examination for DRA Tele-callers - With effect from <b>June 2015</b> examinations (May 2015 examination will be held in paper and pencil mode)</li>
                                    <li>Certificate Examination for DRA - With effect from <b>August 2015</b> examinations (May, June & July 2015 examinations will be held in paper and pencil mode)</li>
                                </ol>
                                <p>The Training Institutes/Candidates are requested to note the above.</p>
                            </div><!--(Max 30 Characters) -->
                        </div>
					</div>
                </div> <!-- Basic Details box closed-->
        	</div>
		</div>
	</section>
</div>
<?php $this->load->view('iibfdra/front-footer');?>