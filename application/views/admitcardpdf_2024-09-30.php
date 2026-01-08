<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view('google_analytics_script_common'); ?>
		<meta charset="utf-8">
		<title>Welcome to IIBF</title>
	</head>
	<body style="background-color:#fff; margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">
		<table cellpadding="0" cellspacing="0" width="800" border="0" align="center">
			<tr>
				<td style="background-color:#fff;"><!--table-1-->
					
					<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
						<tr>
							<td style="border:1px solid #1287c0; padding:5px;"><table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
								<tr>
									<td align="center">
										<img src="<?php echo base_url();?>assets/images/admit_logo.jpg" width="400" height="66" /> 
										
										<?php if($exam_code != 1037 && $exam_code != 1038 && $exam_code != 1046 && $exam_code != 1047) 
											{ ?>
										<img src="<?php echo base_url()?>assets/images/ninty_year_new.png" width="70" height="70" style="margin-left: 100px; margin-right: -190px;" /></td>
									<?php } ?>
								</tr>
								<tr>
									<td height="5"></td>
								</tr>
								<tr>
									<td style="background-color:#1287c0; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:10px 0;"><?php 
                  	$caiib = array($this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
										if(!in_array($exam_code,$caiib)){
										?>
										<?php
                  		if($exam_code == 101 || $exam_code == 1010 || $exam_code == 996){
												echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS - March 2024";
												}else{
											?>
											Admit Letter for <?php echo $record->mode?>
											<?php //echo $exam_name;?>
											<?php if($exam_code == '20' && $idate == '29-Oct-17')
												{ 
													echo preg_replace("/\([^)]+\)/","",$exam_name); 
												}
												else
												{
													echo $exam_name;
												}
											?>
											<?php if($exam_code != 101 && $exam_code != 1037 && $exam_code != 1038 && $exam_code != 1046 && $exam_code != 1047){ echo "Examination"; }
												
												if($exam_code == 1037 || $exam_code == 1038 || $exam_code == 1046 || $exam_code == 1047) { }
												else
												{
													echo '-'.$examdate;
												}
											} }?>
											<?php
												$elective = array("62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
												if($exam_code == $this->config->item('examCodeCaiib')){
													echo "Admit letter for Online CAIIB Examination – June/July 2022";
												}
												if(in_array($exam_code,$elective)){
													echo "Admit letter for Online CAIIB Electives – June/July 2022";
												}
											?></td>
								</tr>
								<tr>
									<td height="5"></td>
								</tr>
								<tr>
									<td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:7px 0;">Candidate Details</td>
								</tr>
								<tr>
									<td style="background-color:#dcf1fc; padding:7px 0;"><table cellpadding="0" cellspacing="0" width="100%" border="0">
                    <tr>
                      <td style="padding:0 10px;"><table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-left:1px solid #fff; border-top:1px solid #fff;">
												<tr>
													<td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;"><strong>Membership / Registration No. : <?php echo $record->mem_mem_no;?></strong></td>
												</tr>
												<tr>
													<td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;"><?php echo $record->mam_nam_1;?>
														<!-- dob added by vishal -->
														&nbsp;&nbsp; 
														<?php 
                              if ($memberDetails[0]['dateofbirth']!='') {
																echo '<b>DOB:</b>&nbsp;'.date('d-m-Y',strtotime($memberDetails[0]['dateofbirth']));
															}
															
														?>
													</td>
												</tr>
												<tr>
													<td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase;"><?php if($record->mem_adr_1 != ''){?>
														<?php echo $record->mem_adr_1."<br/> ";?>
													<?php }?>
													<?php if($record->mem_adr_2 != ''){?>
														<?php echo $record->mem_adr_2."<br/> ";?>
													<?php }?>
													<?php if($record->mem_adr_3 != '' && ($record->mem_adr_3 != $record->mem_adr_2)){?>
														<?php echo $record->mem_adr_3."<br/>";?>
													<?php }?>
													<?php if($record->mem_adr_4 != '' && ($record->mem_adr_4 != $record->mem_adr_3)){?>
														<?php echo str_replace(";","",$record->mem_adr_4)."<br/> ";?>
													<?php }?>
													<?php if($record->mem_adr_5 != '' && ($record->mem_adr_5 != $record->mem_adr_4)){?>
														<?php echo str_replace(";","",$record->mem_adr_5)."<br/> ";?>
													<?php }?>
													<?php if($record->mem_pin_cd != '' && strlen($record->mem_pin_cd) == 6){?>
														<?php echo "Pincode: ". str_replace(";","",$record->mem_pin_cd)."<br/>";?>
													<?php }?></td>
												</tr>
												<tr>
													<td><table>
														<tr>
															<td style="border:0px solid #333; padding:7px; text-transform:uppercase;"><img src="<?php echo base_url();?>assets/images/phone-icon.png" width="220"  alt="Phone" style="float:left; margin-top:8px; margin-right:15px;"  /></td>
															<td></td>
														</tr>
													</table></td>
												</tr>
											</table>
											<!--table-5--></td>
                      <td align="center"><table cellpadding="0" cellspacing="0" border="0">
												<?php
													if($record->scannedphoto == ''){
														$photo = "photo.jpg";	
														}else{
														$photo = $record->scannedphoto;
													}
													if($record->scannedsignaturephoto == ''){
														$signature = "sign.jpg";	
														}else{
														$signature = $record->scannedsignaturephoto;
													}
												?>
												<tr>
													<td>
														<?php if($exam_code == 1037 || $exam_code == 1038)
                              { ?>
															<img src="<?php echo base_url('uploads/iibfbcbf/photo/'.$photo);?>" width="100" height="125" />
                              <?php }
                              else
                              { ?>
															<img src="<?php echo base_url();?><?php echo get_img_name($member_id,'p');?>" width="100" height="125" />
														<?php } ?>
													</td>
												</tr>
												<tr>
													<td height="5"></td>
												</tr>
												<tr>
													<td>
														<?php if($exam_code == 1037 || $exam_code == 1038)
                              { ?>
															<img src="<?php echo base_url('uploads/iibfbcbf/sign/'.$signature);?>" width="100" height="50" />
                              <?php }
                              else
                              { ?>
															<img src="<?php echo base_url();?><?php echo get_img_name($member_id,'s');?>" width="100" height="50" />
														<?php } ?>                                  
													</td>
												</tr>
											</table>
											<!--table-4--></td>
										</tr>
									</table>
                  <!--table-3--></td>
								</tr>
								<tr>
									<td height="5"></td>
								</tr>
								<tr>
									<td><table cellpadding="0" cellspacing="0" border="0" width="100%"  style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
                    <thead>
                      <tr style="background-color:#7fd1ea;">
                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Subject Name</th>
                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Medium</th>
                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Date</th>

                        <?php if(in_array($exam_code, array(1037,1038,1046,1047))) { ?>
                          <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Reporting Time</th>
                        <?php } ?>

                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Time<sup>@</sup></th>
                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;" >Seat Identification Code**</th>
											</tr>
										</thead>
                    <tbody>
                      <?php 
												foreach($subject as $subject){
													
													/*$this->db->select('subject_description');
														$this->db->from('subject_master');
														$this->db->where(array('subject_code'=>$subject->sub_cd));
														$subject_name = $this->db->get();
													$subject_name_res=$subject_name->result() ;*/

                          $exam_disp_time = new DateTime($subject->time);
                          $exam_disp_time->modify('-30 minutes');
                          $disp_reporting_time = $exam_disp_time->format('h:i A');
												?>
												<tr style="background-color:#dcf1fc;">
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subject->subject_description;?></td>
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $medium;?></td>
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo date('d-M-Y',strtotime($subject->date));?></td>

                          <?php if(in_array($exam_code, array(1037,1038,1046,1047))) { ?>
                            <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $disp_reporting_time;?></td>
                          <?php } ?>

													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->time;?></td>
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->venueid;?></td>
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php
														if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB')){
															echo 'NA';
															}else{
															echo $subject->seat_identification;
														}
														
													?></td>
												</tr>
											<?php }?>
										</tbody>
									</table></td>
								</tr>
								<tr>
									<td style="line-height:24px; font-size:13px;"> 
										<table width="100%" >
											<tr>
												<td style="text-align:left;width:50%;" align="left">
													<?php if($exam_code == 101 || $exam_code == 1037 || $exam_code == 1038 || $exam_code == 1046 || $exam_code == 1047)
														{ ?>
														<!--Pass mark for BC/BF Examination is 40-->
														<?php }
														if($exam_code == 101 || $exam_code == 1037 || $exam_code == 1038 || $exam_code == 1046 || $exam_code == 1047)
														{ ?><br>
														<div style="float:left">
															<?php
																if($exam_code == 1037 || $exam_code == 1046)
																{
																	echo 'Pass mark for BC/BF Examination (Advanced) is 50';
																}
																else if( $exam_code == 1038 || $exam_code == 1047)
																{
																	echo 'Pass mark for BC/BF Examination (Basic) is 25';
																} ?>
														</div>
													<?php } ?> 
												</td>
												
												<td style="text-align:right;width:50%;" align="right">
													**(Refer display board at Test Venue)<br>
													
													<?php                         
														if($exam_code!=34 && $exam_code!=160 && $exam_code!=58 && $exam_code!=101)
														{?>
														@ Refer Reporting Time in the Important Instructions
													<?php } ?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="10"></td>
								</tr>
								<tr>
									<td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
                    <thead>
                      <tr style="background-color:#7fd1ea;">
                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Address</th>
											</tr>
										</thead>
                    <tbody>
                      <?php foreach($recordsp as $recordsp){?>
												<tr style="background-color:#dcf1fc;">
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $recordsp->venueid;?></td>
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $recordsp->venueadd1;?> <?php echo $recordsp->venueadd2;?>
														<?php 
															if($recordsp->venueadd3 != $recordsp->venueadd2){
																echo $recordsp->venueadd3;
															}
														?>
														<?php 
															if($recordsp->venueadd4 != $recordsp->venueadd3){
																echo $recordsp->venueadd4;
															}
														?>
														<?php
															if($recordsp->venueadd5 != $recordsp->venueadd4){ 
																echo $recordsp->venueadd5;
															}
														?>
														<?php 
															if($recordsp->venpin != 0){
																echo $recordsp->venpin;
															}
														?></td>
												</tr>
											<?php }?>
										</tbody>
									</table></td>
								</tr>
								<tr>
									<td><?php if($exam_code == 101  || $exam_code == 1037 || $exam_code == 1038 || $exam_code == 1046 || $exam_code == 1047 || $exam_code == 1010 || $exam_code == 10100 || $exam_code == 101000 || $exam_code == 1010000 || $exam_code == 10100000){?>
										<p><strong>In case of any queries regarding venue, please dial 022-62507716 (available on exam day and previous day only). For all other queries email to care@iibf.org.in </strong></p>
									<?php }?>
                  <?php if($exam_code == 996){?>
										<p><strong>In case of any queries regarding venue, please dial 020-66218336/020-66218338 (available on exam day and previous day only). For all other queries email to care@iibf.org.in </strong></p>
									<?php }?>
                  <?php if($exam_code != 101 && $exam_code != 1037 && $exam_code != 1038 && $exam_code != 1046 && $exam_code != 1047 && $exam_code != 1010 & $exam_code != 10100 & $exam_code != 101000 & $exam_code != 1010000 & $exam_code != 10100000){?>
										<?php if($vcenter == 3){?>
											<p><strong>In case of any queries regarding venue, please dial 022-42547558 (available on exam day and previous day only). For all other queries email to care@iibf.org.in </strong></p>
										<?php }?>
										<?php if($vcenter == 1){?>
											<p> <strong>In case of any queries regarding venue, please dial 1800 419 2929 and press option 7.(available on exam day and previous day only). For all other queries email to care@iibf.org.in</strong> </p>
										<?php } }?>
										<p>Please note that you need to appear for examination at the above mentioned 
										venue only and will not be permitted from any other venue</p>
										
										<p>(Your Admit Letters consists of 4 pages which includes Important Instructions, COVID-19 Guidelines to be followed by the candidates (Annexure I) and Self Declaration form (Annexure A). Kindly go through the instructions carefully, print both pages and carry the same to the examination venue)</p>
									</td>
								</tr>
								<tr>
									<td width="100%"><table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
                    <thead>
                      <tr style="background-color:#7fd1ea;">
                        <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Login Credentials:</th>
											</tr>
										</thead>
                    <tbody>
                      <tr style="background-color:#dcf1fc;">
                        <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">LOGIN ID : Your Membership/Registration No. as mentioned above.</td>
											</tr>
                      <tr style="background-color:#dcf1fc;">
                        <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">Exam Password : <?php echo $record->pwd;?></td>
											</tr>
                      <?php if(($exam_code == 1037 || $exam_code == 1038 || $exam_code == 1046 || $exam_code == 1047) && $disp_unique_no != "")
                        { ?>
												<tr style="background-color:#dcf1fc;">
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;"><?php echo $disp_unique_no;?></td>
												</tr>
                        <?php }
                        else { ?>
												
												<?php $date = date_create($date); $created = date_format($date,"dmYHis"); //added by pooja mane 2-26-24?>
												<tr style="background-color:#dcf1fc;">
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;"><?php echo $created.' '.$transaction_no; ?> </td>
												</tr>
											<?php } ?>
										</tbody>
									</table></td>
								</tr>
								<tr>
									<td height="10"></td>
								</tr>
							</table>
							<!--table-2--></td>
						</tr>
					</table>
				<!--table-1--></td>
			</tr>
		</table>
		<!--main-table-->
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<?php if($exam_code != 101 && $exam_code != 1037 && $exam_code != 1038 && $exam_code != 1046 && $exam_code != 1047 && $exam_code != 996){?>
			
			<?php if($exam_period == 915 ){?>
				
				<table cellpadding="0" cellspacing="0" width="800" border="0" align="center" style="background-color:#fff;">
					<tr>
						<td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">IMPORTANT INSTRUCTIONS FOR CANDIDATES</td>
					</tr>
					<tr>
						<td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0;">
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td width="10" style="padding-left:15px;font-size:14px;">1.</td>
									<td><strong style="font-size:14px;">TIMINGS TO BE ADHERED BY THE CANDIDATES  :</strong></td>
								</tr><!--1.-->
								<tr>
									<td colspan="2" style="padding-left:30px;">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td colspan="2">
													
													<table border="1" cellspacing="0" cellpadding="5" width="650">
														<tr>
															<td ><strong>Activities &amp; Timings</strong></td>
															<td ><strong>Batch 1</strong></td>
															<td ><strong>Batch 2</strong></td>
															<td ><strong>Batch 3</strong></td>
														</tr>
														<tr>
															<td >Candidate Reporting at the venue of examination </td>
															<td >9.30</td>
															<td >12.00</td>
															<td >2.30</td>
														</tr>
														<tr>
															<td >Candidate Entry to computer Lab    </td>
															<td >9.30 to 9.45</td>
															<td >12.00 to 12.15</td>
															<td >2.30 to 2.45</td>
														</tr>
														<tr>
															<td >Gate Closing </td>
															<td >9.45</td>
															<td >12.15</td>
															<td >2.45</td>
														</tr>
														<tr>
															<td >Candidate Login start time for sample test</td>
															<td >9.50</td>
															<td >12.20</td>
															<td >2.50</td>
														</tr>
														<tr>
															<td >Exam Start Time </td>
															<td >10.00</td>
															<td >12.30</td>
															<td >3.00</td>
														</tr>
														<tr>
															<td >Exam Close Time</td>
															<td >12.00</td>
															<td >2.30</td>
															<td >5.00</td>
														</tr>
													</table>
													
													
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">a.</td>
												<td style="font-size:13px;">Candidates are advised to report to the Examination Venue as per the timing mentioned above.<strong> No candidate/s will be permitted to enter the Examination Venue/hall after the gate closing time for any reason whatsoever. Institute has instructed the examination conducting authorities of all the venues to strictly follow the timelines (CANDIDATE PLEASE NOTE)..</strong></td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">b.</td>
												<td style="font-size:13px;"><strong>No candidate will be permitted to leave the hall in the first 60 minutes from the scheduled start time of the examination.</strong> </td>
											</tr>
										</table>
									</td>
								</tr><!--Content of No.:1-->
								
								<tr>
									<td width="5" style="padding-left:15px;font-size:14px;">2.</td>
									<td><strong style="font-size:14px;">ADMIT LETTER OF EXAMINATIONS  :</strong></td>
								</tr><!--2.-->
								<tr>
									<td colspan="2" style="padding-left:30px;">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">a.</td>
												<td style="font-size:13px;">Candidates are required to <strong> produce printed copy of admit letter </strong> along with Membership identity card or any other valid photo ID card (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">b.</td>
												<td style="font-size:13px;"><strong>In the absence of printed copy of Admit Letter and Photo Identity Card, candidates will be denied permission to write Examination</strong></td>
												
												
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">c.</td>
												<td style="font-size:13px;">Admit letter is valid only for the examination, date/s and centre/venue mentioned in the admit letter.</td>
											</tr>
										</table>
									</td>
								</tr><!--Content of No.:2-->
								
								<tr>
									<td width="5" style="padding-left:15px;font-size:14px;">3.</td>
									<td><strong style="font-size:14px;">Frisking :</strong></td>
								</tr><!--2.-->
								<tr>
									<td colspan="2" style="padding-left:30px;">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">a.</td>
												<td style="font-size:13px;">Examination conducting authorities may do the frisking of candidates before entry to the examination hall/venue, to ensure that candidates do not carry items like mobile phone, any electronic/smart gadgets, other items which are not allowed in the examination hall. Candidates are required to co-operate with the examination conducting authorities. Candidates who do not co-operate for frisking activity will be denied entry to the examination hall/venue.</td>
											</tr>
										</table>
									</td>
								</tr><!--Content of No.:3-->
								
								
								<tr>
									<td width="5" style="padding-left:15px;font-size:14px;">4.</td>
									<td><strong style="font-size:14px;">Mobile Phones :</strong></td>
								</tr><!--4.-->
								<tr>
									<td colspan="2" style="padding-left:30px;">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">a.</td>
												<td style="font-size:13px;">Mobile phones and other electronic/smart gadgets (except calculator as permissible) are not allowed in the examination hall. It is clarified that mere possession of mobile phone and other electronic/smart gadgets in the examination hall whether in switch off mode or silent mode shall also be deemed to be resorting to adoption of unfair means in the examination.</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">b.</td>
												<td style="font-size:13px;"><strong>Institute will not make any arrangement for safe keep of Mobile Phones, electronic/smart gadgets, bags or any other item pertaining to the candidates.</strong></td>
											</tr>
										</table>
									</td>
								</tr><!--Content of No.:4-->
								
								<tr>
									<td width="5" style="padding-left:15px;font-size:14px;">5.</td>
									<td><strong style="font-size:14px;">Use of calculator :</strong></td>
								</tr><!--3.-->
								<tr>
									<td colspan="2" style="padding-left:30px;">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">a.</td>
												<td style="font-size:13px;">Candidates will be allowed to use battery operated portable calculator in the examination. The calculator can be of any type up to 8 functions i.e. (Addition, Subtraction, Multiplication, Division, Percentage, Sq.-root, Tax+ and Tax -), 12 digits.</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">b.</td>
												<td style="font-size:13px;">Attempt to use any other type of calculator not complying with the specifications indicated above or having more features than mentioned above shall tantamount to use of unfair means. Scientific/Financial calculator is not allowed
												</td>
											</tr>
										</table>
									</td>
								</tr><!--Content of No.:3-->
								
								
								<tr>
									<td width="5" style="padding-left:15px;font-size:14px;">6.</td>
									<td><strong style="font-size:14px;">Provisional Score Card/Result :</strong></td>
								</tr><!--3.-->
								<tr>
									<td colspan="2" style="padding-left:30px;">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">a.</td>
												<td style="font-size:13px;"><strong>After submitting of the question paper provisional score card/result will be displayed on the computer screen. This Score card will be sent to the Candidate's email ID as registered with the Institute. In addition to that, Score card can also be downloaded by the candidate from the Institute's website after 4 working days from the date of examination.</strong></td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">b.</td>
												<td style="font-size:13px;">Candidate can provide alternate email ID if he wishes to receive the score card on different email ID</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">c.</td>
												<td style="font-size:13px;">For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institute's website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same</td>
											</tr>
										</table>
									</td>
								</tr><!--Content of No.:3-->
								
								<tr>
									<td width="10" style="padding-left:15px;font-size:14px;">7.</td>
									<td><strong style="font-size:14px;">Rules, Penalities for Misconduct / Unfair Practices :</strong></td>
								</tr><!--4.-->
								<tr>
									<td colspan="2" style="padding-left:30px;">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">a.</td>
												<td style="font-size:13px;">Candidates would be able to login to the system only with the password mentioned in the Admit Letter. This password should not be disclosed to others. Keep it safe to avoid the possible misuse</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">b.</td>
												<td style="font-size:13px;">Candidates should ensure that they sign the Attendance Sheet.</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">c.</td>
												<td style="font-size:13px;">Candidates are required to strictly follow all the instructions given by the examination conducting authority during the examination and adhere to Rules of the examination.
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">d.</td>
												<td style="font-size:13px;">Candidates are not permitted to logout/switch-off the computer for the sake of going to washroom and if they log out/switch-off NO re-login, will be permitted. Further the candidates are advised that the time taken for going to the washroom would be inclusive of the duration of two hours permitted to them to answer the question paper.
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">e.</td>
												<td style="font-size:13px;">Candidates should not possess and / or use books, notes, periodicals, etc. in the examination hall at the time of examination / or use mathematical tables, slide rules, stencils etc. during the examination
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">f.</td>
												<td style="font-size:13px;">Communication of any sort between candidates or with outsiders is not permitted and complete silence should be maintained during the examination 
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">g.</td>
												<td style="font-size:13px;">Copying answers from other candidates/other printed/Electronic material or permitting others to copy or consultation of any kind will attract the rules relating to unfair practices in the examination.
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">h.</td>
												<td style="font-size:13px;">No candidate shall impersonate others or allow others to impersonate himself/herself at the examination.
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">i.</td>
												<td style="font-size:13px;">No candidate shall misbehave/argue with the Examination Conducting Authorities at the centre.
												</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">j.</td>
												<td style="font-size:13px;">Candidates have to compulsory return any papers given including that given for rough work to invigilator.
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">k.</td>
												<td style="font-size:13px;">If the examination could not commence on scheduled time or there is delay due to Failure of power, Technical snag of whatsoever nature or for any such reason having bearing upon the conduct of examination; candidates have to  :-
													<table>
														<tr>
															<td style="font-size:13px;">i.</td>
															<td style="font-size:13px;">Wait till resumption of power supply/solving of technical snag.</td>
														</tr>
														<tr>
															<td style="font-size:13px;">ii.</td>
															<td style="font-size:13px;">Take-up the examination at other venue arranged by the examination conducting authority.</td>
														</tr>
														<tr>
															<td style="font-size:13px;">iii.</td>
															<td style="font-size:13px;">Follow instructions given by the examination conducting authority.</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">l.</td>
												<td style="font-size:13px;">Candidates should not write Questions/Options etc. on the Admit Letter or use it like rough sheet. If Candidate is found doing so he/she shall be deemed to be resorting to adoption of unfair means in the examination.
												</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">m.</td>
												<td style="font-size:13px;"><strong>This examination is confidential. It is made available to the candidates solely for the purpose of assessing qualifications in the discipline referenced in the title of this examination. Candidates are expressly prohibited from disclosing, publishing, reproducing, or transmitting the questions/options of the examinations, in whole or in part, in any form or by any means, verbal or written, electronic or mechanical, for any purpose, without the prior express written permission from IIBF. Candidates found doing so, shall be considered as unlawful act and attract the rules relating to unfair practices.</strong>
												</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
												<td style="font-size:13px;">&nbsp;
												</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
												<td style="font-size:13px;">Violation of any of the Rules / Instructions, misuse of the Admit Letter will be considered to be an act of serious misconduct and the Institute will take action as per the Rules of the examination, which will also be reported to the employer of the candidate.
												</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
												<td style="font-size:13px;">&nbsp;
												</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
												<td style="font-size:13px;"><strong>PLEASE REFER INSTITUTE'S WEBSITE UNDER THE MENU "EXAM RELATED" FOR DETAILS OF DEBARMENT PERIOD FOR:</strong>
												</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;"><strong>1)</strong></td>
												<td style="font-size:13px;"><strong>UNFAIR PRACTICES ADOPTED BY CANDIDATES DURING CONDUCT OF INSTITUTE'S EXAMINATIONS</strong></td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;"><strong>2)</strong></td>
												<td style="font-size:13px;"><strong>INSTRUCTIONS/INFORMATION ABOUT ON-LINE EXAMINATIONS</strong></td>
											</tr>
											
										</table>
									</td>
								</tr><!--Content of No.:4-->
								
							</table>
						</td>
					</tr>
				</table>
				
				<table cellpadding="0" cellspacing="0" width="800" border="0" align="center" style="background-color:#fff;">
					<tr>
						<td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">IMPORTANT INSTRUCTIONS FOR CANDIDATES</td>
					</tr>
					<tr>
						<td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0;">
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td width="10" style="padding-left:15px;font-size:14px;">1.</td>
									<td><strong style="font-size:14px;">Guidelines to be followed by the candidates during the Examinations under COVID-19 Pandemic environment:</strong></td>
								</tr><!--1.-->
								<tr>
									<td colspan="2" style="padding-left:30px;">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td colspan="2">
												</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">1.</td>
												<td style="font-size:13px;">As a precautionary measure against COVID-19, candidates should reach the exam centre before the reporting time mentioned in the admit letter, to avoid any delay and  crowding at the exam centre at the time of entry and to maintain social distancing</td>
											</tr>
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">2.</td>
												<td style="font-size:13px;">Candidates should bring the printed admit letter along with hard copy valid photo identity proof (in original) along with duly completed Self Declaration Form given in Annexure – A</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">3.</td>
												<td style="font-size:13px;">The Self-Declaration Form printed and duly completed should be submitted to the Invigilator</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">4.</td>
												<td style="font-size:13px;">Candidates must maintain social distancing & wear mask starting from point of entry in the exam venue till the candidates exit from the exam venue. Candidates must follow the guidelines issued by Ministry of Health & Family Welfare, Govt of India from time to time relating to  Covid-19 Pandemic</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">5.</td>
												<td style="font-size:13px;">The body temperature of the candidates will be checked at the entranceof the exam venue using Thermal Gun and candidates who are detected with high body temperature may not be allowed entry for the examination</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">6.</td>
												<td style="font-size:13px;">Candidates will be frisked using a Handheld Metal Detector but no contact with the body would be ensured</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">7.</td>
												<td style="font-size:13px;">Candidates need to maintain a space of at least 01 meter from each other at all the time after reporting to exam venue and make use of Hand sanitizers and/or Hand Wash made available at the venue during the entire exam process and follow the instructions provided by centre staff</td>
											</tr>
											
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">8.</td>
												<td style="font-size:13px;">Do NOT bring any prohibited items (electronic gadget, mobile phone, scientific/financial calculator, metal instrument or any other unauthorized devices etc) to exam centre as there are no arrangements available for safe keeping of your belongings</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">9.</td>
												<td style="font-size:13px;">Candidates are advised to use stairs instead of lifts to avoid any risk of contact at the Examination Centre</td>
											</tr>
											
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">10.</td>
												<td style="font-size:13px;">Candidates would NOT be provided with any blank A4 size sheets for rough work at the exam centre, to avoid any risk due to contact with an infected person. However, the candidates may use the online rough sheet on the exam screen to do any rough work/ calculations during the examination</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">11.</td>
												<td style="font-size:13px;">Please note that candidates would NOT be provided with any hard copy Scorecard printout at the exam venue, after the examination. The scorecard will only be sent over mail to the registered e-mail ID of  the candidate's within  03 / 04 working days from the exam date</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">12.</td>
												<td style="font-size:13px;">After reporting to their respective desk, before start of exam, during exam and after completion of the exam, candidates must not leave their seats without the permission of the invigilator/centre staff. They must wait for the instructions</td>
											</tr>
											
											<tr>
												<td width="2%" valign="top" style="font-size:13px;">13.</td>
												<td style="font-size:13px;">Candidates are required to co-operate with the examination conducting authorities for conducting the examination smoothly under COVID-19 Pandemic environment</td>
											</tr>
											
											
										</table>
									</td>
								</tr><!--Content of No.:1-->
							</table>
						</td>
					</tr>
				</table>
				
				<div class="main-container" style="background:#fff; width:680px; border:1px solid #999; padding:30px; margin:600px auto; font-size:14px;">
					<table width="100%" border="0" cellspacing="5" cellpadding="5">
						
						<tr>
							<td style="text-align: center;"><h4 style="text-decoration: underline; margin-bottom:20px">SELF DECLARATION</h4></td>
						</tr>
						<tr>
							<td style="text-align: center;">(Duly filled Self-Declaration form to be submitted to the invigilator/centre staff)</td>
						</tr>
						<tr>
							<td style="text-align: center;">In the interest of your well-being and that of everyone at the venue. I declare the following</td>
						</tr>
					</table>
					
					<table width="100%" border="0" cellspacing="5" cellpadding="5">
						<tr>
							<td colspan="3"><strong style="font-weight:500; font-size:14px">1.	I am not experiencing any of the below issues/symptoms: </strong></td>
						</tr>
						<tr>
							<td width="150">Fever <span><input type="checkbox" name="checkbox" id="check1"></span></td>
							<td>Sore throat/Runny Nose  <span><input type="checkbox" name="checkbox" id="check2"></span></td>
							<td>Cough <span><input type="checkbox" name="checkbox" id="check3"></span></td>
						</tr>
						<tr>
							<td width="150">Body/Chest pain <span><input type="checkbox" name="checkbox" id="check4"></span></td>
							<td>Breathlessness <span><input type="checkbox" name="checkbox" id="check5"></span></td>
							<td>&nbsp;</td>
						</tr>
					</table>
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><strong style="font-weight:500; font-size:14px">2.	I have not been in closed contact with a person suffering from Covid-19 </strong> </td>
							<td width="50"><span><input type="checkbox" name="checkbox" id="contact"></span></td>
						</tr>	  
					</table>
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><strong style="font-weight:500; font-size:14px">3.	I am not under mandatory quarantine <br>(Due to close contact with a person suffering with Covid-19)</strong></td>
							<td width="50"><span><input type="checkbox" name="checkbox" id="quar"></span></td>
						</tr>	  
					</table>
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px;">
						
						<tr>
							<td width="30%" style="border: 1px solid #ccc; padding:6px">Candidate Name :</td>
							<td style="border: 1px solid #ccc; padding:6px"></td>
						</tr>
						<tr>
							<td style="border: 1px solid #ccc; padding:6px">Membership No :</td>
							<td style="border: 1px solid #ccc; padding:6px"></td>
						</tr>
						<tr>
							<td style="border: 1px solid #ccc; padding:6px">Subject/Module Name. :</td>
							<td style="border: 1px solid #ccc; padding:6px"></td>
						</tr>
						<tr>
							<td style="border: 1px solid #ccc; padding:6px">Date/Time of Exam :</td>
							<td style="border: 1px solid #ccc; padding:6px"></td>
						</tr>
						<tr>
							<td style="border: 1px solid #ccc; padding:6px">Name of the Exam Centre/Venue :</td>
							<td style="border: 1px solid #ccc; padding:6px"></td>
						</tr>
						<tr>
							<td style="border: 1px solid #ccc; padding:6px">Exam City :</td>
							<td style="border: 1px solid #ccc; padding:6px"></td>
						</tr>
						
					</table>
					
					<table width="100%" border="0" cellspacing="5" cellpadding="5" style="margin-top: 20px">
						<tr>
							<td>I may be subject to legal provisioning's/action's as applicable for hiding any facts on Covid-19 infections related to me and causing health hazards to others. </td>
						</tr>	 
						<tr>
							<td>I acknowledge that the information given above is accurate, complete and to the best of my knowledge. </td>
						</tr>
						
					</table>
					
					<table width="100%" border="0" cellspacing="5" cellpadding="5" style="margin-top: 20px">
						<tr>
							<td width="15%">Date : </td>
							<td><input type="text" name="date" style="border: 0 !important; border-bottom: 1px solid #999 !important; width:50%; height:30px; display:inline-block"></td>
						</tr>	 
						<tr>
							<td>Signature :  </td>
							<td><input type="text" name="signature" style="border: 0 !important; border-bottom: 1px solid #999 !important; width:50%; height:30px; display:inline-block"></td>
						</tr>	
						<tr>
							<td>Mobile no :</td>
							<td><input type="text" name="mobileno" style="border: 0 !important; border-bottom: 1px solid #999 !important; width:50%; height:30px; display:inline-block"></td>
						</tr>	
						
					</table>
					
					
					
				</div>
				
			<?php }?>
			
			<?php }elseif($exam_code == 101 || $exam_code == 1037 || $exam_code == 1038 || $exam_code == 1046 || $exam_code == 1047 || $exam_code == 1010){?>
			<table cellpadding="0" cellspacing="0" width="800" border="0" align="center" style="background-color:#fff;">
				<tr>
					<td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">IMPORTANT INSTRUCTIONS FOR CANDIDATES</td>
				</tr>
				<tr>
					<td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0;">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="10" style="padding-left:15px;font-size:14px;">1.</td>
								<td><strong style="font-size:14px;">TIMINGS TO BE ADHERED BY THE CANDIDATES  :</strong></td>
							</tr><!--1.-->
							<tr>
								<td colspan="2" style="padding-left:30px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td colspan="2">
												
												<table border="1" cellspacing="0" cellpadding="5" width="650">
													<tr>
														<td ><strong>Activities &amp; Timings</strong></td>
														<td ><strong>Batch    1</strong></td>
														<td ><strong>Batch    2</strong></td>
														<td ><strong>Batch    3</strong></td>
														<td ><strong>Batch    4</strong></td>
													</tr>
													<tr>
														<td >Candidate Reporting at the venue of examination </td>
														<td >8.30</td>
														<td >11.00</td>
														<td >1.30</td>
														<td >4.00</td>
													</tr>
													<tr>
														<td >Candidate Entry to computer Lab  </td>
														<td >8.30 to 8.45</td>
														<td >11.00 to 11.15</td>
														<td >1.30 to 1.45 </td>
														<td >4.00 to 4.15</td>
													</tr>
													<tr>
														<td >Gate Closing </td>
														<td >8.45</td>
														<td >11.15</td>
														<td >1.45</td>
														<td >4.15</td>
													</tr>
													<tr>
														<td >Candidate Login start time for sample    test</td>
														<td >8.50</td>
														<td >11.20</td>
														<td >1.50</td>
														<td >4.20</td>
													</tr>
													<tr>
														<td >Exam Start Time </td>
														<td >9.00</td>
														<td >11.30</td>
														<td >2.00</td>
														<td >4.30</td>
													</tr>
													<tr>
														<td >Exam Close Time</td>
														<td >11.00</td>
														<td >1.30</td>
														<td >4.00</td>
														<td >6.30</td>
													</tr>
												</table>
												
												
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Candidates are advised to report to the Examination Venue as per the timing mentioned above. <strong> No candidate/s will be permitted to enter the Examination Venue/hall after the gate closing time for any reason whatsoever. Institute has instructed the examination conducting authorities of all the venues to strictly follow the timelines (CANDIDATE PLEASE NOTE).</strong></td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;"><strong>No candidate will be permitted to leave the hall in the first 60 minutes from the scheduled start time of the examination</strong> </td>
										</tr>
									</table>
								</td>
							</tr><!--Content of No.:1-->
							<tr>
								<td width="10" style="padding-left:15px;font-size:14px;">2.</td>
								<td><strong style="font-size:14px;">ADMIT LETTER OF EXAMINATIONS  :</strong></td>
							</tr><!--1.-->
							<tr>
								<td colspan="2" style="padding-left:30px;"> 
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Candidates are required to <strong> produce printed copy of admit letter </strong> along with Membership identity card or any other valid photo ID card in original (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue. </td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;"><strong>In the absence of printed copy of Admit Letter and Original Photo Identity Card, candidates will be denied permission to write Examination.</strong> </td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">c.</td>
											<td style="font-size:13px;">Admit letter is valid only for the examination, date/s and centre/venue mentioned in the admit letter.</td>
										</tr>
									</table>
								</td>
							</tr><!--Content of No.:1-->
							<tr>
								<td width="5" style="padding-left:15px;font-size:14px;">3.</td>
								<td><strong style="font-size:14px;">Mobile Phones :</strong></td>
							</tr><!--2.-->
							<tr>
								<td colspan="2" style="padding-left:30px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Mobile phones and other electronic/smart gadgets (except calculator as permissible) are not allowed in the examination hall. It is clarified that mere possession of mobile phone and other electronic/smart gadgets in the examination hall whether in switch off mode or silent mode shall also be deemed to be resorting to adoption of unfair means in the examination.</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;"><strong>Institute will not make any arrangement for safe keep of Mobile Phones, electronic/smart gadgets, bags or any other item pertaining to the candidates.</strong></td>
										</tr>
									</table>
								</td>
							</tr><!--Content of No.:2-->
							<tr>
								<td width="5" style="padding-left:15px;font-size:14px;">4.</td>
								<td><strong style="font-size:14px;">Use of calculator :</strong></td>
							</tr><!--3.-->
							<tr>
								<td colspan="2" style="padding-left:30px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Candidates will be allowed to use battery operated portable calculator in the examination. The calculator can be of any type up to 8 functions i.e. (Addition, Subtraction, Multiplication, Division, Percentage, Sq.-root, Tax+ and Tax -), 12 digits.</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;">Attempt to use any other type of calculator not complying with the specifications indicated above or having more features than mentioned above shall tantamount to use of unfair means. Scientific/Financial calculator is not allowed.
											</td>
										</tr>
									</table>
								</td>
							</tr><!--Content of No.:3-->
							<!--<tr>
								<td width="5" style="padding-left:15px;font-size:14px;">5.</td>
								<td><strong style="font-size:14px;">Rough Sheet :</strong></td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:30px;">
								<table cellpadding="0" cellspacing="0" border="0">
								<tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;"><strong>For any rough work Candidate can press 'Rough Sheet' button on the computer screen during the examination for doing any rough work. Blank paper/rough sheet will not be provided to the candidate for rough work during the examination</strong></td>
								</tr>
								</table>
								</td>
							</tr>-->
							<tr>
								<td width="10" style="padding-left:15px;font-size:14px;">5.</td>
								<td><strong style="font-size:14px;">Rules, Penalities for Misconduct / Unfair Practices :</strong></td>
							</tr><!--4.-->
							<tr>
								<td colspan="2" style="padding-left:30px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Candidates would be able to login to the system only with the password mentioned in the Admit Letter. This password should not be disclosed to others. Keep it safe to avoid the possible misuse.</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;">Candidates should ensure that they sign the Attendance Sheet.</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">c.</td>
											<td style="font-size:13px;">Candidates are required to strictly follow all the instructions given by the examination conducting authority during the examination and adhere to Rules of the examination.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">d.</td>
											<td style="font-size:13px;">Candidates are not permitted to logout/switch-off the computer for the sake of going to washroom and if they log out/switch-off NO re-login, will be permitted. Further the candidates are advised that the time taken for going to the washroom would be inclusive of the duration of two hours permitted to them to answer the question paper.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">e.</td>
											<td style="font-size:13px;">Candidates should not possess and / or use books, notes, periodicals, etc. in the examination hall at the time of examination / or use mathematical tables, slide rules, stencils etc. during the examination.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">f.</td>
											<td style="font-size:13px;">Communication of any sort between candidates or with outsiders is not permitted and complete silence should be maintained during the examination. 
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">g.</td>
											<td style="font-size:13px;">Copying answers from other candidates/other printed/Electronic material or permitting others to copy or consultation of any kind will attract the rules relating to unfair practices in the examination.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">h.</td>
											<td style="font-size:13px;">No candidate shall impersonate others or allow others to impersonate himself/herself at the examination.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">i.</td>
											<td style="font-size:13px;">No candidate shall misbehave/argue with the Examination Conducting Authorities at the centre.
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">j.</td>
											<td style="font-size:13px;">Candidates have to compulsory return any papers given including that given for rough work to invigilator.
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">k.</td>
											<td style="font-size:13px;">If the examination could not commence on scheduled time or there is delay due to Failure of power, Technical snag of whatsoever nature or for any such reason having bearing upon the conduct of examination; candidates have to  :-
												<table>
													<tr>
														<td style="font-size:13px;">i.</td>
                            <td style="font-size:13px;">Wait till resumption of power supply/solving of technical snag.</td>
													</tr>
													<tr>
														<td style="font-size:13px;">ii.</td>
                            <td style="font-size:13px;">Take-up the examination at other venue arranged by the examination conducting authority.</td>
													</tr>
													<tr>
														<td style="font-size:13px;">iii.</td>
                            <td style="font-size:13px;">Follow instructions given by the examination conducting authority.</td>
													</tr>
												</table>
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">l.</td>
											<td style="font-size:13px;">For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institute's website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">m.</td>
											<td style="font-size:13px;">Candidates should not write Questions/Options etc. on the Admit Letter or use it like rough sheet. If Candidate is found doing so he/she shall be deemed to be resorting to adoption of unfair means in the examination.
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">n.</td>
											<td style="font-size:13px;"><strong>This examination is confidential. It is made available to the candidates solely for the purpose of assessing qualifications in the discipline referenced in the title of this examination. Candidates are expressly prohibited from disclosing, publishing, reproducing, or transmitting the questions/options of the examinations, in whole or in part, in any form or by any means, verbal or written, electronic or mechanical, for any purpose, without the prior express written permission from IIBF. Candidates found doing so, shall be considered as unlawful act and attract the rules relating to unfair practices.</strong>
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
											<td style="font-size:13px;">&nbsp;
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
											<td style="font-size:13px;">Violation of any of the Rules / Instructions, misuse of the Admit Letter will be considered to be an act of serious misconduct and the Institute will take action as per the Rules of the examination, which will also be reported to the employer of the candidate.
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
											<td style="font-size:13px;">&nbsp;
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
											<td style="font-size:13px;"><strong>PLEASE REFER INSTITUTE'S WEBSITE UNDER THE MENU "EXAM RELATED" FOR DETAILS OF DEBARMENT PERIOD FOR.</strong>
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;"><strong>1)</strong></td>
											<td style="font-size:13px;"><strong>UNFAIR PRACTICES ADOPTED BY CANDIDATES DURING CONDUCT OF INSTITUTE'S EXAMINATIONS</strong></td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;"><strong>2)</strong></td>
											<td style="font-size:13px;"><strong>INSTRUCTIONS/INFORMATION ABOUT ON-LINE EXAMINATIONS</strong></td>
										</tr>
										
									</table>
								</td>
							</tr><!--Content of No.:4-->
							
						</table>
					</td>
				</tr>
			</table>
			
			
			
			
			<table cellpadding="0" cellspacing="0" width="800" border="0" align="center" style="background-color:#fff;">
				<tr>
					<td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">IMPORTANT INSTRUCTIONS FOR CANDIDATES</td>
				</tr>
				<tr>
					<td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0;">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="10" style="padding-left:15px;font-size:14px;">1.</td>
								<td><strong style="font-size:14px;">Guidelines to be followed by the candidates during the Examinations under COVID-19 Pandemic environment:</strong></td>
							</tr><!--1.-->
							<tr>
								<td colspan="2" style="padding-left:30px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td colspan="2">
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">1.</td>
											<td style="font-size:13px;">As a precautionary measure against COVID-19, candidates should reach the exam centre before the reporting time mentioned in the admit letter, to avoid any delay and  crowding at the exam centre at the time of entry and to maintain social distancing</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">2.</td>
											<td style="font-size:13px;">Candidates should bring the printed admit letter along with hard copy valid photo identity proof (in original) along with duly completed Self Declaration Form given in Annexure – A</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">3.</td>
											<td style="font-size:13px;">The Self-Declaration Form printed and duly completed should be submitted to the Invigilator</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">4.</td>
											<td style="font-size:13px;">Candidates must maintain social distancing & wear mask starting from point of entry in the exam venue till the candidates exit from the exam venue. Candidates must follow the guidelines issued by Ministry of Health & Family Welfare, Govt of India from time to time relating to  Covid-19 Pandemic</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">5.</td>
											<td style="font-size:13px;">The body temperature of the candidates will be checked at the entranceof the exam venue using Thermal Gun and candidates who are detected with high body temperature may not be allowed entry for the examination</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">6.</td>
											<td style="font-size:13px;">Candidates will be frisked using a Handheld Metal Detector but no contact with the body would be ensured</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">7.</td>
											<td style="font-size:13px;">Candidates need to maintain a space of at least 01 meter from each other at all the time after reporting to exam venue and make use of Hand sanitizers and/or Hand Wash made available at the venue during the entire exam process and follow the instructions provided by centre staff</td>
										</tr>
										
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">8.</td>
											<td style="font-size:13px;">Do NOT bring any prohibited items (electronic gadget, mobile phone, scientific/financial calculator, metal instrument or any other unauthorized devices etc) to exam centre as there are no arrangements available for safe keeping of your belongings</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">9.</td>
											<td style="font-size:13px;">Candidates are advised to use stairs instead of lifts to avoid any risk of contact at the Examination Centre</td>
										</tr>
										
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">10.</td>
											<td style="font-size:13px;">Candidates would NOT be provided with any blank A4 size sheets for rough work at the exam centre, to avoid any risk due to contact with an infected person. However, the candidates may use the online rough sheet on the exam screen to do any rough work/ calculations during the examination</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">11.</td>
											<td style="font-size:13px;">Please note that candidates would NOT be provided with any hard copy Scorecard printout at the exam venue, after the examination. The scorecard will only be sent over mail to the registered e-mail ID of  the candidate's within  03 / 04 working days from the exam date</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">12.</td>
											<td style="font-size:13px;">After reporting to their respective desk, before start of exam, during exam and after completion of the exam, candidates must not leave their seats without the permission of the invigilator/centre staff. They must wait for the instructions</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">13.</td>
											<td style="font-size:13px;">Candidates are required to co-operate with the examination conducting authorities for conducting the examination smoothly under COVID-19 Pandemic environment</td>
										</tr>
										
										
									</table>
								</td>
							</tr><!--Content of No.:1-->
						</table>
					</td>
				</tr>
			</table>
			
			
			<div class="main-container" style="background:#fff; width:680px; border:1px solid #999; padding:30px; margin:600px auto; font-size:14px;">
				<table width="100%" border="0" cellspacing="5" cellpadding="5">
					
					<tr>
						<td style="text-align: center;"><h4 style="text-decoration: underline; margin-bottom:20px">SELF DECLARATION</h4></td>
					</tr>
					<tr>
						<td style="text-align: center;">(Duly filled Self-Declaration form to be submitted to the invigilator/centre staff)</td>
					</tr>
					<tr>
						<td style="text-align: center;">In the interest of your well-being and that of everyone at the venue. I declare the following</td>
					</tr>
				</table>
				
				<table width="100%" border="0" cellspacing="5" cellpadding="5">
					<tr>
						<td colspan="3"><strong style="font-weight:500; font-size:14px">1.	I am not experiencing any of the below issues/symptoms: </strong></td>
					</tr>
					<tr>
						<td width="150">Fever <span><input type="checkbox" name="checkbox" id="check1"></span></td>
						<td>Sore throat/Runny Nose  <span><input type="checkbox" name="checkbox" id="check2"></span></td>
						<td>Cough <span><input type="checkbox" name="checkbox" id="check3"></span></td>
					</tr>
					<tr>
						<td width="150">Body/Chest pain <span><input type="checkbox" name="checkbox" id="check4"></span></td>
						<td>Breathlessness <span><input type="checkbox" name="checkbox" id="check5"></span></td>
						<td>&nbsp;</td>
					</tr>
				</table>
				
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><strong style="font-weight:500; font-size:14px">2.	I have not been in closed contact with a person suffering from Covid-19 </strong> </td>
						<td width="50"><span><input type="checkbox" name="checkbox" id="contact"></span></td>
					</tr>	  
				</table>
				
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><strong style="font-weight:500; font-size:14px">3.	I am not under mandatory quarantine <br>(Due to close contact with a person suffering with Covid-19)</strong></td>
						<td width="50"><span><input type="checkbox" name="checkbox" id="quar"></span></td>
					</tr>	  
				</table>
				
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px;">
					
					<tr>
						<td width="30%" style="border: 1px solid #ccc; padding:6px">Candidate Name :</td>
						<td style="border: 1px solid #ccc; padding:6px"></td>
					</tr>
					<tr>
						<td style="border: 1px solid #ccc; padding:6px">Membership No :</td>
						<td style="border: 1px solid #ccc; padding:6px"></td>
					</tr>
					<tr>
						<td style="border: 1px solid #ccc; padding:6px">Subject/Module Name. :</td>
						<td style="border: 1px solid #ccc; padding:6px"></td>
					</tr>
					<tr>
						<td style="border: 1px solid #ccc; padding:6px">Date/Time of Exam :</td>
						<td style="border: 1px solid #ccc; padding:6px"></td>
					</tr>
					<tr>
						<td style="border: 1px solid #ccc; padding:6px">Name of the Exam Centre/Venue :</td>
						<td style="border: 1px solid #ccc; padding:6px"></td>
					</tr>
					<tr>
						<td style="border: 1px solid #ccc; padding:6px">Exam City :</td>
						<td style="border: 1px solid #ccc; padding:6px"></td>
					</tr>
					
				</table>
				
				<table width="100%" border="0" cellspacing="5" cellpadding="5" style="margin-top: 20px">
					<tr>
						<td>I may be subject to legal provisioning's/action's as applicable for hiding any facts on Covid-19 infections related to me and causing health hazards to others. </td>
					</tr>	 
					<tr>
						<td>I acknowledge that the information given above is accurate, complete and to the best of my knowledge. </td>
					</tr>
					
				</table>
				
				<table width="100%" border="0" cellspacing="5" cellpadding="5" style="margin-top: 20px">
					<tr>
						<td width="15%">Date : </td>
						<td><input type="text" name="date" style="border: 0 !important; border-bottom: 1px solid #999 !important; width:50%; height:30px; display:inline-block"></td>
					</tr>	 
					<tr>
						<td>Signature :  </td>
						<td><input type="text" name="signature" style="border: 0 !important; border-bottom: 1px solid #999 !important; width:50%; height:30px; display:inline-block"></td>
					</tr>	
					<tr>
						<td>Mobile no :</td>
						<td><input type="text" name="mobileno" style="border: 0 !important; border-bottom: 1px solid #999 !important; width:50%; height:30px; display:inline-block"></td>
					</tr>	
					
				</table>
				
				
				
			</div>
			
			
			
			<?php }elseif($exam_code == 996){?>
			<table cellpadding="0" cellspacing="0" width="800" border="0" align="center" style="background-color:#fff;">
				<tr>
					<td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">IMPORTANT INSTRUCTIONS FOR CANDIDATES</td>
				</tr>
				<tr>
					<td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0;">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="10" style="padding-left:15px;font-size:14px;">1.</td>
								<td><strong style="font-size:14px;">Candidates are requested to reach the exam venue 30 minutes before the exam Time@ indicated above</strong></td>
							</tr><!--1.-->
							<!--Content of No.:1-->
							<tr>
								<td width="10" style="padding-left:15px;font-size:14px;">2.</td>
								<td><strong style="font-size:14px;">ADMIT LETTER OF EXAMINATIONS  :</strong></td>
							</tr><!--1.-->
							<tr>
								<td colspan="2" style="padding-left:30px;"> 
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Candidates are required to <strong> produce printed copy of admit letter </strong> along with Membership identity card or any other valid photo ID card in original (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue. </td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;"><strong>In the absence of printed copy of Admit Letter and Original Photo Identity Card, candidates will be denied permission to write Examination.</strong> </td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">c.</td>
											<td style="font-size:13px;">Admit letter is valid only for the examination, date/s and centre/venue mentioned in the admit letter.</td>
										</tr>
									</table>
								</td>
							</tr><!--Content of No.:1-->
							<tr>
								<td width="5" style="padding-left:15px;font-size:14px;">3.</td>
								<td><strong style="font-size:14px;">Mobile Phones :</strong></td>
							</tr><!--2.-->
							<tr>
								<td colspan="2" style="padding-left:30px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Mobile phones and other electronic/smart gadgets (except calculator as permissible) are not allowed in the examination hall. It is clarified that mere possession of mobile phone and other electronic/smart gadgets in the examination hall whether in switch off mode or silent mode shall also be deemed to be resorting to adoption of unfair means in the examination.</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;"><strong>Institute will not make any arrangement for safe keep of Mobile Phones, electronic/smart gadgets, bags or any other item pertaining to the candidates.</strong></td>
										</tr>
									</table>
								</td>
							</tr><!--Content of No.:2-->
							<tr>
								<td width="5" style="padding-left:15px;font-size:14px;">4.</td>
								<td><strong style="font-size:14px;">Use of calculator :</strong></td>
							</tr><!--3.-->
							<tr>
								<td colspan="2" style="padding-left:30px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Candidates will be allowed to use battery operated portable calculator in the examination. The calculator can be of any type up to 8 functions i.e. (Addition, Subtraction, Multiplication, Division, Percentage, Sq.-root, Tax+ and Tax -), 12 digits.</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;">Attempt to use any other type of calculator not complying with the specifications indicated above or having more features than mentioned above shall tantamount to use of unfair means. Scientific/Financial calculator is not allowed.
											</td>
										</tr>
									</table>
								</td>
							</tr><!--Content of No.:3-->
							<!--<tr>
								<td width="5" style="padding-left:15px;font-size:14px;">5.</td>
								<td><strong style="font-size:14px;">Rough Sheet :</strong></td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:30px;">
								<table cellpadding="0" cellspacing="0" border="0">
								<tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;"><strong>For any rough work Candidate can press 'Rough Sheet' button on the computer screen during the examination for doing any rough work. Blank paper/rough sheet will not be provided to the candidate for rough work during the examination</strong></td>
								</tr>
								</table>
								</td>
							</tr>-->
							<tr>
								<td width="10" style="padding-left:15px;font-size:14px;">5.</td>
								<td><strong style="font-size:14px;">Rules, Penalities for Misconduct / Unfair Practices :</strong></td>
							</tr><!--4.-->
							<tr>
								<td colspan="2" style="padding-left:30px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">a.</td>
											<td style="font-size:13px;">Candidates would be able to login to the system only with the password mentioned in the Admit Letter. This password should not be disclosed to others. Keep it safe to avoid the possible misuse.</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">b.</td>
											<td style="font-size:13px;">Candidates should ensure that they sign the Attendance Sheet.</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">c.</td>
											<td style="font-size:13px;">Candidates are required to strictly follow all the instructions given by the examination conducting authority during the examination and adhere to Rules of the examination.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">d.</td>
											<td style="font-size:13px;">Candidates are not permitted to logout/switch-off the computer for the sake of going to washroom and if they log out/switch-off NO re-login, will be permitted. Further the candidates are advised that the time taken for going to the washroom would be inclusive of the duration of two hours permitted to them to answer the question paper.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">e.</td>
											<td style="font-size:13px;">Candidates should not possess and / or use books, notes, periodicals, etc. in the examination hall at the time of examination / or use mathematical tables, slide rules, stencils etc. during the examination.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">f.</td>
											<td style="font-size:13px;">Communication of any sort between candidates or with outsiders is not permitted and complete silence should be maintained during the examination. 
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">g.</td>
											<td style="font-size:13px;">Copying answers from other candidates/other printed/Electronic material or permitting others to copy or consultation of any kind will attract the rules relating to unfair practices in the examination.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">h.</td>
											<td style="font-size:13px;">No candidate shall impersonate others or allow others to impersonate himself/herself at the examination.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">i.</td>
											<td style="font-size:13px;">No candidate shall misbehave/argue with the Examination Conducting Authorities at the centre.
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">j.</td>
											<td style="font-size:13px;">Candidates have to compulsory return any papers given including that given for rough work to invigilator.
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">k.</td>
											<td style="font-size:13px;">If the examination could not commence on scheduled time or there is delay due to Failure of power, Technical snag of whatsoever nature or for any such reason having bearing upon the conduct of examination; candidates have to  :-
												<table>
													<tr>
														<td style="font-size:13px;">i.</td>
                            <td style="font-size:13px;">Wait till resumption of power supply/solving of technical snag.</td>
													</tr>
													<tr>
														<td style="font-size:13px;">ii.</td>
                            <td style="font-size:13px;">Take-up the examination at other venue arranged by the examination conducting authority.</td>
													</tr>
													<tr>
														<td style="font-size:13px;">iii.</td>
                            <td style="font-size:13px;">Follow instructions given by the examination conducting authority.</td>
													</tr>
												</table>
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">l.</td>
											<td style="font-size:13px;">For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institute's website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same.
											</td>
										</tr>
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">m.</td>
											<td style="font-size:13px;">Candidates should not write Questions/Options etc. on the Admit Letter or use it like rough sheet. If Candidate is found doing so he/she shall be deemed to be resorting to adoption of unfair means in the examination.
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">n.</td>
											<td style="font-size:13px;"><strong>This examination is confidential. It is made available to the candidates solely for the purpose of assessing qualifications in the discipline referenced in the title of this examination. Candidates are expressly prohibited from disclosing, publishing, reproducing, or transmitting the questions/options of the examinations, in whole or in part, in any form or by any means, verbal or written, electronic or mechanical, for any purpose, without the prior express written permission from IIBF. Candidates found doing so, shall be considered as unlawful act and attract the rules relating to unfair practices.</strong>
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
											<td style="font-size:13px;">&nbsp;
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
											<td style="font-size:13px;">Violation of any of the Rules / Instructions, misuse of the Admit Letter will be considered to be an act of serious misconduct and the Institute will take action as per the Rules of the examination, which will also be reported to the employer of the candidate.
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
											<td style="font-size:13px;">&nbsp;
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
											<td style="font-size:13px;"><strong>PLEASE REFER INSTITUTE'S WEBSITE UNDER THE MENU "EXAM RELATED" FOR DETAILS OF DEBARMENT PERIOD FOR.</strong>
											</td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;"><strong>1)</strong></td>
											<td style="font-size:13px;"><strong>UNFAIR PRACTICES ADOPTED BY CANDIDATES DURING CONDUCT OF INSTITUTE'S EXAMINATIONS</strong></td>
										</tr>
										
										<tr>
											<td width="2%" valign="top" style="font-size:13px;"><strong>2)</strong></td>
											<td style="font-size:13px;"><strong>INSTRUCTIONS/INFORMATION ABOUT ON-LINE EXAMINATIONS</strong></td>
										</tr>
										
									</table>
								</td>
							</tr><!--Content of No.:4-->
							
						</table>
					</td>
				</tr>
			</table>
		<?php }?>
	</body>
</html>