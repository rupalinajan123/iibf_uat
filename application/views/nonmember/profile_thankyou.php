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
                
                <?php if($exam_info[0]['exam_code'] == 101 || $exam_info[0]['exam_code'] == 1046 || $exam_info[0]['exam_code'] == 1047){ ?>
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name :</label>
                  <div class="col-sm-5">
                  <?php if(isset($result[0]['firstname']) && $result[0]['firstname'] != ""){echo $result[0]['firstname']." ".$result[0]['lastname'];}?>
                    </div>
                </div>
              <?php } ?>

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
                
                <?php if($exam_info[0]['exam_code'] == 101 || $exam_info[0]['exam_code'] == 1046 || $exam_info[0]['exam_code'] == 1047){ 
                  //$exam_date = '';
                //echo $applied_exam_info[0]['exam_period'];
                $subject_details = $this->master_model->getRecords('subject_master',array('exam_code'=>$exam_info[0]['exam_code'],'exam_period'=>$exam_info[0]['exam_period']),'exam_date',array('id'=>'desc')); 


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
                <label for="roleid" class="col-sm-3 control-label">Exam Name :</label>
                	<div class="col-sm-5">
                  <?php 
                  $cust_exam_name = '';
          /*if($exam_info[0]['exam_code'] == "1046"){
            $cust_exam_name = '(BCBF Advanced)';
          }else if($exam_info[0]['exam_code'] == "1047"){
            $cust_exam_name = '(BCBF Basic)';
          }*/
          echo "<b>".$exam_info[0]['description']." ".$cust_exam_name."</b>";
				 			 ;?>
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
        

            <?php 
            $excodes = array(220,8,11,19,78,79,151,153,156,158,163,165,166,119,157);

            if($exam_info[0]['exam_code'] == 101 || $exam_info[0]['exam_code'] == 1046 || $exam_info[0]['exam_code'] == 1047){ ?>
                <div style="text-align:left">
                  <note><b>Note:</b><br></note>
                  <note><b>Admit letter shall be available on IIBF website one week before the examination date.</b></note>
                  <br>
                  <note><b>Certificate shall be issued to the candidates who clear the examination and their KYC verification is successfully done.</b></note>
                </div>
                <br><br>
              <?php } ?>


              <?php if(in_array($exam_info[0]['exam_code'],$excodes)){ ?>
                <div style="text-align:left">
                  <note><b>Note:</b><br></note>
                  <note><b>Please note that the admit letters will be available for download on the Institute’s website as well as in the candidate login 10 days before the exam</b></note>
                </div>
                <br><br>
              <?php } ?>

              
        </div>
        <?php 
		if($payment_info[0]['status']==1)
			{?>
			<div class="box-footer">

          <div class="col-sm-12">

          <!-- <div class="col-sm-2 col-xs-offset-5"> -->
            <a class="noprintelm" href="<?php echo  base_url()?>Nonreg/logout/">Logout</a>&nbsp;&nbsp;
          <!-- </div> -->
		  
           <!-- <div class="col-sm-2 col-xs-offset-5"> -->
       	   <a class="noprintelm" href="<?php echo  base_url()?>NonMember/Dashboard/">Home</a>&nbsp;&nbsp;
          <!-- </div> -->

          <?php 
            if($payment_info[0]['status']==1 && $payment_info[0]['ref_id'] != '')
            {

              $admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$payment_info[0]['ref_id'],'mem_mem_no'=>$payment_info[0]['member_regnumber'],'exm_cd'=>$payment_info[0]['exam_code'],'remark'=>1),'admitcard_image',array('admitcard_id'=>'desc')); 
              if(isset($admit_card_details) && count($admit_card_details) > 0 && $admit_card_details[0]['admitcard_image'] != "" && $exam_info[0]['exam_code']!=101 && $exam_info[0]['exam_code']!=1046 && $exam_info[0]['exam_code']!=1047){
              ?>

              <?php if(!in_array($exam_info[0]['exam_code'],$this->config->item('skippedAdmitCardForExams'))) { ?>
                <a href="<?php echo base_url()?>/uploads/admitcardpdf/<?php echo $admit_card_details[0]['admitcard_image']?>" class="noprintelm" target="_blank">Download admitcard</a>&nbsp;&nbsp;
              <?php } ?>
          
          <?php } 
            } ?>

		  
		    <!-- <div class="col-sm-2 col-xs-offset-5"> -->

          

       	  <a href="<?php echo base_url()?>uploads/Publications _List_IT.xlsx" class='publication noprintelm' data-attr=<?php echo $result[0]['regnumber'];?>  target="_blank">Publications</a>&nbsp;&nbsp;
          
          &nbsp; &nbsp;
          <?php
							if($exam_info[0]['exam_code'] == 1002){
								$link = 'https://www.amazon.in/dp/B088FXM796';
							}elseif($exam_info[0]['exam_code'] == 1003){
								$link = 'https://www.amazon.in/Micro-Small-Medium-Enterprises-India-ebook/dp/B07Z9CJTDJ/ref=sr_1_1?dchild=1&keywords=9789386394071&qid=1589462229&sr=8-1';
							}elseif($exam_info[0]['exam_code'] == 1004){
								$link = 'https://www.amazon.in/dp/B088FXM796';
							}
						?>
						
						<a class="noprintelm" href="<?php echo $link;?>" target="_blank">Ebook</a>

            <?php if($exam_info[0]['exam_code'] == 101 || $exam_info[0]['exam_code'] == 1046 || $exam_info[0]['exam_code'] == 1047){ ?>
            &nbsp;&nbsp;<a class="noprintelm" href="javascript:void(0);" onClick="printAcknowDiv('printAcknowId')">Save Acknowledgement</a>
          <?php } ?>
          
          <!-- </div> -->
		  
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

