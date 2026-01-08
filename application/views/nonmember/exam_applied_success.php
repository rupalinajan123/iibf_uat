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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Nonmember/Msuccess/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('nmregid');?>"> 
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
          <ul>
           <li>On successful completion of transaction confirmation SMS/email will be sent to the candidate intimating the receipt of examination forms.
					</li><li>In case the candidate does not receive confirmation SMS/email from the institute, the candidate should apply again till he receives the confirmation SMS/email.
					</li><li>Please note that Institute will not accept any responsibility in case of failed transactions. However fees debited if any to candidate's account will be refunded within seven working days of the transaction. Candidates need to reapply in such case. 
				</li>
        
        <?php if(in_array($applied_exam_info[0]['exam_code'],$this->config->item('skippedAdmitCardForExams'))) { //priyanka d >>27-dec-24 >> by default selecting venue for jaiib/caiiib as we don't have to create admitcard from filled form now >> exam_cd
                       ?> 

        <li><b>Please note that the admit letters will be available for download on the Institute’s website as well as in the candidate login 10 days before the exam.</b></li>
      <?php } ?>
              </ul>
                </div>
                
               </div>
          <div id="printAcknowId" class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Your transaction details are forwarded to your registered e-mail id.</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">

              <?php if($applied_exam_info[0]['exam_code'] == 101 || $applied_exam_info[0]['exam_code'] == 1046 || $applied_exam_info[0]['exam_code'] == 1047 && isset($applied_candidate) && $applied_candidate[0]['firstname'] != ""){  

                ?>
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name</label>
                  <div class="col-sm-3">
                  <?php if(isset($applied_candidate[0]['firstname']) && $applied_candidate[0]['firstname'] != ""){echo $applied_candidate[0]['firstname']." ".$applied_candidate[0]['lastname'];}?>
                    </div>
                </div>
              <?php } ?>
            
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	<?php echo $applied_exam_info[0]['regnumber'];?>
                    </div>
                </div>
                
                <?php if($applied_exam_info[0]['exam_code'] == 101 || $applied_exam_info[0]['exam_code'] == 1046 || $applied_exam_info[0]['exam_code'] == 1047 && isset($applied_candidate) && $applied_candidate[0]['firstname'] != ""){ 
                  //$exam_date = '';
                //echo $applied_exam_info[0]['exam_period'];
                $subject_details = $this->master_model->getRecords('subject_master',array('exam_code'=>$applied_exam_info[0]['exam_code'],'exam_period'=>$applied_exam_info[0]['exam_period']),'exam_date',array('id'=>'desc')); 


                ?>
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Examination</label>
                  <div class="col-sm-3">
                  <?php 
                  if(isset($subject_details) && count($subject_details) > 0 && $subject_details[0]['exam_date'] != "")
                  {
                      echo "<b>".date("d-m-Y",strtotime($subject_details[0]['exam_date']))."</b>";
                  }
                  ?>
                    </div>
                </div>
              <?php } ?>
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                     <div class="col-sm-3">
					<?php 
          $cust_exam_name = '';
          /*if($applied_exam_info[0]['exam_code'] == "1046"){
            $cust_exam_name = '(BCBF Advanced)';
          }else if($applied_exam_info[0]['exam_code'] == "1047"){
            $cust_exam_name = '(BCBF Basic)';
          }*/
          echo "<b>".$applied_exam_info[0]['description']." ".$cust_exam_name."</b>";?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>

                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5">
                    <?php echo $applied_exam_info[0]['exam_fee'];?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                	<div class="col-sm-5">
                      <?php 
                    //$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4)."-".date('d');
					/*$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4);
                    echo date('F',strtotime($month))."-".substr($applied_exam_info['0']['exam_month'],0,-2);*/
                    $month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4);
                    echo date('F',strtotime($month))."-".substr($applied_exam_info['0']['exam_month'],0,-2); 
					//echo 'JULY 2023';
             ?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode</label>
                	<div class="col-sm-5">
                      <?php 
					if($applied_exam_info[0]['exam_mode']=='ON')
					{
						echo 'Online';
					}
					else if($applied_exam_info[0]['exam_mode']=='OF')
					{
						echo 'Offline';
					}?>
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium</label>
                	<div class="col-sm-5">
                   <?
                                echo $medium[0]['medium_description'];
                       ?>
                 
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center</label>
                	<div class="col-sm-5">
                    <?php 
					if(isset($center[0]['center_name']))
					{
								echo $center[0]['center_name'];
					}
					?>
                 
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Number</label>
                	<div class="col-sm-5">
                      <?php echo $payment_info[0]['transaction_no'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Status</label>
                	<div class="col-sm-5">
                      <?php if($payment_info[0]['status']=='1'){echo 'Success';}else{echo 'Unsuccess';}?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Date</label>
                	<div class="col-sm-5">
                      <?php echo $payment_info[0]['date'];?>
                    </div>
                </div>

                <?php if($applied_exam_info[0]['exam_code'] == 101 || $applied_exam_info[0]['exam_code'] == 1046 || $applied_exam_info[0]['exam_code'] == 1047){ ?>
                <div style="text-align:left">
                  <note><b>Note:</b><br></note>
                  <note><b>Admit letter shall be available on IIBF website one week before the examination date.</b></note>
                  <br>
                  <note><b>Certificate shall be issued to the candidates who clear the examination and their KYC verification is successfully done.</b></note>
                </div>
                <br><br>
              <?php } ?>
                
                <?php if($payment_info[0]['status']=='1')
				{?>
               			 <div style="text-align:left">
                        <a class="noprintelm" href="<?php echo base_url()?>NonMember/exampdf/">Save as pdf</a> &nbsp; &nbsp;
                      <a class="noprintelm" href="javascript:void(0);" onclick="print_exam_non_mem_preview()">Continue</a>

                      <?php if($applied_exam_info[0]['exam_code'] != 101 && $applied_exam_info[0]['exam_code'] != 1046 && $applied_exam_info[0]['exam_code'] != 1047) { ?>
                        <?php 
                      if(!in_array($applied_exam_info[0]['exam_code'],$this->config->item('skippedAdmitCardForExams'))) { //priyanka d >>27-dec-24 >> by default selecting venue for jaiib/caiiib as we don't have to create admitcard from filled form now >> exam_cd
                       ?> 
                     <a class="noprintelm" href="<?php echo base_url()?>/admitcard/getadmitdashboard" target="_blank">Download admitcard</a> 
                   <?php } ?>
                   <?php } ?>   

					   <a href="<?php echo base_url()?>uploads/Publications _List_IT.xlsx" class='publication noprintelm' data-attr=<?php echo $applied_exam_info[0]['regnumber'];?>  target="_blank">Publications</a> &nbsp; &nbsp;
                       
                       <?php
					  		$link = '#';
							if($applied_exam_info[0]['exam_code'] == 1002){
								$link = 'https://www.amazon.in/dp/B088FXM796';
							}elseif($applied_exam_info[0]['exam_code'] == 1003){
								$link = 'https://www.amazon.in/Micro-Small-Medium-Enterprises-India-ebook/dp/B07Z9CJTDJ/ref=sr_1_1?dchild=1&keywords=9789386394071&qid=1589462229&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1004){
								$link = 'https://www.amazon.in/dp/B088FXM796';
							}elseif($applied_exam_info[0]['exam_code'] == 1005){
								$link = 'https://www.amazon.in/Bankers-Handbook-Credit-Management-IIBF-ebook/dp/B07KPGWNCM/ref=tmm_kin_swatch_0?_encoding=UTF8&qid=1542791029&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1008){
								$link = 'https://www.amazon.in/Bankers-Handbook-Accounting-Institute-Banking-ebook/dp/B089FY5BXQ/ref=sr_1_1?keywords=9789387957251&qid=1594714135&s=books&sr=1-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1010){
								$link = 'https://www.amazon.in/Customer-Service-Banking-Codes-Standards-ebook/dp/B07Y9Y23YY/ref=sr_1_1?dchild=1&keywords=9789386189707&qid=1594713806&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1011){
								$link = 'https://www.amazon.in/Security-Indian-Institute-Banking-Finance-ebook/dp/B07Z9DF2RY/ref=sr_1_1?dchild=1&keywords=9789350719572&qid=1594713837&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1012){
								$link = 'https://www.amazon.in/Information-System-Institute-Banking-Finance-ebook/dp/B089FZ8JTN/ref=sr_1_2?dchild=1&keywords=9789386394439&qid=1594713869&sr=8-2';
							}elseif($applied_exam_info[0]['exam_code'] == 1013){
								$link = 'https://www.amazon.in/Digital-Banking-Indian-Institute-Finance-ebook/dp/B082XB2SCX/ref=sr_1_1?dchild=1&keywords=9789389546347&qid=1594713890&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1014){
								$link = 'https://www.amazon.in/International-Finance-Indian-Institute-Banking-ebook/dp/B07Z9DZYC3/ref=sr_1_1?dchild=1&keywords=9789386394729&qid=1594713911&sr=8-1';
							}
						?>
						
						<a class="noprintelm" href="<?php echo $link;?>" target="_blank">Ebook</a>
            
            <?php if($applied_exam_info[0]['exam_code'] == 101 || $applied_exam_info[0]['exam_code'] == 1046 || $applied_exam_info[0]['exam_code'] == 1047){ ?>
            &nbsp;&nbsp;<a class="noprintelm" href="javascript:void(0);" onClick="printAcknowDiv('printAcknowId')">Save Acknowledgement</a>
          <?php } ?>
                       
                       
                        </div>
					<?php 
				}?>	
                </div>
                
               </div> 
               
               
               
               <!-- Basic Details box closed-->
                 
  </div>
     
      
      </div>
    </section>
 
  
     </form>
     </div>
<!-- Data Tables -->
<!--<script>
   history.pushState(null, null, document.title);
   window.addEventListener('popstate', function () {
       history.pushState(null, null, document.title);
   });
 </script>-->

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
