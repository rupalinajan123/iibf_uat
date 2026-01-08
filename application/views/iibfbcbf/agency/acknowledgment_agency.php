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
					<div class="col-lg-10">
						<h2>Transaction Details</h2>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins text-centerx">
                <div class="table-responsive">
                  <table class="table table-bordered custom_inner_tbl" style="width: 100%;max-width: 600px;margin: 0 auto;background: #fff;">
                    <tbody>
                      <tr><td><b>Agency Name</b></td><td><?php echo $payment_info[0]['agency_name']." (".$payment_info[0]['agency_code'].")";?></td></tr>
                      <tr><td><b>Centre Name</b></td><td><?php echo $payment_info[0]['centre_name']." (".$payment_info[0]['centre_username'].")";?></td></tr>
                      <tr><td><b>Candidate Name</b></td><td><?php echo $payment_info[0]['salutation']." ".$payment_info[0]['first_name']; if($payment_info[0]['middle_name'] != "") { echo " ".$payment_info[0]['middle_name']; } if($payment_info[0]['last_name'] != "") { echo " ".$payment_info[0]['last_name']; } ?></td></tr>
                      <tr><td><b>Registration Number</b></td><td><?php echo $payment_info[0]['regnumber'];?></td></tr>
                      <tr><td><b>Training ID</b></td><td><?php echo $payment_info[0]['training_id'];?></td></tr>
                      <tr><td><b>Email ID</b></td><td><?php echo $payment_info[0]['email_id'];?></td></tr>
                      <tr><td><b>Mobile Number</b></td><td><?php echo $payment_info[0]['mobile_no'];?></td></tr>
                      <tr><td><b>Transaction Number</b></td><td><?php echo $payment_info[0]['transaction_no'];?></td></tr>
                      <tr>
                        <td><b>Transaction Status</b></td>
                        <td>
                          <?php if($payment_info[0]['status']=='1') {echo 'Success';} 
                          else if($payment_info[0]['status']=='2'){echo 'Pending';} 
                          else if($payment_info[0]['status']=='3'){echo 'Applied';} 
                          else if($payment_info[0]['status']=='4'){echo 'Cancelled';}
                          else if($payment_info[0]['status']=='0'){echo 'Fail';} ?>
                        </td>
                      </tr>
                      <tr><td><b>Transaction Date</b></td><td><?php echo $payment_info[0]['date'];?></td></tr>
                      
                      <?php if($payment_info[0]['status']=='1') { ?>
                        <tr><td><b>Examination Date</b></td><td><?php echo $payment_info[0]['exam_date'];?></td></tr>
                        <tr>
                          <td><b>Download Invoice</b></td>
                          <td>
                            <a href="<?php echo site_url('iibfbcbf/download_file_common/index/'.url_encode($payment_info[0]['invoice_id']).'/invoice_image'); ?>" class="example-image-link btn btn-success" target="_blank">Download Invoice</a></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
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