<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
    
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
                  <td align="center"><img src="<?php echo base_url();?>assets/images/logo.jpg" width="400" height="66" /></td>
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td style="background-color:#1287c0; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:10px 0;">
                  Admit Letter for Online <?php echo $exam_name;?> Examination - <?php echo $examdate;?>
                  <div align="right">
                  	<a href="<?php echo base_url();?>Dbf/dashboard">Home</a> &nbsp;
                  	<a href="<?php echo base_url();?>Dbf/getadmitcardpdf">Save as pdf</a>&nbsp;
                  	<a href="javascript:void(0);" onclick="javascript:printDiv();">Print</a>&nbsp;
                  	<a href="<?php echo base_url()?>Dbf/logout">Logout</a>
                  </div>
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
                            Membership / Registration No. : <?php echo $record->mem_mem_no;?>
                            </td>
                          </tr>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">
                            <?php echo $record->mam_nam_1;?>
                            </td>
                          </tr>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase;">
                            <?php echo $record->mem_adr_1." ".$record->mem_adr_2." ".$record->mem_adr_3;?>
                            <?php echo $record->mem_adr_4." ".$record->mem_adr_5." ".$record->mem_adr_5;?>
                            </td>
                          </tr>
                          </table><!--table-5-->
                        </td>
                        <td align="center">
                          <table cellpadding="0" cellspacing="0" border="0">
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
                                <img src="<?php echo $img_path?><?php echo $photo;?>" width="100" height="125" />
                              </td>
                              </tr>
                              <tr>
                  <td height="5"></td>
                </tr>
                              <tr>
                              <td>
                                <img src="<?php echo $sig_path?><?php echo $signature;?>" width="100" height="50" />
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
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Time</th>
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;" >Seat Identification Code**</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
				  	foreach($subject as $subject){
				  ?>
                  <tr style="background-color:#dcf1fc;">
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subject->subject_description;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $medium;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->date;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->time;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $record->venueid;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $record->seat_identification;?></td>
                    </tr>
                  <?php }?>  
                  </tbody>
                  </table>
                  </td>
                </tr>
                <tr>
                	<td style="text-align:right; line-height:24px; font-size:13px;">**(Refer display board at Test Venue)</td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                  <td>
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
                  <thead>
                    <tr style="background-color:#7fd1ea;">
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Vanue Code</th>
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Address</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr style="background-color:#dcf1fc;">
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $record->venueid;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;">
					  	<?php echo $record->venueadd1." ".$record->venueadd2." ".$record->venueadd3;?>
                      	<?php echo $record->venueadd4." ".$record->venueadd4." ".$record->venpin;?>
                      </td>
                    </tr>
                  </tbody>
                  </table>
                  </td>
                </tr>
                <tr>
                <td>
                <p><strong>Note : In case of any queries regarding venue, Please Dial 1800 419 2929 and Press Option 7.(available on exam day and previous
day only). For all other queries email to care@iibf.org.in</strong></p>
<p>Please note that you need to appear for examination at the above mentioned 
venue only and will not be permitted from any other venue</p>
<p>(Your Admit Letter consists of 2 pages which includes Important Instructions. Kindly go through the instructions carefully, print
both the pages and carry the same to the examination venue)</p>
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
                  <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">LOGIN ID : <?php echo $record->mem_mem_no;?></td>
                </tr>
                <tr style="background-color:#dcf1fc;">
                  <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">Exam Password : <?php echo $record->pwd;?></td>
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
          
