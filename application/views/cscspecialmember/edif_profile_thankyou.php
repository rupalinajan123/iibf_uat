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
     INDIAN INSTITUTE OF BANKING & FINANCE
        
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>

    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
            (An ISO 21001:2018 Certified )
            <div style="float:right;">
            <a href="<?php echo base_url();?>NonMember/profile/">Back</a>
            </div>
            </div>
           
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
               <div class="form-group">
                <?php $exam_info = $this->session->userdata('examinfo'); ?>

                	<div class="col-sm-9" id="printAcknowId">
                   Your application saved successfully.<br><br><strong>Your Membership No is</strong> <?php echo $application_number;?> <br><br>Please note down your Membership No for further reference.<br> <br>Please check your <strong>registered email</strong> for the admit card.<br><br> <strong>Kindly download and save it for your reference.</strong><br><br>
									
                        <div style="text-align:left">
                        <!-- <a class="noprintelm" href="<?php echo base_url()?>NonMember/downloadeditprofile/">Save as pdf</a> &nbsp; &nbsp;
                        <a class="noprintelm" href="javascript:void(0);" id="print_id" onClick="edit_profile_Nonmemb_preview();">Continue</a> -->
                        &nbsp;&nbsp;
                        <?php
           $admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$this->session->userdata('cscnmregnumber'),'exm_cd'=>base64_decode($exam_info['excd']),'exm_prd'=>$exam_info['eprid'],'remark'=>1),'admitcard_image',array('admitcard_id'=>'desc')); 
           //echo $this->db->last_query();
              if(isset($admit_card_details) && count($admit_card_details) > 0 && $admit_card_details[0]['admitcard_image'] != "" && (base64_decode($exam_info['excd'])==1052 || base64_decode($exam_info['excd'])==1054 || base64_decode($exam_info['excd'])==1053)){

              ?>
                <a href="<?php echo base_url()?>uploads/admitcardpdf/<?php echo $admit_card_details[0]['admitcard_image']?>" class="noprintelm" target="_blank">Download admitcard</a>&nbsp;&nbsp;
          <?php }
           ?>
          <?php if(base64_decode($exam_info['excd']) == 1052 || base64_decode($exam_info['excd']) == 1054){ ?>
          &nbsp;&nbsp;
           <a class="noprintelm" href="javascript:void(0);" onClick="printAcknowDiv('printAcknowId')">Save Acknowledgement </a>
           
        <?php } ?>

                        </div>
                    </div>
                    
                     
                    
                </div>
           
                </div>
                
               </div> <!-- Basic Details box closed-->
                 
                
             
             
        </div>
      </div>
     
      
      
    </section>
 
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

<!-- Div to print  -->
<!--<script>
   history.pushState(null, null, document.title);
   window.addEventListener('popstate', function () {
       history.pushState(null, null, document.title);
   });
 </script>-->