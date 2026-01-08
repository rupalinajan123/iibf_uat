<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/inc_header'); ?> 
  </head>
	<body class="fixed-sidebar">
		<?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div class="modal fade" id="confirm"  role="dialog" >
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					
					<div class="modal-body">
						<div class="message" style="color:#F00; text-align:center; font-size:20px;"><strong>VERY IMPORTANT</strong></div>
							<br />
							<br />
						
							<div class="message" style="color:#F00; text-align:justify;font-size:16px;"> Candidates are required to take utmost care and precaution in selecting Centre as there is no provision to change the Centre in the system.<br />
								<br />
								Hence no request for change of centre will be entertained for any reason.<br />
								<br />
								
								THE FEES ONCE PAID WILL NOT BE REFUNDED OR ADJUSTED ON ANY ACCOUNT</div>
						</div>
						<div class="modal-footer"><!--data-dismiss="modal"-->
							<input type="button" name="btnSubmit"  class="btn btn-primary" id="btnSubmit" value="Okay" onclick="Show();">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="wrapper">
			<?php $this->load->view('ncvet/candidate/inc_sidebar_candidate'); ?>			
			<div id="page-wrapper" class="gray-bg">				
				<?php $this->load->view('ncvet/candidate/inc_topbar_candidate'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Exam Instructions 
						</h2>	
						<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/candidate/applyexam/examlist'); ?>">Exams</a></li>
						</ol>					
					</div>
					<div class="col-lg-2"> 
						
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins text-centerx">
								<div class="ibox-title"><h2>Exam Instructions </h2>
									
								</div>
									
									<div class="ibox-content">
										<form method="post" action="<?php echo site_url('ncvet/candidate/applyexam/examform'); ?>" id="exam_details" enctype="multipart/form-data" autocomplete="off">
											<h4>
												<?php echo date("d F, Y. h:i A");  ?>	
											</h4>
											
												<div class="row">
													<div class="col-xl-12 col-lg-12">
														<div class="form-group">
														
															<?php 
															if(count($exam_info) >0 && $exam_info[0]['nonmember_instruction']!='')
															{
																$newstring = str_replace("#url#", "".base_url()."", htmlspecialchars_decode( $exam_info[0]['nonmember_instruction']));
																echo  $finalstring = str_replace("{url}", "javascript:void(0);", $newstring);
															}
															?>
														</div>          
													</div>
													<div class="col-xl-12 col-lg-12">
														<div class="form-group">
															<input style="cursor: pointer;" name="declaration1" value="1" id="agree" type="checkbox" required>&nbsp;
															<label for="agree" style="cursor: pointer;">I
																have read the Rules and Regulations and other instructions governing 
																the above examination and I agree to abide by the said Rules, 
																Regulations and Instructions</label>
														</div>
													</div>
													
												</div>
												<div class="row">
													<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer2">
													<input type="submit" class="btn btn-primary" id="submitAll" name="submitAll" value="I Agree"> 
													</div>
												</div>

										</form>
									</div>
								</div>
							</div>
						</div>
					</div>				
					
					<?php $this->load->view('ncvet/candidate/inc_footerbar_candidate'); ?>		
				</div>
			</div>
			<?php $this->load->view('ncvet/inc_footer'); ?>		
			<?php $this->load->view('ncvet/common/inc_bottom_script'); ?>		
			<script>
				$(document).ready(function(){
				//$('#confirm').modal('show');
				$('#confirm').modal({backdrop: 'static', keyboard: false}, 'show');
				});
				function Show(){ 
				$('#confirm').modal('hide');
				
				} 
			</script>
	</body>
</html>