<table cellpadding="0" cellspacing="0" width="800" border="0" align="center" style="background-color:#fff;">
	<tr>
           <td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">IMPORTANT INSTRUCTIONS FOR CANDIDATES</td>
    </tr>
  <tr>
    <td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0;">
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td width="10" style="padding-left:15px;font-size:14px;">1.</td>
          <td><strong style="font-size:14px;">Admit Letter of Examinations :</strong></td>
        </tr><!--1.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Candidates are required to <strong>produce hard copy of admit letter</strong> along with Membership identity card or any other valid Original photo ID card at the examination venue. </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>In the absence of hard copy of Admit Letter and Photo Identity Card, candidates will be denied permission to write Examination.</strong> </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">c.</td>
                <td style="font-size:13px;">Admit letter is valid only for the examination and centre/venue mentioned in the admit letter.</td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:1-->
        <tr>
          <td width="5" style="padding-left:15px;font-size:14px;">2.</td>
          <td><strong style="font-size:14px;">Mobile Phones :</strong></td>
        </tr><!--2.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Mobile phones and other electronic gadgets (except calculator as permissible ) are not allowed in the examination hall. It is clarified that mere possession of mobile phone in the examination hall whether in switch off mode or silent mode shall also be deemed to be resorting to adoption of unfair means in the examination.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>Institute will not make any arrangement for safe keep of Mobile phones, electronic gadgets, bags or any other item pertaining to the candidates.</strong></td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:2-->
        <tr>
          <td width="5" style="padding-left:15px;font-size:14px;">3.</td>
          <td><strong style="font-size:14px;">Use of calculator :</strong></td>
        </tr><!--3.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Candidates will be allowed to use battery operated portable calculator in the exam. The calculator can be of any type up to 6 functions, 12 digits.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;">Attempt to use any other type of calculator not complying with the specifications indicated above or having more features than mentioned above shall tantamount to use of unfair means. Scientific/Financial calculator is not allowed.
                </td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:3-->
        <tr>
          <td width="10" style="padding-left:15px;font-size:14px;">4.</td>
          <td><strong style="font-size:14px;">Other Rules/Information:</strong></td>
        </tr><!--4.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;"><strong>Candidates are not permitted to logout/switch-off the computer for the sake of going to washroom and if they log out/switch-off NO re-login, will be permitted. Further the candidates are advised that the time taken for going to the washroom would be inclusive of the duration of two hours permitted to them to answer the question paper.</strong></td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;">Candidates should not possess and / or use books, notes, periodicals, etc. in the examination hall at the time of examination / or use mathematical tables, slide rules, stencils etc. during the examination.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">c.</td>
                <td style="font-size:13px;">Candidates are advised to reach the Examination Venue at least 30 minutes before commencement of the examination.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">d.</td>
                <td style="font-size:13px;">No candidate will be permitted to enter the Examination Venue/hall after expiry of 15 minutes and to leave the hall in the first 30 minutes from the scheduled commencement of the examination.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">e.</td>
                <td style="font-size:13px;">Candidates would be able to login to the system only with the password mentioned in this Admit Letter. This password should not be disclosed to others. Keep it safe to avoid the possible misuse.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">f.</td>
                <td style="font-size:13px;">If the examination could not commence on scheduled time or there is delay due to Failure of power, Technical snag of whatsoever nature or for any such reason having bearing upon the conduct of examination; candidates have to : 
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
                <td width="2%" valign="top" style="font-size:13px;">g.</td>
                <td style="font-size:13px;">Candidates are required to strictly follow all the instructions given by the examination conducting authority during the examination and adhere to Rules of the examination.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">h.</td>
                <td style="font-size:13px;">Violation of any of the Rules / Instructions, misuse of the Admit Letter will be considered to be an act of serious misconduct and the Institute will take action as per the Rules of the examination, which will also be reported to the employer of the candidate.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">i.</td>
                <td style="font-size:13px;">For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institutes website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same.
                </td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:4-->
       
      </table>
    </td>
  </tr>
</table>

<!--Printable DIV-->
<div class="content-wrapper" id="print_div" style="display: none;">

