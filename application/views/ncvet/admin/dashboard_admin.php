<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'NCVET'; } ?></title>
    <?php $this->load->view('ncvet/inc_header'); ?>    
  </head>
	<body class="fixed-sidebar">
		<?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
			<?php $this->load->view('ncvet/admin/inc_sidebar_admin'); ?>			
			<div id="page-wrapper" class="gray-bg">				
				<?php $this->load->view('ncvet/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Dashboard </h2>
						<ol class="breadcrumb"><li class="breadcrumb-item active"> <strong>Dashboard</strong></li></ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					
          <div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins text-centerx">
								<div class="ibox-title"><h2>Welcome To Admin Dashboard</h2></div>
                <div class="ibox-content">
									<h4>
										<?php echo date("d F, Y. h:i A"); ?>	
                  </h4>
								</div>
					    </div>
					  </div>
					</div>

          <div class="row justify-content-md-centerx" style="display:nonex;">

            <!-------------------- Total Candidate Enrollment ------------------->
            <div class="col-lg-4">
              <div class="ibox">                    
                <div class="ibox-title">                    
                  <h5>Total Candidates Enrolled : <?php echo $total_candidate_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
								</div>
                
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>Total Candidates Enrolled </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><?php echo $total_candidate_cnt; ?></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total KYC Completed </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><?php echo $total_kyc_completed_candidate_cnt; ?></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total Candidates Enrolled for Training </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;">0</a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total Registered for Exam </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;">0</a></td>
                      </tr>                      
                    </tbody>
                  </table>
								</div>
							</div>
						</div>
						
            <!-------------------- IIBF Enrollment ------------------->
            <div class="col-lg-4">
              <div class="ibox">                    
                <div class="ibox-title">                    
                  <h5>Total IIBF Candidates Enrolled : <?php echo $total_iibf_candidate_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>Total IIBF Candidates Enrolled </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><?php echo $total_iibf_candidate_cnt; ?></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total IIBF KYC Completed </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><?php echo $total_kyc_completed_iibf_candidate_cnt; ?></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total IIBF Candidates Enrolled for Training </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;">0</a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total IIBF Registered for Exam </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;">0</a></td>
                      </tr>                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-------------------- BFSI SSC Enrollment ------------------->
            <div class="col-lg-4">
              <div class="ibox">                    
                <div class="ibox-title">                    
                  <h5>Total BFSI SSC Candidates Enrolled : <?php echo $total_bfsi_candidate_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>Total BFSI SSC Candidates Enrolled </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><?php echo $total_bfsi_candidate_cnt; ?></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total BFSI SSC KYC Completed </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><?php echo $total_kyc_completed_bfsi_candidate_cnt; ?></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total BFSI SSC Candidates Enrolled for Training </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;">0</a></td>
                      </tr>                      
                      <tr>
                        <td><b>Total BFSI SSC Registered for Exam </b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;">0</a></td>
                      </tr>                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>  

            <!-- <div class="col-lg-4">
              <div class="ibox ">
                <div class="ibox-title">                    
                  <h5>Today's Exam Registration : <?php echo $today_exam_registraion_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
									  </div>
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>BC/BF Exam (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                      <tr>
                        <td><b>BC/BF Exam (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                    </tbody>
                  </table>
									    </div>
									  </div>
									</div> -->

            <!-- <div class="col-lg-4">
              <div class="ibox ">
                <div class="ibox-title">                    
                  <h5>Total Training Registration : <?php echo $total_registraion_for_training_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>BC/BF Exam (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                      <tr>
                        <td><b>BC/BF Exam (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Eligible for Examination but not Applied</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                      </tr>                      
                      <tr>
                        <td><b>Candidates required to re-enroll for Training</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"></a></td>
                      </tr>                      
                    </tbody>
                  </table>
                </div> 
							</div>
						</div> -->
					</div>
        </div>				
				
				<?php $this->load->view('ncvet/admin/inc_footerbar_admin'); ?>		
			</div>
		</div>
		<?php $this->load->view('ncvet/inc_footer'); ?>		
		<?php $this->load->view('ncvet/common/inc_bottom_script'); ?>		
	</body>
</html>