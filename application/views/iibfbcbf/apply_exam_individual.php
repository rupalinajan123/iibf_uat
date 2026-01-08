<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>

    <!-- GAURAV START CSS -->
    <style>
      /* Base Modal Container */
      .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1050; /* Ensure it's above other elements, common Bootstrap modal z-index */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if content overflows */
        background-color: rgba(0, 0, 0, 0.6); /* Darker overlay for better focus */
        /* Flexbox for perfect centering */
        /*display: flex;*/
        align-items: center;
        justify-content: center;
      }

      /* Modal Content Box */
      .modal-content {
        background-color: #fff; /* White background */
        margin: auto; /* Centering fallback */
        padding: 50px 30px; /* Increased padding */
        border: 1px solid #ddd; /* Lighter border */
        border-radius: 8px; /* Slightly more rounded corners */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); /* Subtle shadow for depth */
        width: 95%; /* Responsive width */
        max-width: 850px; /* Maximum width */
        position: relative; /* Needed for absolute positioning of close button */
        font-family: Arial, sans-serif; /* Readable font */
        line-height: 1.9; /* Improved line spacing */
        color: #333; /* Darker text for readability */
      }

      .close-btn:hover,
      .close-btn:focus {
        color: #333; /* Darker on hover */
        text-decoration: none;
      }

      /* Modal Title */
      .modal-content p {
        font-size: 18px;
      }  
      .modal-content h3 {
        font-size: 30px; /* Larger, more prominent title */
        color: #00BCD4; /* Alert-like red for "BEWARE" */
        margin-top: 0; /* Remove default top margin */
        margin-bottom: 20px; /* Space below title */
        text-align: center; /* Center the title */
        font-weight: bold; /* Ensure it's bold */
      }

      /* Modal Body Content */
      .modal-body-content p {
        margin-bottom: 15px; /* Space between paragraphs */
        text-align: justify; /* Justify text for a cleaner block look */
      }

      .modal-body-content strong {
        font-weight: bold; /* Ensure strong tags are bold */
        color: #d9534f; /* Highlight important parts in red */
      }
    </style>
    <!-- GAURAV END CSS -->

  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
    <div class="d-flex logo"><img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0">INDIAN INSTITUTE OF BANKING & FINANCE - BCBF Apply Exam</h3></div>
    <div class="bcbf_wrap"> 
      <div class="half-circle"></div>
        <div class="container">        
        

          <div class="admin_login_form animated fadeInDown"> 
            <form class="m-t" action="<?php echo site_url('iibfbcbf/apply_exam_individual'); ?>" method="post" enctype="multipart/form-data" id="iibf_bcbf_apply_individual_exam_form" autocomplete="off">
              <div class="form-group">
                <label for="training_id" class="form_label">Training ID or Registration Number <sup class="text-danger">*</sup></label>
                <input type="text" name="training_id" id="training_id" value="<?php if(set_value('training_id') != "") { echo set_value('training_id'); } ?>" class="form-control" placeholder="Training ID or Registration Number *" required />
                <?php if (form_error('training_id') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('training_id'); ?></div><?php } ?>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="iibf_bcbf_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
                    <input type="text" name="iibf_bcbf_captcha" id="iibf_bcbf_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
                    <?php if (form_error('iibf_bcbf_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('iibf_bcbf_captcha'); ?></label> <?php } ?>
                  </div>
                  <div class="col-md-6">
                    <label class="form_label">&nbsp;</label>
                    <div class="captcha_common">
                      <div class="image">
                        <?php echo $captcha_img; ?>
                      </div>
                      <a href="javascript:void(0)" onclick="refresh_captcha()" class="refresh_captcha_link"><i class="fa fa-refresh"></i></a>
                    </div>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary block full-width btn-login">Get Details</button>

              <!-- For OLD BCBF Exam -->
              <?php if(isset($_GET['ctype']))
              {
                $url_old_bcbf = '';
                if($_GET['ctype'] == "Tk0=")
                {
                  $url_old_bcbf = base_url('nonreg/examlist/?Extype=Mg==&Mtype=Tk0=');
                }
                else if($_GET['ctype'] == "Tw==")
                {
                  $url_old_bcbf = base_url('Register/examlist/?Extype=Mg==&Mtype=Tw==');
                } ?>
                <div>
                  <div style='text-align: center; font-size: 14px;  font-weight: bold;  margin: 15px 0 5px 0;'>OR</div>
                  <label style='text-align: center;  width: 100%;  font-weight: bold;  margin: 0 0 5px 0;  font-size: 14px;'>for BC agents onboarded with Banks before 1st April 2024, click here! <sup class="text-danger"></sup></label> 
                  <a href="<?php echo $url_old_bcbf; ?>" class="btn btn-primary block full-width btn-login">Without Training</a>
                </div> 
              <?php } ?><!-- For OLD BCBF Exam -->
              
            </form>
          </div>
        </div>
    </div>
    

    <!-- GAURAV START POP -->
    <div id="fraudAlertModal" class="modal">
      <div class="modal-content">
        <!-- <span class="close-btn" id="closeModal">&times;</span> -->
        <h3>BEWARE OF FRAUDULENT OFFERS FOR CERTIFICATION</h3>
        <div class="modal-body-content">
          <p>
            It has come to the Institute’s attention that certain individuals are fraudulently
            offering IIBF certifications without the mandated training and/or examinations.
          </p>
          <p>
            <strong style="color: black;">Candidates are strictly cautioned</strong> that IIBF has not authorised any person,
            intermediary, or agency to provide BC/BF certifications through such means.
          </p>
          <p>
            Candidates are advised <strong style="color: black;">not to engage with or make any payment</strong> to such parties. Any
            candidate who chooses to do so shall be solely responsible for the
            consequences, including possible debarment from future examinations for
            resorting to unfair means.
          </p>
          <p>
            IIBF shall not be liable for any loss, dispute, or claim arising from such fraudulent practices.
          </p>
          <center><button class="btn btn-primary close-btn" id="closeModal" style="font-size: 15px;">OK</button></center>
        </div>
      </div>
    </div>
    <!-- GAURAV END POP -->

    <?php $this->load->view('iibfbcbf/inc_footer'); ?>
    <?php if ($error) { ?><script>sweet_alert_error("<?php echo $error; ?>"); </script><?php } ?>
    
    <script type="text/javascript" src="<?php echo auto_version(base_url('assets/iibfbcbf/jquery_validation/jquery.validate.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo auto_version(base_url('assets/iibfbcbf/jquery_validation/jquery.validate_additional.js')); ?>"></script>
    
    <script type="text/javascript">
      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#iibf_bcbf_captcha").val("");
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/apply_exam_individual/refresh_captcha'); ?>",
          success: function(res) 
          {
            if (res) 
            {
              jQuery("div.image").html(res);
              $("#page_loader").css("display", "none");
            }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            //$('#current_faculty_status').val(status);
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.");
            $("#page_loader").hide();

          }
        });
      }
      
      $(document).ready(function() 
      {
        $.validator.addMethod("required", function(value, element) { if ($.trim(value).length == 0) { return false; } else { return true; } });
        
        $("#iibf_bcbf_apply_individual_exam_form").validate(
        {
          onblur: function(element) { $(element).valid(); },
          rules: 
          { 
            training_id: { required: true },
            iibf_bcbf_captcha: { required: true, remote: { url: "<?php echo site_url('iibfbcbf/apply_exam_individual/validation_check_captcha/0/1'); ?>", type: "post" } }
          },
          messages: 
          {
            training_id: { required: "Please enter the Training ID or Registration Number" },
            iibf_bcbf_captcha: { required: "Please enter the code", remote: "Please enter the valid code" }
          },
          submitHandler: function(form) 
          {
            $("#page_loader").show();
            form.submit();
          }
        });


        // --- GAURAV START: Modal Logic with Corrected URL Cleanup ---
        var modal = $("#fraudAlertModal");
        var closeBtn = $("#closeModal");
        var currentURL = window.location.href;

        // Function to close the modal
        function closeFraudAlert() {
         modal.css("display", "none");
        }
        
        // Check if the URL contains "popup=yes"
        if (currentURL.includes('popup=yes')) {
          // 1. Show modal
          modal.css("display", "flex"); // Changed to flex to use CSS centering

          // 2. Remove 'popup=yes' from the URL without reloading the page
          var cleanedURL = currentURL
           .replace(/[?&]popup=yes/, '')
           .replace(/(\?|&)$/, '');

          window.history.replaceState({}, document.title, cleanedURL);
        }

        // Closing logic for the modal
        closeBtn.click(closeFraudAlert);

        $(window).click(function(event) {
         if (event.target.id === 'fraudAlertModal') {
          closeFraudAlert();
         }
        });
        // --- GAURAV END: Modal Logic with Corrected URL Cleanup ---

      });
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>