<table cellpadding="0" cellspacing="0" width="800" border="0" align="center">
    <tr>
      <td style="background-color:#fff;">
        <!--table-1-->
        <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
          <tr>
            <td style="border:1px solid #1287c0; padding:5px;">
              <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
                <tr>
                  <td align="center"><img src="<?php echo base_url();?>assets/images/logo.jpg" width="400" height="66" /></td>
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td style="background-color:#1287c0; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:10px 0;"> Admit Letter for Online <?php echo $exam_name;?> Examination - <?php echo $examdate;?></td>
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
                            Membership / Registration No. : <?php echo $record->mem_mem_no;?>
                            </td>
                          </tr>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">
                            <?php echo $record->mam_nam_1;?>
                            </td>
                          </tr>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase;">
                            <?php echo $record->mem_adr_1." ".$record->mem_adr_2." ".$record->mem_adr_3;?>
                            <?php echo $record->mem_adr_4." ".$record->mem_adr_5." ".$record->mem_adr_5;?>
                            </td>
                          </tr>
                          </table><!--table-5-->
                        </td>
                        <td align="center">
                          <table cellpadding="0" cellspacing="0" border="0">
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
                                <img src="<?php echo $img_path?><?php echo $photo;?>" width="100" height="125" />
                              </td>
                              </tr>
                              <tr>
                  <td height="5"></td>
                </tr>
                              <tr>
                              <td>
                                <img src="<?php echo $sig_path?><?php echo $signature;?>" width="100" height="50" />
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
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Time</th>
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;" >Seat Identification Code**</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
				  	foreach($subjectprint as $subjectprint){
				  ?>
                  <tr style="background-color:#dcf1fc;">
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subjectprint->subject_description;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $medium;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subjectprint->date;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subjectprint->time;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $record->venueid;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $record->seat_identification;?></td>
                    </tr>
                  <?php }?>  
                  </tbody>
                  </table>
                  </td>
                </tr>
                <tr>
                	<td style="text-align:right; line-height:24px; font-size:13px;">**(Refer display board at Test Venue)</td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                  <td>
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
                  <thead>
                    <tr style="background-color:#7fd1ea;">
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Vanue Code</th>
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Address</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr style="background-color:#dcf1fc;">
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $record->venueid;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;">
					  	<?php echo $record->venueadd1." ".$record->venueadd2." ".$record->venueadd3;?>
                      	<?php echo $record->venueadd4." ".$record->venueadd4." ".$record->venpin;?>
                      </td>
                    </tr>
                  </tbody>
                  </table>
                  </td>
                </tr>
                <tr>
                <td>
                <p><strong>Note : In case of any queries regarding venue, Please Dial 1800 419 2929 and Press Option 7.(available on exam day and previous
day only). For all other queries email to care@iibf.org.in</strong></p>
<p>Please note that you need to appear for examination at the above mentioned 
venue only and will not be permitted from any other venue</p>
<p>(Your Admit Letter consists of 2 pages which includes Important Instructions. Kindly go through the instructions carefully, print
both the pages and carry the same to the examination venue)</p>
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
                  <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">LOGIN ID : <?php echo $record->mem_mem_no;?></td>
                </tr>
                <tr style="background-color:#dcf1fc;">
                  <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">Exam Password : <?php echo $record->pwd;?></td>
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
<table cellpadding="0" cellspacing="0" width="800" border="0" align="center" style="background-color:#fff;">
	<tr>
           <td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">IMPORTANT INSTRUCTIONS FOR CANDIDATES</td>
    </tr>
  <tr>
    <td style="background-color:#dcf1fc; padding:7px 0; border:1px solid #1287c0;">
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td width="10" style="padding-left:15px;font-size:14px;">1.</td>
          <td><strong style="font-size:14px;">Admit Letter of Examinations :</strong></td>
        </tr><!--1.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Candidates are required to <strong>produce hard copy of admit letter</strong> along with Membership identity card or any other valid Original photo ID card at the examination venue. </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>In the absence of hard copy of Admit Letter and Photo Identity Card, candidates will be denied permission to write Examination.</strong> </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">c.</td>
                <td style="font-size:13px;">Admit letter is valid only for the examination and centre/venue mentioned in the admit letter.</td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:1-->
        <tr>
          <td width="5" style="padding-left:15px;font-size:14px;">2.</td>
          <td><strong style="font-size:14px;">Mobile Phones :</strong></td>
        </tr><!--2.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Mobile phones and other electronic gadgets (except calculator as permissible ) are not allowed in the examination hall. It is clarified that mere possession of mobile phone in the examination hall whether in switch off mode or silent mode shall also be deemed to be resorting to adoption of unfair means in the examination.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>Institute will not make any arrangement for safe keep of Mobile phones, electronic gadgets, bags or any other item pertaining to the candidates.</strong></td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:2-->
        <tr>
          <td width="5" style="padding-left:15px;font-size:14px;">3.</td>
          <td><strong style="font-size:14px;">Use of calculator :</strong></td>
        </tr><!--3.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Candidates will be allowed to use battery operated portable calculator in the exam. The calculator can be of any type up to 6 functions, 12 digits.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;">Attempt to use any other type of calculator not complying with the specifications indicated above or having more features than mentioned above shall tantamount to use of unfair means. Scientific/Financial calculator is not allowed.
                </td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:3-->
        <tr>
          <td width="10" style="padding-left:15px;font-size:14px;">4.</td>
          <td><strong style="font-size:14px;">Other Rules/Information:</strong></td>
        </tr><!--4.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;"><strong>Candidates are not permitted to logout/switch-off the computer for the sake of going to washroom and if they log out/switch-off NO re-login, will be permitted. Further the candidates are advised that the time taken for going to the washroom would be inclusive of the duration of two hours permitted to them to answer the question paper.</strong></td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;">Candidates should not possess and / or use books, notes, periodicals, etc. in the examination hall at the time of examination / or use mathematical tables, slide rules, stencils etc. during the examination.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">c.</td>
                <td style="font-size:13px;">Candidates are advised to reach the Examination Venue at least 30 minutes before commencement of the examination.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">d.</td>
                <td style="font-size:13px;">No candidate will be permitted to enter the Examination Venue/hall after expiry of 15 minutes and to leave the hall in the first 30 minutes from the scheduled commencement of the examination.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">e.</td>
                <td style="font-size:13px;">Candidates would be able to login to the system only with the password mentioned in this Admit Letter. This password should not be disclosed to others. Keep it safe to avoid the possible misuse.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">f.</td>
                <td style="font-size:13px;">If the examination could not commence on scheduled time or there is delay due to Failure of power, Technical snag of whatsoever nature or for any such reason having bearing upon the conduct of examination; candidates have to : 
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
                <td width="2%" valign="top" style="font-size:13px;">g.</td>
                <td style="font-size:13px;">Candidates are required to strictly follow all the instructions given by the examination conducting authority during the examination and adhere to Rules of the examination.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">h.</td>
                <td style="font-size:13px;">Violation of any of the Rules / Instructions, misuse of the Admit Letter will be considered to be an act of serious misconduct and the Institute will take action as per the Rules of the examination, which will also be reported to the employer of the candidate.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">i.</td>
                <td style="font-size:13px;">For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institutes website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same.
                </td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:4-->
       
      </table>
    </td>
  </tr>
</table>

</div>

<script>
function printDiv(divName) {
	
	 var opt = confirm("Do you want to print a copy?"); 
	if(opt == false){
		return false;	
	}else{
		 var printContents = document.getElementById('print_div').innerHTML;
		 var originalContents = document.body.innerHTML;
		 document.body.innerHTML = printContents;
		 window.print();
		 document.body.innerHTML = originalContents;
	}
     
}
</script>
  
</body>
</html>