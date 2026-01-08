<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
  </head>
	
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2>Payment Receipt</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/transaction_details_agency'); ?>">Transaction Details</a></li>
							<li class="breadcrumb-item active"> <strong>Payment Receipt</strong></li>
            </ol>
          </div>
        </div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									<div class="ibox-tools">
										<a href="<?php echo site_url('iibfbcbf/agency/transaction_details_agency'); ?>" class="btn btn-primary custom_right_add_new_btn">Back</a>                    
                  </div>
                </div>
                
								<div class="ibox-content">
                  <div class="table-responsive">
                    <?php 
                      $sub_data['payment_data'] = $payment_data;
                      $this->load->view('iibfbcbf/common/inc_payment_receipt_agency_common', $sub_data); 
                    ?>
                  </div>                
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>			
      </div>
    </div>
        
    <?php $this->load->view('iibfbcbf/inc_footer'); ?>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
  </body>
</html>    