<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view('google_analytics_script_common'); ?>
		<meta charset="utf-8">
		<title>Welcome to IIBF</title>
	</head>

	<body class="main-body" style="background-color:#fff; margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">
		<?php 
			$caiib = array($this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
			$jaiibarr = array('210','420');
      
		?>
		
    <table cellpadding="0" cellspacing="0" width="800" border="0" align="center">
			<tr>
				<td style="background-color:#fff;">
					<!--table-1-->
					<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
						<tr>
							<td style="border:1px solid #1287c0; padding:5px;">
								<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
									<tr>
										<td align="center">
											<img src="<?php echo base_url();?>assets/images/admit_logo.jpg" width="400" height="66" />
											<?php if(!in_array($exam_code,$caiib) && !in_array($exam_code,$jaiibarr)){
											?>
											<!-- <img src="<?php echo base_url()?>assets/images/ninty_year_new.png" width="70" height="70" style="margin-left: 100px; margin-right: -190px;" /> -->
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td height="5"></td>
									</tr>
									<tr>
										<td style="background-color:#1287c0; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:10px 0;">
											<?php
                      $dipcert = array(8,11,18,19,119,24,25,26,78,79,151,153,154,156,157,158,162,163,165,166,$this->config->item('examCodeSOB'));
                      if(!in_array($exam_code,$caiib))
                      {												
                        if($exam_code == 101)
                        {
                          echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS - ".$examdate;
                        }
                        elseif($exam_code == 991  || $exam_code == 997)
                        { // || $exam_code == 1052 || $exam_code == 1053
                          echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENT";
                        }
                        elseif($exam_code == 210 || $exam_code == 420 )
                        {
                          echo "Admit letter for JAIIB/DBF Nov Dec, 2025 examination";
                        }
                        else
                        { ?>
                          Admit Letter for <?php echo $member_result->mode?> <?php echo $exam_name;?>
                          <?php if($exam_code != 101 && $exam_code != 991 && $exam_code != 997 && $exam_code != 1052 && $exam_code != 1053){ echo "Examination"; }?>
                          - 
                          <?php echo $examdate;?>
                        <?php } 
                      }
                      
                      $elective = array("62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
                      if($examdate=='Nov 2022')
                      {
                        $examdate='Nov/Dec 2022';
                      }
                      if($exam_code == $this->config->item('examCodeCaiib'))
                      {
                        echo "Admit letter for Online CAIIB Examination – Nov/Dec 2025";
                        //echo "Admit Letters for CAIIB - August 2024";
                      }
                      if(in_array($exam_code,$elective))
                      {
                        echo "Admit letter for Online CAIIB Electives - Nov/Dec 2025";
                      }
                      
                      if($this->router->fetch_method()=='getadmitcardsp_new' || $this->router->fetch_method()=='getadmitcardjd' || $this->router->fetch_method()=='getadmitcardjdres') 
                      { ?>
                        <div align="right">
                          <?php if($this->router->fetch_method()=='getadmitcardsp_new') {
                          ?>
                          <a id="save_pdf" href="<?php echo base_url();?>Admitcard/getadmitcardpdfsp_new/<?php echo base64_encode($exam_code)?>/<?php echo $mem_exam_id;?>" target="_blank" style="color:#F00">Save as pdf</a>&nbsp;
                          <?php
                            } else if($this->router->fetch_method()=='getadmitcardjdres') {
                              ?>
                              <a id="save_pdf" href="<?php echo base_url();?>Admitcard/getadmitcardpdfjd/<?php echo base64_encode($exam_code)?>/<?php echo $mem_exam_id;?>" target="_blank" style="color:#F00">Save as pdf</a>&nbsp;
                              <?php
                                } else {
                          ?>
                          <a  id="save_pdf" href="<?php echo base_url();?>Admitcard/getadmitcardpdfjd/<?php echo base64_encode($exam_code)?>/<?php echo $mem_exam_id;?>" target="_blank" style="color:#F00">Save as pdf</a>&nbsp;
                          <?php
                          } ?>
                          &nbsp;
                          <a href="<?php echo base_url()?>admitcard/logout" style="color:#F00">Logout</a>
                        </div>
                      <?php } ?>
										</td>
									</tr>
									<tr>
										<td height="5"></td>
									</tr>
									<tr>
										<td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:7px 0;">Candidate Details</td>
									</tr>
									<tr>
										<td style="background-color:#dcf1fc; padding:7px 0;">
											<table cellpadding="0" cellspacing="0" width="100%" border="0">
												<tr>
													<td style="padding:0 10px;">
														<table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-left:1px solid #fff; border-top:1px solid #fff;">
															<tr>
																<td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
																	<strong>Membership / Registration No. : <?php echo $member_result->mem_mem_no;?></strong>
																	<?php if(!empty($instituteDetails) && $instituteDetails[0]['associatedinstitute']!='') { ?>
																		<div style="margin-top: 10px;"> <b>Institute:</b>&nbsp; <?php echo $instituteDetails[0]['associatedinstitute'];?> 
																		</div>
																		<?php } ?> <!-- priyanka d - 
																	</td>-feb-23 -->
																</td>
															</tr>
															<tr>
																<td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">
																	<?php echo $member_result->mam_nam_1;?>
																	&nbsp;&nbsp; <b>DOB:</b>&nbsp; <?php echo date('d-m-Y',strtotime($memberDetails[0]['dateofbirth']));?>  <!-- priyanka d - 
																	</td>-feb-23 -->
																</td>
															</tr>
															<tr>
																<td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase;">
																	<?php if($member_result->mem_adr_1 != ''){?>
																		<?php echo $member_result->mem_adr_1."<br/> ";?>
																	<?php }?>
																	<?php if($member_result->mem_adr_2 != ''){?>
																		<?php echo $member_result->mem_adr_2."<br/> ";?>
																	<?php }?>
																	<?php if($member_result->mem_adr_3 != '' && ($member_result->mem_adr_3 != $member_result->mem_adr_2)){?>
																		<?php echo $member_result->mem_adr_3."<br/>";?>
																	<?php }?>
																	<?php if($member_result->mem_adr_4 != '' && ($member_result->mem_adr_4 != $member_result->mem_adr_3)){?>
																		<?php echo str_replace(";","",$member_result->mem_adr_4)."<br/> ";?>
																	<?php }?>
																	<?php if($member_result->mem_adr_5 != '' && ($member_result->mem_adr_5 != $member_result->mem_adr_4)){?>
																		<?php echo str_replace(";","",$member_result->mem_adr_5)."<br/> ";?>
																	<?php }?>
																	<?php if($member_result->mem_pin_cd != '' && strlen($member_result->mem_pin_cd) == 6){?>
																		<?php echo "Pincode: ". str_replace(";","",$member_result->mem_pin_cd)."<br/>";?>
																	<?php }?>
																</td>
															</tr>
															<tr>
																<td>
																	<table>
																		<tr>
																			<td style="border:0px solid #333; padding:7px; text-transform:uppercase;"><img src="<?php echo base_url();?>assets/images/phone-icon.png" width="220"  alt="Phone" style="float:left; margin-top:8px; margin-right:15px;"  />
																				<div style="margin-top: 84px;">
																					<?php 
																						if(isset($optFlgRecord)){
																							if($optFlgRecord=='F')
																							echo'You have opted Forgo Credits and register de-novo';
																							if( $optFlgRecord=='R')
																							echo'You have opted Avail credits(as applicable) with Balance attempts';
																						}
																					?>
																				</div>
																			</td>
																			<td>
                                      <?php
                                          if(in_array($exam_code,$caiib) || in_array($exam_code,$jaiibarr) || in_array($exam_code,$dipcert) ) {
                                        ?>
                                       
										  <table style="border:1px solid black;padding:10px;">
                                          <tr><td><b>Medium :</b> </td><td><?php echo $medium;?></td></tr>
                                          <tr><td><b>Center :</b> </td><td><?php echo $center;?></td></tr>
                                          </table>
                                        <?php } ?>

                                      </td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table><!--table-5-->
													</td>
													<td align="center">
														<table cellpadding="0" cellspacing="0" border="0">
															<tr>
																<td>
																	<?php $this->master_model->resize_admitcard_images(get_img_name($member_id,'p')); ?>
																	<img src="<?php echo base_url();?><?php echo get_img_name($member_id,'p');?>" width="100" height="125" />
																</td>
															</tr>
                              <tr>
																<td height="5"></td>
															</tr>
                              <tr>
																<td>
																	<?php $this->master_model->resize_admitcard_images(get_img_name($member_id,'s')); ?>
																	<img src="<?php echo base_url();?><?php echo get_img_name($member_id,'s');?>" width="100" height="50" />
																</td>
															</tr>
														</table><!--table-4-->
													</td>
												</tr>
											</table><!--table-3-->
										</td>
									</tr>
									<tr>
										<td height="5"></td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" border="0" width="100%"  style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
												<thead>
													<tr style="background-color:#7fd1ea;">
														<th style="width:35%;text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Subject Name</th>
                            <?php
                                if(!in_array($exam_code,$caiib) && !in_array($exam_code,$jaiibarr) && !in_array($exam_code,$dipcert)) {
                              ?>
														<th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Medium</th>
                            <?php } ?>
														<th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Date</th>
														<?php if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') || in_array($exam_code,$caiib) || in_array($exam_code,$dipcert)){
															//priyanka d - 20-feb-23
														?>
														<th  style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Reporting Time<sup>@</sup></th>
														<?php } ?>
														<th  style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Exam Time</th>
														<th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
														<th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;" >Seat Number *</th>
														<?php if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') || in_array($exam_code,$caiib) || in_array($exam_code,$dipcert)){
															//priyanka d - 20-feb-23
														?>
														<th  style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;" >Verification Stamp</th>
														<?php } ?>
													</tr>
												</thead> 
												<tbody>
													<?php 
														// echo "<pre>";
														// print_r($subject); exit;
														foreach($subject as $subject){
														?>
														<tr style="background-color:#dcf1fc;">
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subject->subject_description;?></td>
                              <?php
                                if(!in_array($exam_code,$caiib) && !in_array($exam_code,$jaiibarr) && !in_array($exam_code,$dipcert)) {
                              ?>
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $medium;?></td>
                              <?php } ?>
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">
																<?php
																	$exam_print_date = explode("-",$subject->exam_date);
																	$edate = $exam_print_date[0]."-".$exam_print_date[1]."-".$exam_print_date[2];
																	echo date('d-M-Y',strtotime($edate));
																	$curr_exam_time = date('H:i:s',strtotime($subject->time));
																	//echo $subject->exam_date;
																?>
															</td>
															<?php if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') || in_array($exam_code,$caiib) || in_array($exam_code,$dipcert)){
																//priyanka d - 20-feb-23
															?>
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo date("g:i A",strtotime($curr_exam_time) - 1800);;
															?></td>
															<?php } ?>
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->time;?></td>
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->venueid;?></td>
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">
																<?php
																	$member_array = array();
																	if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') || in_array($exam_code,$caiib) || in_array($exam_code,$dipcert)){
																		echo '';  //priyanka d - 20-feb-23
																		}else{
																		if(in_array($member_id,$member_array)){
																			echo 'NA';
																			}else{
																			echo $subject->seat_identification;
																		}
																	}
																?>
															</td>
															<?php if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') || in_array($exam_code,$caiib) || in_array($exam_code,$dipcert)){ ?>
																<td  style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"></td>
															<?php } //priyanka d - 20-feb-23 ?> 
														</tr>
													<?php }?>  
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td style="text-align:right; line-height:24px; font-size:13px;">
											<?php if($exam_code!=34 && $exam_code!=160 && $exam_code!=58 && $exam_code!=101){?>
												<?php
													if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') || in_array($exam_code,$elective) || in_array($exam_code,$dipcert)) {   //priyanka d - 20-feb-23
													?>
													*Seat No. will be allotted at examination hall &nbsp;
												<?php } ?>
												@Please adhere to the Reporting time mentioned above. No candidate/s will be permitted to enter the Exam Venue/Hall later than the Entry close time for any reason whatsoever. ” 
												
											<?php }?>
										</td>
									</tr>
									<tr>
                    <td height="10" style="color:red;"><strong>#Candidates are advised to check Institute's Website, a day before the Examination Date, for any Information/Notice or Change in Examination Venue.</strong></td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
												<thead>
													<tr style="background-color:#7fd1ea;">
														<th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
														<th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"> <?php if($selected_vendor=='nseit') echo'NSEIT Venue Address#';
															else if($selected_vendor=='csc') echo'CSC Venue Address#';
														else echo'Venue Address#'; ?></th>
													</tr>
												</thead>
												<tbody>
													<?php  foreach($venue_result as $venue_result){?>
														<tr style="background-color:#dcf1fc;">
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $venue_result->venueid;?></td>
															<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;">
																<?php echo $venue_result->venue_name;?>&nbsp;
																<?php echo $venue_result->venueadd1;?>&nbsp;
																<?php echo $venue_result->venueadd2;?>&nbsp;
																<?php 
																	if($venue_result->venueadd3 != $venue_result->venueadd2){
																		echo $venue_result->venueadd3;
																	}
																?>&nbsp;
																<?php 
																	if($venue_result->venueadd4 != $venue_result->venueadd3){
																		echo $venue_result->venueadd4;
																	}
																?>&nbsp;
																<?php
																	if($venue_result->venueadd5 != $venue_result->venueadd4){ 
																		echo $venue_result->venueadd5;
																	}
																?>&nbsp;
																<?php 
																	if($venue_result->venpin != 0){
																		echo $venue_result->venpin;
																	}
																?>
															</td>
														</tr>
													<?php }?>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<?php if($vcenter == 3){ if($exam_code == 991 || $exam_code == 1052){ ?>
												<p><strong>In case of any technical issues, please dial 8956684119 (available on exam day and previous day only). For all other queries email to iibfexam@cscacademy.org
												</strong></p>
												<?php }else if($exam_code == 997 || $exam_code == 1053){ ?>
												<p><strong>
													<!-- In case of any technical issues, please dial 8956684119 (available on exam day and previous day only). For all other queries email to ippbexam@cscacademy.org  -->
													<?php if($selected_vendor=='nseit') {
													?>
													1.	In case of queries regarding the venue, please dial 022-62507714  from Monday to Friday (10 AM to 5 PM), or email bcbfexamsupport@nseit.com.<br>
													2.	Please note that you need to appear for the examination at the above-mentioned venue only and will not be permitted from any other venue.<br>
													3.	Your admit letter consists of 2 pages which include Important Instructions for Candidates. Kindly go through the instruction carefully before appearing for the exam. <br>
													4.	@Candidate should report to the venue of the examination at least 30 minutes before the exam time.<br>
													5.	Kindly go through the Exam Rules & Regulations carefully before appearing for the Examination.
													<?php 
													} else { ?>
													In case of any Technical Issue/Problem please dial 8956684119 Ext. 662  (available on exam day and the previous day only). for all other queries email to ippbexam@cscacademy.org.
													<?php } ?>
												</strong></p>
												<?php }else{
												?>
												
												<p><strong>
												<?php if($vcenter == 3){ ?>
													In case of any queries regarding venue, please dial 022-62507716  (available on exam day and previous day only). For all other queries email to care@iibf.org.in
													<?php } else { ?>
														In case of any queries regarding venue, please dial 1800 419 2929 (available on exam day and previous day only). For all other queries email to care@iibf.org.in
													<?php } ?>
												</strong></p>     
											<?php } }?>
											<?php if($vcenter == 4){ if($exam_code == 991 || $exam_code == 1052){ ?>
												<p><strong>In case of any technical issues, please dial 89566 84119 (available on exam day and previous day only). For all other queries email to iibfexam@cscacademy.org
												</strong></p>
												<?php }else if($exam_code == 997 || $exam_code == 1053){ ?>
												<p><strong>
													<!-- In case of any technical issues, please dial 8956684119 (available on exam day and previous day only). for all other queries email to ippbexam@cscacademy.org -->
												</strong></p>
											<?php } }?>
											<?php if($vcenter == 1){
												if($exam_code == 991 || $exam_code == 1052){ ?>
												<p><strong>In case of any technical issues, please dial 89566 84119 (available on exam day and previous day only). For all other queries email to iibfexam@cscacademy.org
												</strong></p>
												<?php  }else if($exam_code == 997 || $exam_code == 1053){ ?>
												<p><strong>
													<?php if($selected_vendor=='nseit') {
													?>
													1.	In case of queries regarding the venue, please dial 022-62507714  from Monday to Friday (10 AM to 5 PM), or email bcbfexamsupport@nseit.com.<br>
													2.	Please note that you need to appear for the examination at the above-mentioned venue only and will not be permitted from any other venue.<br>
													3.	Your admit letter consists of 2 pages which include Important Instructions for Candidates. Kindly go through the instruction carefully before appearing for the exam.<br> 
													4.	@Candidate should report to the venue of the examination at least 30 minutes before the exam time.<br>
													5.	Kindly go through the Exam Rules & Regulations carefully before appearing for the Examination.
													<?php 
													} else { ?>
													<!-- In case of any technical issues, please dial 8956684119 (available on exam day and previous day only). For all other queries email to ippbexam@cscacademy.org -->
													In case of any Technical Issue/Problem please dial 8956684119 Ext. 662  (available on exam day and the previous day only). for all other queries email to ippbexam@cscacademy.org. 
													<?php } ?>
												</strong></p>
												<?php  }else{ ?>
												<p>
													<strong>
														
													<?php if($vcenter == 3){ ?>
													In case of any queries regarding venue, please dial 022-62507716  (available on exam day and previous day only). For all other queries email to care@iibf.org.in
													<?php } else { ?>
														In case of any queries regarding venue, please dial 011-35454694 and press option 7.(available on exam day and previous day only). For all other queries email to care@iibf.org.in
													<?php } ?>
													</strong>
												</p>
											<?php } } ?>
											<?php /*?><?php if($exam_code!=21 && $exam_code!=42 && $exam_code!=992){?>
												<p><strong>For any Technical support / Query please contact on 8956684119 10.00 AM to 5 PM as on the day of the exam</strong></p>
											<?php }?><?php */?>
											<?php if($exam_code == 991 || $exam_code == 1052){?>
												<p>(Your Admit Letter consists of 2 pages which include Important Instruction. Kindly go through the instructions carefully, print both pages and carry the same to the examination venue)</p>
												<?php }else if($exam_code == 997 || $exam_code == 1053){ ?>
												<p><strong>
													<!-- In case of any technical issues, please dial 8956684119 (available on exam day and previous day only). for all other queries email to ippbexam@cscacademy.org 
													In case of any Technical Issue/Problem please dial 8956684119 Ext. 662  (available on exam day and the previous day only). for all other queries email to ippbexam@cscacademy.org. -->
													<?php if($selected_vendor=='nseit') {
													?>
													1.	In case of queries regarding the venue, please dial 022-62507714  from Monday to Friday (10 AM to 5 PM), or email bcbfexamsupport@nseit.com.<br>
													2.	Please note that you need to appear for the examination at the above-mentioned venue only and will not be permitted from any other venue.<br>
													3.	Your admit letter consists of 2 pages which include Important Instructions for Candidates. Kindly go through the instruction carefully before appearing for the exam. <br>
													4.	@Candidate should report to the venue of the examination at least 30 minutes before the exam time.<br>
													5.	Kindly go through the Exam Rules & Regulations carefully before appearing for the Examination.
													<?php 
													} else { ?>
													In case of any Technical Issue/Problem please dial 8956684119 Ext. 662  (available on exam day and the previous day only). for all other queries email to ippbexam@cscacademy.org.
													<?php } ?>
												</strong></p>
												<?php }else  if(in_array($exam_code,$caiib)){ ?>
												<p>(Your Admit Letters consists of 2 pages which includes Important Instructions. Kindly go through the instructions carefully, print all the pages and carry the same to the examination venue)</p>
												<?php } else  if(in_array($exam_code,$dipcert)){ ?>
												<p>(Your Admit Letters consists of 2 pages which includes Important Instructions. Kindly go through the instructions carefully, print all the pages and carry the same to the examination venue)</p>
												<?php } else  if(!in_array($exam_code,$dipcert)){ ?>
												<p>(Kindly go through the instructions carefully, print all pages and carry the same to the examination venue)</p>
											<?php }?>
										</td>
									</tr>
									<tr>
										<td width="100%">
											<table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
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
														<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">Exam Password : <?php echo $member_result->pwd;?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<?php if(!in_array($exam_code, array(210,420)) && !in_array($exam_code,$caiib)) { ?>
										<table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
											<tbody>
												<tr style="background-color:#dcf1fc;">
													<td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;"> <?php echo $member_result->created_on.' '.$transaction_no; ?> </td>
												</tr>
												<!-- <tr>
													<td style="text-align:left; padding:7px;"><p>
													The cut-off date of guidelines / instructions issued by the regulator(s) and important developments in banking and finance up to 31st December,2023 will only be considered for the purpose of inclusion in the question papers.</p></td>
												</tr> -->  
											</tbody>
										</table>
									<?php } ?>
									<tr>
										<td height="10"></td>
									</tr>
								</table><!--table-2-->
							</td>
						</tr>
					</table><!--table-1-->
				</td>
			</tr>
		</table><!--main-table-->
		
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
					

        <table cellpadding="0" cellspacing="0" border="0"  width="800"  align="center">
          
            <tr>
              <td style="font-size:14px;background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:14px;   padding:7px 0; text-transform:uppercase;">IMPORTANT RULES/INSTRUCTIONS FOR CONDUCT OF EXAM</td>
            </tr>
           
          <tr>
            <td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0; font-size:10px;" >
             
                <table cellpadding="0" cellspacing="0" border="0" width="100%" >
                  <tr>
                    <td width="10" style="padding-left:15px;">1.</td>
                    <td><strong style="">TIMINGS TO BE ADHERED BY THE CANDIDATES  :</strong></td>
                  </tr><!--1.-->
                  <tr>
                    <td colspan="2" style="padding-left:30px;">
                      <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr>
                          <td colspan="2">
                            <table border="1" cellspacing="0" cellpadding="5" width="80%">
                              <tr>
                                <td >Reporting Time  </td>
                                <td >09:00 A.M</td>
                                <td >11:45 A.M</td>
                                <td >02:30 P.M</td>
                              </tr>
                            
                              <tr>
                                <td >Entry Close </td>
                                <td >09:15 A.M</td>
                                <td >12:00 P.M</td>
                                <td >02:45 P.M</td>
                              </tr>
                              
                              <tr>
                                <td >Exam Time </td>
                                <td >09:30 A.M - 11:30 A.M</td>
                                <td >12:15 P.M-02:15 P.M</td>
                                <td >03:00 P.M- 05:00 P.M</td>
                              </tr>
                             
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
               
              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:7px;margin-left: 22px; width:96%">
                <tr>
                  <td width="2%" valign="top" >a.</td>
                  <td style="">Candidates must report at the examination venue <span style="color:red;">15 minutes prior to the Reporting Time</span>. Candidates reporting later than the time mentioned above will not be allowed to appear for the online examination for any reason whatsoever.</strong>
                  </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="">b.</td>
                  <td style=""><strong>Entry to the examination Lab/hall will close 15 minutes prior to the exam i.e. 9:15 A.M for Batch -1, 12:00 P.M for Batch-2 and 02:45 P.M for Batch-3.  Institute has instructed the examination conducting authorities of all the venues to strictly follow the timelines. <span style="color:red;">In case, the candidates have reported late and are found to be Arguing/Misbehaving with the Test Administrator, the candidate/s may be booked under Unfair Practice. Please refer to the rules/penalties for misconduct/unfair practices and debarment period for unfair practise.</span></strong> </td>
                </tr>
				<tr>
                  <td width="2%" valign="top" style="">c.</td>
                  <td style=""><strong>No candidate will be permitted to leave the examination hall in the first 60 minutes from the scheduled start time of the examination.</strong> </td>
                </tr>
              </table>
              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:7px;width:99%">
                <tr>
                  <td width="5" style="">2.&nbsp;</td>
                  <td><strong style="">ADMIT LETTER OF EXAMINATIONS  :</strong></td>
                </tr><!--2.-->
                <tr>
                  <td colspan="2" style="padding-left:30px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td width="2%" valign="top" style="">a.</td>
                        <td style="">Candidates are required to produce printed copy of admit letter along with Membership identity card or any other valid photo ID card in original (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue. </td>
                      </tr>
                      <tr>
                        <td width="2%" valign="top" style="">b.</td>
                        <td style=""><strong>In the absence of printed copy of Admit Letter and Original Photo Identity Card, candidates will be denied permission to write Examination. </strong></td>
                      </tr>
                      <tr>
                        <td width="2%" valign="top" style="">c.</td>
                        <td style="">Admit letter is valid only for the examination, date/s and centre/venue mentioned in the admit letter.</td>
                      </tr>
                    </table>
                  </td>
                </tr><!--Content of No.:2-->
                <tr>
                  <td width="5" style="margin-top:7px;">3.&nbsp;</td>
                  <td><strong style="">Frisking :</strong></td>
                </tr><!--2.-->
                <tr>
                  <td colspan="2" style="padding-left:30px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td width="2%" valign="top" style="">a.</td>
                        <td style="">Examination conducting authorities may do the frisking of candidates before entry to the examination hall/venue, to ensure that candidates do not carry items like mobile phone, any electronic/smart gadgets, other items which are not allowed in the examination hall. Candidates are required to co-operate with the examination conducting authorities. Candidates who do not co-operate for frisking activity will be denied entry to the examination hall/venue.</td>
                      </tr>
                    </table>
                  </td>
                </tr><!--Content of No.:3-->
                <tr>
                  <td width="5" style="margin-top:7px;">4.&nbsp;</td>
                  <td><strong style="">Mobile Phones :</strong></td>
                </tr><!--4.-->
                <tr>
                  <td colspan="2" style="padding-left:30px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td width="2%" valign="top" style="">a.</td>
                        <td style="">Mobile phones and other electronic/smart gadgets (except simple calculator as permissible) are not allowed in the examination hall. It is clarified that mere possession of mobile phone and other electronic/smart gadgets in the examination hall whether in switch off mode or silent mode shall also be deemed to be resorting to adoption of unfair means in the examination.  </td>
                      </tr>
                      <tr>
                        <td width="2%" valign="top" style="">b.</td>
                        <td style=""><strong>Institute will not make any arrangement for safe keep of Mobile Phones, electronic/smart gadgets, bags or any other item pertaining to the candidates.</strong></td>
                      </tr>
                    </table>
                  </td>
                </tr><!--Content of No.:4-->
                <tr>
                  <td width="5" style="margin-top:7px;">5.&nbsp;</td>
                  <td><strong style="">Use of calculator :</strong></td>
                </tr><!--5.-->
                <tr>
                  <td colspan="2" style="padding-left:30px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td width="2%" valign="top" style="">a.</td>
                        <td style="">Candidates will be allowed to use battery operated portable calculator in the examination. The calculator can be of any type up to 8 functions i.e. (Addition, Subtraction, Multiplication, Division, Percentage, Sq.-root, Tax+ and Tax -), 12 digits.   </td>
                      </tr>
                      <tr>
                        <td width="2%" valign="top" style="">b.</td>
                        <td style="">Attempt to use any other type of calculator not complying with the specifications indicated above or having more features than mentioned above shall tantamount to use of unfair means. Scientific/Financial calculator is not allowed.   
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr><!--Content of No.:5-->
                <tr>
                  <td width="5" style="margin-top:7px;">6.&nbsp;</td>
                  <td><strong style="">Provisional Score Card/Result :</strong></td>
                </tr><!--6.-->
                <tr>
                  <td colspan="2" style="padding-left:30px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td width="2%" valign="top" style="">a.</td>
                        <td style=""><strong>After submitting of the question paper provisional score card/result will be displayed on the computer screen.  </strong></td>
                      </tr>
                      <tr>
                        <td width="2%" valign="top" style="">b.</td>
                        <td style="">Candidate can download provisional score card from the website by 4-5 days from Institute’s website.
                        </td>
                      </tr>
                    
                    </table>
                  </td>
                </tr><!--Content of No.:6-->
                <tr>
                  <td width="5" style="margin-top:7px;">7.&nbsp;</td>
                  <td><strong style="">Scribe Guidelines : </strong></td>
                </tr><!--7.-->
                <tr>
                  <td colspan="2" style="padding-left:30px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td width="2%" valign="top" style="">a.</td>
                        <td style="">The candidate should make online application on website www.iibf.org.in about such requirement and obtaining permission at least 3 days before the commencement of the examination (This is required to make suitable arrangements at the examination venue). Candidate is required to follow this procedure for each attempt of examination in case the help of scribe is required. For more details pls refer the Institute website for complete guidelines.</td>
                      </tr>
                    </table>
                  </td>
                </tr><!--Content of No.:7-->
              </table>
              <table cellpadding="0" cellspacing="0" border="0"  width="100%"  align="center">
                <tr>
                  <td style="" >
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:7px;width:99%">
                      <tr>
                        <td width="2" style="padding-left:0px;">8. </td>
                        <td><strong style="padding-left:0px;">Rules, Penalties in case of Misconduct /Unfair Practices  :</strong></td>
                      </tr><!--8.-->
                      <tr>
                        <td colspan="2" style="padding-left:30px;">
                          <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td width="2%" valign="top" style="">a.</td>
                              <td style="">Candidates would be able to login to the system only with the password mentioned in the Admit Letter. This password should not be disclosed to others. Keep it safe to avoid the possible misuse</td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">b.</td>
                              <td style="">Candidates should ensure that they sign the Attendance Sheet.</td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">c.</td>
                              <td style="">Candidates are required to strictly follow all the instructions given by the examination conducting authority during the examination and adhere to Rules of the examination.
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">d.</td>
                              <td style="">Candidates are not permitted to logout/switch-off the computer for the sake of going to washroom and if they log out/switch-off NO re-login, will be permitted. Further the candidates are advised that the time taken for going to the washroom would be inclusive of the duration of two hours permitted to them to answer the question paper.
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">e.</td>
                              <td style="">In case candidates go to the washroom, attendance should again be taken for such candidates.
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">f.</td>
                              <td style="">Candidates should not possess and / or use books, notes, periodicals, etc. in the examination hall at the time of examination / or use mathematical tables, slide rules, stencils etc. during the examination  
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">g.</td>
                              <td style="">Communication of any sort between candidates or with outsiders is not permitted and complete silence should be maintained during the examination. 
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">h.</td>
                              <td style="">Copying answers from other candidates/other printed/Electronic material or permitting others to copy or consultation of any kind will attract the rules relating to unfair practices in the examination. 
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">i.</td>
                              <td style="">No candidate shall impersonate others or allow others to impersonate himself/herself at the examination. 
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">j.</td>
                              <td style="">No candidate shall misbehave/argue with the Examination Conducting Authorities at the centre. 
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">k.</td>
                              <td style="">Candidates have to compulsory return any papers given including that given for rough work to invigilator. </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">l.</td>
                              <td style="">If the examination could not commence on scheduled time or there is delay due to Failure of power, Technical snag of whatsoever nature or for any such reason having bearing upon the conduct of examination; candidates have to :- 
                                <table>
                                  <tr>
                                    <td style="">i.</td>
                                    <td style="">Wait till resumption of power supply/solving of technical snag.</td>
                                  </tr>
                                  <tr>
                                    <td style="">ii.</td>
                                    <td style="">Take-up the examination at other venue arranged by the examination conducting authority. </td>
                                  </tr>
                                  <tr>
                                    <td style="">iii.</td>
                                    <td style="">Follow instructions given by the examination conducting authority.</td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">m.</td>
                              <td style="">For all examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institute website www.iib.org.in in printable form once the result are declared. Candidates are requested to download the same. 
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">n.</td>
                              <td style="">Candidates should not write Questions/Options etc. on the Admit Letter or use it like rough sheet. If Candidate is found doing so, he/she shall be deemed to be resorting to adoption of unfair means in the examination.    
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">o.</td>
                              <td style=""><strong>This examination is confidential. It is made available to the candidates solely for the purpose of assessing qualifications in the discipline referenced in the title of this examination. Candidates are expressly prohibited from disclosing, publishing, reproducing, or transmitting the questions/options of the examinations, in whole or in part, in any form or by any means, verbal or written, electronic or mechanical, for any purpose, without the prior express written permission from IIBF. Candidates found doing so, shall be considered as unlawful act and attract the rules relating to unfair practices.</strong>
                              </td>
                            </tr>
                            <tr>
                              <td width="2%" valign="top" style="">&nbsp;</td>
                              <td style="">Violation of any of the Rules / Instructions, misuse of the Admit Letter will be considered to be an act of serious misconduct, and the Institute will take action as per the Rules of the examination, which will also be reported to the employer of the candidate.
                              </td>
                            </tr>
                            <tr>
                              <td width="5" style="padding-left:15px;">&nbsp;</td>
                              <td style=""><strong>PLEASE REFER INSTITUTE’S WEBSITE UNDER THE MENU “EXAM RELATED” FOR DETAILS OF DEBARMENT PERIOD FOR UNFAIR PRACTICES ADOPTED BY CANDIDATES DURING CONDUCT OF INSTITUTE’S EXAMINATIONS. 
                              </strong>
                              <br>
                             
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
    <?php 
    	$dipcert = array(8,11,19,119,78,79,151,153,154,156,157,158,163,165,166,$this->config->item('examCodeSOB'));
      if(!in_array($exam_code,$dipcert)) {
    ?>        
		<table cellpadding="0" cellspacing="0" border="0"  width="800"  align="center" style="">
          
            <tr>
              <td style="font-size:14px;background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:14px;   padding:7px 0; text-transform:uppercase;">Code of Conduct for candidates passing the JAIIB/CAIIB Examinations</td>
            </tr>
           
          <tr>
            <td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0; font-size:10px;" >
             
                <table cellpadding="0" cellspacing="0" border="0" width="100%" >
                  <tr>
                    
                    <td><strong style="">Code of Conduct for JAIIB / CAIIB passed candidates is a set of principles that outlines what is expected from them in their workplace:</strong></td>
                  </tr><!--1.-->
				  <tr>
					<td >
						<ol>
							<li>Shall always adhere to the highest level of professionalism, honesty, integrity as well as high moral and ethical standards.</li>
							<li>Shall ensure appropriate disclosures, wherever required, to maintain transparency in all operations.</li>
							<li>Shall at all times ensure compliance with all the laws and regulations affecting operations of the Institute/organisation (employer).</li>
							<li>Shall deal fairly and with equality with employees, customers and other stakeholders.</li>
							<li>Shall continuously keep abreast of the latest developments in relevant laws, rules and regulations related to banking operations.</li>
							<li>Shall not engage in any activity which may be considered as conflict of interest for the organisation.</li>
							<li>Shall also not engage in any activity which is detrimental to the interest of the certifying Institution i.e. HBF.</li>
							<li>Shall ensure that the conduct of all activities are totally transparent and ethical.</li>
							<li>Shall keep all proprietary and confidential information that comes to his/her possession as confidential.</li>
							<li>Shall report or communicate such material/information that is factually correct, complete and accurate to the best of his/her knowledge.</li>
							<li>Shall not indulge in any activity which may be construed as unbecoming of a professional.</li>
							<li>Shall promote prompt reporting of violations and suspected violations to the appropriate authority.</li>
							<li>Shall always try to uphold the credibility of employee of organisation through conduct and action.</li>
							<li>Shall not resort to falsification of documents or misinformation for personal benefits.</li>
						</ol>
					</td>
				  </tr>
				</table>
			</td>
			</tr>	
		</table>
	<?php } ?>	
     
  </body>
  <script>
    document.addEventListener('keydown', function (event) {
      if (event.ctrlKey && event.key === 'p') {
        // Ctrl+P pressed, execute your custom logic here
        event.preventDefault(); // Prevent the default print behavior
        var savePdfAnchor = document.getElementById('save_pdf');
        if (savePdfAnchor) {
          savePdfAnchor.click();
          } else {
          console.error('Element with ID "save_pdf" not found.');
        }
      }
    });
  </script>
</html>