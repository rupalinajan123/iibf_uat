<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('ncvet/inc_header'); ?>
  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('ncvet/common/inc_loader'); ?>
    <div class="ncvet_wrap"> 
      
      <div class="d-flex logo"><img src="<?php echo base_url('assets/ncvet/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0" style="    font-size: 20px;">INDIAN INSTITUTE OF BANKING & FINANCE <br>
								ISO 21001:2018 Certified</h3></div>
      <div class="container">        
       
        <div class="admin_login_form animated fadeInDown" style="max-width:none; margin-top:110px">
          <h3 style="text-align: center;margin-bottom: 2%;" class="col-xl-12 col-lg-12" >NCVET – Admission cum Enrollment Form (Fundamentals of Retail Banking)</h3>
          <h3 class="text-center mb-4"><b>Your application has been submitted successfully</b></h3>

          <div style="text-align:justify;width:600px;"  id="printableDiv">
                     
                      <p><strong>Your Enrollment No is</strong> <?php echo $application_number;?> <strong>and Your password is </strong><?php echo $password?></p>
                      <p>Please print/note down your Enrollment No and Password as these will be required for:</p>
                      <ul>
                        <li>Training Re-enrollment</li>
                        <li>Examination Registration/Re-registration</li>
                        <li>Accessing Edit Profile</li>
                        <li>Downloading Admit Letter, Results, etc.</li>
                      </ul>
          </div><br>
         
           <div style="text-align:left">
            
            <a href="<?php echo base_url();?>/ncvet/candidate/login_candidate" style="margin-left:15px;">Go To Profile</a>
            &nbsp; &nbsp; / &nbsp; &nbsp;
             <a href="javascript:void(0);" onclick="printDiv('printableDiv')">Print </a>
          </div>
           
        </div>
        
      </div>
    </div>    
    
    <?php $this->load->view('ncvet/inc_footer'); ?>
    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
    <script>
      function printDiv(divId) {
          var content = document.getElementById(divId).innerHTML;
          var originalContent = document.body.innerHTML;

          document.body.innerHTML = content;
          window.print();
          document.body.innerHTML = originalContent;
      }
      </script>
  </body>
</html>