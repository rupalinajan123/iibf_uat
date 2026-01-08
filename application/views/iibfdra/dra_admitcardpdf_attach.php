<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>

<html lang="en">

<head>
<?php $this->load->view('google_analytics_script_common'); ?>

	<meta charset="utf-8">

	<title>Welcome to IIBF</title>

    

</head>

<body style="background-color:#fff; margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">

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

                  <!-- <img src="<?php echo base_url()?>assets/images/ninty_year_new.png" width="70" height="70" style="margin-left: 100px; margin-right: -190px;" /> -->

                  </td>

                </tr>

                <tr>

                  <td height="5"></td>

                </tr>

                <tr>

                  <td style="background-color:#1287c0; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:10px 0;">

                  <?php

                  	$caiib = array($this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");

					if(!in_array($exam_code,$caiib)){

				  ?>

                  <?php

                  		if($exam_code == 101){

							echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS - ".$examdate;

						}elseif($exam_code == 991){

							echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENT";

						}else{

				  ?>

                  Admit Letter for DRA <?php echo $exam_name;?>

                  <?php if($exam_code != 101 && $exam_code != 991){ echo "Examination"; }?>

                   - 

				  <?php echo $examdate;?>

                  <?php } }?>

                  <?php

						$elective = array("62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");

						if($exam_code == $this->config->item('examCodeCaiib')){

							echo "Admit letter for Online CAIIB Examination – ".$examdate;

						}

						if(in_array($exam_code,$elective)){

							echo "Admit letter for Online CAIIB Electives – ".$examdate;

						}

					?>

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

                            </td>

                          </tr>

                          <tr>

                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">

                            <?php echo $member_result->mam_nam_1;?>

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

                                	<td style="border:0px solid #333; padding:7px; text-transform:uppercase;"><img src="<?php echo base_url();?>assets/images/phone-icon.png" width="220"  alt="Phone" style="float:left; margin-top:8px; margin-right:15px;"  /></td>

                                    <td></td>

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
                                <?php $this->master_model->resize_admitcard_images(dra_img_p($member_id)); ?>
                                <img src="<?php echo dra_img_p($member_id);?>" width="100" height="125" />

                              </td>

                              </tr>

                              <tr>

                  <td height="5"></td>

                </tr>

                              <tr>

                              <td>
                                <?php $this->master_model->resize_admitcard_images(dra_img_s($member_id)); ?>
                                <img src="<?php echo dra_img_s($member_id);?>" width="100" height="125" />
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

                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Subject Name</th>

                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Medium</th>

                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Date</th>

                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Reporting Time<sup>@</sup></th>

                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Exam Time<sup>@</sup></th>

                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Seat Identification No</sup></th>
                    </tr>

                  </thead> 

                  <tbody>

                  <?php 

				  	foreach($subject as $subject){
				  	    
				  	    

				  ?>

                  <tr style="background-color:#dcf1fc;">

                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subject->subject_description;?></td>

                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $medium;?></td>

                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">

					  <?php
					  		$exam_print_date = explode("-",$subject->date);
							  $edate = $exam_print_date[0]."-".$exam_print_date[1]."-".$exam_print_date[2];
							  echo date('d-M-Y',strtotime($edate));

                $curr_exam_time = date('H:i:s',strtotime($subject->time));
					  ?>

                      </td>

                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo date("g:i A",strtotime($curr_exam_time) - 1800); ?></td>

                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->time;?></td>

                       <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subject->seat_identification;?></td>


                    </tr>

                  <?php }?>  

                  </tbody>

                  </table>

                  </td>

                </tr>

                <tr>

                  <td style="text-align:left; line-height:24px; font-size:13px;">
                    *Seat No. will be allotted at examination hall @No candidate/s will be permitted to enter the Examination Venue/hall after the gate
closing time for any reason whatsoever.” The exam/reporting time details are shared below
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
                         <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Seat Identification No</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($venue_result as $venue_result){?>
                      <tr style="background-color:#dcf1fc;">
                        <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $venue_result->venueid;?></td>
                        <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $venue_result->venueadd1;?> <?php echo $venue_result->venueadd2;?>
                          <?php 
							if($venue_result->venueadd3 != $venue_result->venueadd2){
								echo $venue_result->venueadd3;
							}
						?>
                          <?php 
							if($venue_result->venueadd4 != $venue_result->venueadd3){
								echo $venue_result->venueadd4;
							}
						?>
                          <?php
							if($venue_result->venueadd5 != $venue_result->venueadd4){ 
								echo $venue_result->venueadd5;
							}
						?>
                          <?php 
							if($venue_result->venpin != 0){
								echo $venue_result->venpin;
							}
						?></td>
                        <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $venue_result->seat_identification;?></td>
                      </tr>
                      <?php }?>
                    </tbody>
                  </table></td>
              </tr>
                <tr>

                	<td style="text-align:left; line-height:24px; font-size:13px;">

                     @ Refer Reporting Time in the Important Instructions
                    

                    </td>

                </tr>

                <tr>

                    <td height="10"><p>1.In case of any queries regarding venue, please dial 1800 419 2929 (available on exam day and previous day only). for all other queries contact to respective Zonal/Corporate Office of the institute.</p>
                    <p>2.Please note that you need to appear for examination at the above mentioned 
                      venue only and will not be permitted from any other venue</p>
                      <!-- <p>3.Your Admit Letter consists of 2 pages which includes Important Instructions.COVID-19 Guidelines to be followd by the candidates(Annexure I) and self diclaration form(Annexure A). Kindly go through the instructions carefully, print
                      both the pages and carry the same to the examination venue)</p> -->
                    </td>

                </tr>

                

                <tr>

                <td>

                

                

				

    

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

  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  

 <table cellpadding="0" cellspacing="0" width="800" border="0" align="center" style="background-color:#fff; font-size:14px important;">
    
    <tr>
           <td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">IMPORTANT INSTRUCTIONS FOR CANDIDATES</td>
    </tr>
    <tr>
      <td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0;"><table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td width="10" style="padding-left:15px;font-size:14px;">1.</td>
            <td><strong style="font-size:14px;">TIMINGS TO BE ADHERED BY THE CANDIDATES  :</strong></td>
          </tr>
          <!--1.-->
          <tr>
            <td colspan="2" style="padding-left:30px;"><table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td colspan="2"><table border="1" cellspacing="0" cellpadding="5" width="650">
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
                        <td >13.30</td>
                        <td >16.00</td>
                      </tr>
                      <tr>
                        <td >Candidate Entry to computer Lab  </td>
                        <td >8.30 to 8.45</td>
                        <td >11.00 to 11.15</td>
                        <td >13.30 to 13.45</td>
                        <td >16.00 to 16.15</td>
                      </tr>
                      <tr>
                        <td >Gate Closing </td>
                        <td >8.45</td>
                        <td >11.15</td>
                        <td >13.45</td>
                        <td >16.15</td>
                      </tr>
                      <tr>
                        <td >Candidate Login start time for sample    test</td>
                        <td >8.50</td>
                        <td >11.20</td>
                        <td >13.50</td>
                        <td >16.20</td>
                      </tr>
                      <tr>
                        <td >Exam Start Time </td>
                        <td >9.00</td>
                        <td >11.30</td>
                        <td >14.00</td>
                        <td >16.30</td>
                      </tr>
                      <tr>
                        <td >Exam Close Time</td>
                        <td >11.00</td>
                        <td >13.30</td>
                        <td >16.00</td>
                        <td >18.30</td>
                      </tr>
                    </table></td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">a.</td>
                  <td style="font-size:13px;">Candidates are advised to report to the Examination Venue as per the timing mentioned above.<strong> No candidate/s will be permitted to enter the Examination Venue/hall after the reporting time. </strong></td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">b.</td>
                  <td style="font-size:13px;"><strong>No candidate will be permitted to leave the hall in the first 60 minutes from the scheduled start time of the examination.</strong></td>
                </tr>
              </table></td>
          </tr>
          <!--Content of No.:1-->
          <tr>
            <td width="10" style="padding-left:15px;font-size:14px;">2.</td>
            <td><strong style="font-size:14px;">ADMIT LETTER OF EXAMINATIONS  :</strong></td>
          </tr>
          <!--1.-->
          <tr>
            <td colspan="2" style="padding-left:30px;"><table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">a.</td>
                  <td style="font-size:13px;">Candidates are required to <strong>produce printed copy of admit letter</strong> along with Membership identity card or any other valid photo ID card in original (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">b.</td>
                  <td style="font-size:13px;"><strong>In the absence of printed copy of Admit Letter and Photo Identity Card, candidates will be denied permission to write Examination.</strong></td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">c.</td>
                  <td style="font-size:13px;">Admit letter is valid only for the examination, date/s and centre/venue mentioned in the admit letter.</td>
                </tr>
              </table></td>
          </tr>
          <!--Content of No.:1-->
          <tr>
            <td width="5" style="padding-left:15px;font-size:14px;">3.</td>
            <td><strong style="font-size:14px;">Mobile Phones :</strong></td>
          </tr>
          <!--2.-->
          <tr>
            <td colspan="2" style="padding-left:30px;"><table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">a.</td>
                  <td style="font-size:13px;">Mobile phones and other electronic/smart gadgets (except calculator as permissible) are not allowed in the examination hall. It is clarified that mere possession of mobile phone and other electronic/smart gadgets in the examination hall whether in switch off mode or silent mode shall also be deemed to be resorting to adoption of unfair means in the examination.</td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">b.</td>
                  <td style="font-size:13px;"><strong>Institute will not make any arrangement for safe keep of Mobile Phones, electronic/smart gadgets, bags or any other item pertaining to the candidates.</strong></td>
                </tr>
              </table></td>
          </tr>
          <!--Content of No.:2-->
          <tr>
            <td width="5" style="padding-left:15px;font-size:14px;">4.</td>
            <td><strong style="font-size:14px;">Use of calculator :</strong></td>
          </tr>
          <!--3.-->
          <tr>
            <td colspan="2" style="padding-left:30px;"><table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">a.</td>
                  <td style="font-size:13px;">Candidates will be allowed to use battery operated portable calculator in the examination. The calculator can be of any type up to 6 functions, 12 digits.</td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">b.</td>
                  <td style="font-size:13px;">Attempt to use any other type of calculator not complying with the specifications indicated above or having more features than mentioned above shall tantamount to use of unfair means. Scientific/Financial calculator is not allowed. </td>
                </tr>
              </table></td>
          </tr>
          <!--Content of No.:3-->
          <tr>
            <td width="10" style="padding-left:15px;font-size:14px;">5.</td>
            <td><strong style="font-size:14px;">Rules, Penalities for Misconduct / Unfair Practices :</strong></td>
          </tr>
          <!--4.-->
          <tr>
            <td colspan="2" style="padding-left:30px;"><table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">a.</td>
                  <td style="font-size:13px;">Candidates are advised to reach the Examination Venue at least 30 minutes before commencement of the examination.</td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">b.</td>
                  <td style="font-size:13px;">No candidate will be permitted to enter the Examination Venue/hall after expiry of 15 minutes and to leave the hall in the first 30 minutes from the scheduled commencement of the examination.</td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">c.</td>
                  <td style="font-size:13px;">Candidates would be able to login to the system only with the password mentioned in this Admit Letter. This password should not be disclosed to others. Keep it safe to avoid the possible misuse. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">d.</td>
                  <td style="font-size:13px;">Candidates should ensure that they sign the Attendance Sheet. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">e.</td>
                  <td style="font-size:13px;">Candidates are required to strictly follow all the instructions given by the examination conducting authority during the examination and adhere to Rules of the examination. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">f.</td>
                  <td style="font-size:13px;">Candidates are not permitted to logout/switch-off the computer for the sake of going to washroom and if they log out/switch-off NO re-login, will be permitted. Further the candidates are advised that the time taken for going to the washroom would be inclusive of the duration of two hours permitted to them to answer the question paper. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">g.</td>
                  <td style="font-size:13px;">Candidates should not possess and / or use books, notes, periodicals, etc. in the examination hall at the time of examination / or use mathematical tables, slide rules, stencils etc. during the examination. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">h.</td>
                  <td style="font-size:13px;">Communication of any sort between candidates or with outsiders is not permitted and complete silence should be maintained during the examination. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">i.</td>
                  <td style="font-size:13px;">Copying answers from other candidates/other printed/Electronic material or permitting others to copy or consultation of any kind will attract the rules relating to unfair practices in the examination. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">j.</td>
                  <td style="font-size:13px;">No candidate shall impersonate others or allow others to impersonate himself/herself at the examination. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">k.</td>
                  <td style="font-size:13px;">No candidate shall misbehave/argue with the Examination Conducting Authorities at the centre. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">l.</td>
                  <td style="font-size:13px;">Candidates have to compulsory return any papers given including that given for rough work to invigilator. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">m.</td>
                  <td style="font-size:13px;">If the examination could not commence on scheduled time or there is delay due to Failure of power, Technical snag of whatsoever nature or for any such reason having bearing upon the conduct of examination; candidates have to :-
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
                    </table></td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">n.</td>
                  <td style="font-size:13px;">For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institute's website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
                  <td style="font-size:13px;">&nbsp;</td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
                  <td style="font-size:13px;">Violation of any of the Rules / Instructions, misuse of the Admit Letter will be considered to be an act of serious misconduct and the Institute will take action as per the Rules of the examination, which will also be reported to the employer of the candidate. </td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
                  <td style="font-size:13px;">&nbsp;</td>
                </tr>
                <tr>
                  <td width="2%" valign="top" style="font-size:13px;">&nbsp;</td>
                  <td style="font-size:13px;"><strong>PLEASE REFER INSTITUTE'S WEBSITE UNDER THE MENU "EXAM RELATED" FOR DETAILS OF DEBARMENT PERIOD FOR UNFAIR PRACTICES ADOPTED BY CANDIDATES DURING CONDUCT OF INSTITUTE'S EXAMINATIONS.</strong></td>
                </tr>
              </table></td>
          </tr>
          <!--Content of No.:4-->
          
        </table></td>
    </tr>
  </table>
</body>

</html>