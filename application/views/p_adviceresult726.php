<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Indian Institute of Banking &amp; Finance</title>
<link href="<?php echo  base_url()?>assets/css/exam-result.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
</head> 
 
<body>  
<table border="0" cellpadding="0" cellspacing="0" width="900px" align="center" style=" margin-bottom:10px;">
  <tr>
    <td style="padding:10px 0;"><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
        <tr> 
          <td align="right">
          	<a onclick="javascript:printDiv();">
          		<img src="<?php echo  base_url()?>assets/images/print-icon.png"> 
            </a>
            <?php /*?>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo base_url()?>result/pdc_adviceresultpdf">
          		<img src="<?php echo  base_url()?>assets/images/Graphicloads-Filetype-Pdf.ico" width="30" height="30">
            </a><?php */?>
          </td>
        </tr>
      </table>
      <!--Logo Table--></td>
  </tr>
  <tr>
    <td><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
        <tr>
          <td style="border:3px solid #619fda;"><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
              <tr>
                <td style="padding:10px 0; border-bottom:3px solid #619fda;"><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                    <tr>
                      <td style="padding-left:20px; vertical-align:top;"><img src="<?php echo  base_url()?>assets/images/iibf_logo_short.png"></td>
                      <td class="login-logo" align="center"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<sup>®</sup><br>
                        <small>An ISO 21001:2018 Certified </small><br />
                        <span style="font-size:20px;">KOHINOOR CITY, COMMERCIAL - II, TOWER - 1, 2ND FLOOR<BR />
                        KIROL ROAD, KURLA (WEST), MUMBAI - 400 070</span></a></td>
                        <td valign="top">
                    <!--    <img src="<?php echo base_url()?>assets/images/register-trademark.png" width="40" height="40" />-->
                        </td>
                        <td>
                         <img src="<?php echo base_url()?>assets/images/90 Year of Service [IIBF]_Logo _Final_08062017.png" width="100" height="100" />
                        </td>
                    </tr>
                  </table>
                  <!--Logo Table--></td>
              </tr>
              <tr>
                <td style="border-bottom:3px solid #619fda; padding:10px; font-family:'Times New Roman', Times, serif; font-size:15px;">
                	<table width="100%"> 
                    	<tr>
                        	<td width="75%">
                            	Membership/Registration No: <?php echo $this->session->userdata('result_mem_no')?><br />
                  				<?php echo $user_info->firstname." ".$user_info->middlename." ".$user_info->lastname?> <br />
                  				<?php echo $user_info->address1?><br />
                  				<?php echo $user_info->address2?><br />
								  <?php 
                                        if($user_info->address3 != $user_info->address2 && $user_info->address3!=''){
                                            echo $user_info->address3."<br/>";
                                        }
                                  ?>
								  <?php 
                                        if($user_info->address4 != $user_info->address3 && $user_info->address4!=''){
                                            echo $user_info->address4."<br/>";
                                        }
                                  ?>
                                  <?php 
                                        if($user_info->address5 != $user_info->address4 && $user_info->address5!=''){
                                            echo $user_info->address5."<br/>";
                                        }
                                  ?>
                                  <?php 
                                        if($user_info->address6 != $user_info->address5 && $user_info->address6!=''){
                                            echo $user_info->address6."<br/>";
                                        }
                                  ?>
                  					Pin :<?php echo $user_info->pincode?>
                            </td>
                        </tr>
                    </table>
                </td>
              </tr>
              <tr>
                <td style="border-bottom:3px solid #619fda; padding:5px 10px; font-family: Tahoma, Geneva, sans-serif; font-size:14px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                    <tr>
                      <td>
                      	<strong>Examination<br />
                        <?php  echo preg_replace("/\([^)]+\)/","",$exam_name). " April 2018";?> 
                        </strong>
                      </td>
                      <td style="text-align:right;"><strong>MUMBAI, Dated</strong><br />
                      <?php echo "26-APR-2018";?>
                      </td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td style="padding:10px; font-size:14px; font-family:'Times New Roman', Times, serif; font-weight:bold;">DEAR CANDIDATE,<br />
                  <br />
                  WE HERE UNDER ADVISE YOUR RESULT OF THE ABOVE EXAMINATION </td>
              </tr>
              <tr>
                <td style="padding:20px 0; font-family:'Times New Roman', Times, serif; font-size:14px;"><table style="border-collapse:collapse;" width="90%" align="center">
                    <tbody>
                      <tr style="border: 1px #6699CC solid;">
                        <th style="border: 1px #6699CC solid;font-weight:bold; padding:5px 0;" class="text2" bgcolor="#e2e2e2" align="center">Subject</th>
                        <th style="border: 1px #6699CC solid;font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Marks Obtained</th>
                        <th style="border: 1px #6699CC solid;font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Result</th>
                      </tr>
                      <?php 
					  	$scnt = 0;
						
					  	foreach($record as $record){ 
					  ?>
                      <tr>
                        <td style="border: 1px #6699CC solid; padding:5px 10px;" class="text2">
							<?php echo $record['subject_name']?>
                        </td>
                        <td style="border: 1px #6699CC solid;" class="text2" align="center">
                        	<?php
								if($record['status'] == 'A'){
									echo "-";	
								}
								if($record['status'] == 'F' || $record['status'] == 'P'){
									 echo $record['marks'];
								}
							
							 
							 ?>
                        </td>
                        <td style="border: 1px #6699CC solid;" class="text21" align="center">
                        	<?php 
								if($record['status'] == 'P'){
									echo "PASS";
									$scnt++;
								}elseif($record['status'] == 'F'){
									echo "FAIL";
								}elseif($record['status'] == 'A'){
									echo "ABSENT";
								}elseif($record['status'] == 'C'){
									echo "CREDIT TRANSFER";	
								}elseif($record['status'] == 'E'){
									echo "EXEMPTED ";	
								}
							?>
                        </td>
                      </tr>
                      <?php }?>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
				  <?php 
				  
				  if(in_array($exam_code,array("34"))){?>
                  <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                   <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 60</p>
                    </td>
                  <?php }?>
                  <?php if(in_array($exam_code,array("58"))){?>
                  <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
               <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                </td>
                  <?php }?>
                  <?php if(in_array($exam_code,array("160"))){?>
                  <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
               <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                </td>
                  <?php }?>
                  <?php if(in_array($exam_code,array("177"))){?>
                  <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
               <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                </td>
                  <?php }?>
              </tr>
              
              <tr>
                <td style="padding:10px; font-size:14px; border-bottom:3px solid #619fda;">
                
                <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">This result advice is issued subject to what is stated below which may please be noted</p>
                  <ol class="result">
                    <li>As per the rules, no request for revaluation of answer paper will be entertained. The facility of verification of marks for online mode will not be available for Objective Type (Multiple Choice Questions) examinations since the evaluation is computerized. </li>
                    <li>In case a candidate has adopted unfair practices, the declaration of results is only provisional and is subject to cancellation in the event of adoption of unfair practices being established. </li>
                    <li>The result of this examination shall also be liable to be cancelled in the event of it being established that the candidate has adopted unfair practices in any previous examination. </li>
                    <li><strong>Candidates passing the examination will be issued a Certificate</strong></li>
                    <li>For Important Announcements/Notices & Examination schedule/rules/syllabus visit Institute's website: www.iibf.org.in </li>
                  </ol></td>
              </tr>
              <tr>
                <td style="padding:20px 0;"><table style="border-collapse:collapse;" width="90%" align="center">
                    <tbody>
                      <tr>
                        <td colspan="4" style="border:1px;border-style:solid;border-color:#6699CC;text-align:center; padding:5px 0;" bgcolor="#e2e2e2">Please Write to us at the following E-mail in case of any change in your address:</td>
                      </tr>
                      <tr>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Members Support Services</td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre <br>
                          Eastern Zone</td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre<br>
                          Southern Zone</td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre<br>
                          Northern Zone</td>
                      </tr>
                      <tr>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;"><a style="text-decoration:underline" href="mailto:care@iibf.org.in">care@iibf.org.in</a></td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;"><a style="text-decoration:underline" href="mailto:iibfez@iibf.org.in">iibfez@iibf.org.in</a></td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;"><a style="text-decoration:underline" href="mailto:iibfsz@iibf.org.in">iibfsz@iibf.org.in</a></td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;"><a style="text-decoration:underline" href="mailto:iibfnz@iibf.org.in">iibfnz@iibf.org.in</a></td>
                      </tr>
                    </tbody>
                  </table>
                  <table width="10%" align="center" border="0" cellpadding="0" cellspacing="0" style="padding:30px 0 0;">
                    <tr>
                      <td><a href="#" onclick="javascript:printDiv();" style="background-color:#09F; color:#fff; text-align:center; padding:10px 15px; text-decoration:none; margin-right:5px;">Print</a></td>
                      <td>
                       <a href="<?php echo base_url()?>result/pdc726" style="background-color:#ddd; color:#000; text-align:center; padding:10px 15px; text-decoration:none;">Back</a>
                      </td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td class="footer" colspan="2" width="43%" valign="middle" height="24">Copyright &copy; 2012. INDIAN INSTITUTE OF BANKING AND FINANCE. All rights reserved. 
              </tr>
            </table>
            <!--Table with Border--></td>
        </tr>
      </table>
      <!--Logo Table--></td>
  </tr>
</table>
<!--Main Outer Table-->

<!-- Print copy start here -->

<div id="print_div" style="display: none;">
<table border="0" cellpadding="0" cellspacing="0" width="900px" align="center" style=" margin-bottom:10px;">
  <tr>
    <td style="padding:10px 0;">
      <!--Logo Table--></td>
  </tr>
  <tr>
    <td><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
        <tr>
          <td style="border:3px solid #619fda;"><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
              <tr>
                <td style="padding:10px 0; border-bottom:3px solid #619fda;"><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                    <tr>
                      <td style="padding-left:20px; vertical-align:top;"><img src="<?php echo  base_url()?>assets/images/iibf_logo_short.png"></td>
                      <td class="login-logo" align="center"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
                        <small>An ISO 21001:2018 Certified </small><br />
                        <span style="font-size:20px;">KOHINOOR CITY, COMMERCIAL - II, TOWER - 1, 2ND FLOOR<BR />
                        KIROL ROAD, KURLA (WEST), MUMBAI - 400 070</span></a></td>
                        <td valign="top">
                        <img src="<?php echo base_url()?>assets/images/register-trademark.png" width="40" height="40" />
                        </td>
                        <td>
                         <img src="<?php echo base_url()?>assets/images/90 Year of Service [IIBF]_Logo _Final_08062017.png" width="100" height="100" />
                        </td>
                    </tr>
                  </table>
                  <!--Logo Table--></td>
              </tr>
              <tr>
                <td style="border-bottom:3px solid #619fda; padding:10px; font-family:'Times New Roman', Times, serif; font-size:15px;">
                	<table width="100%">
                    	<tr>
                        	<td width="75%">
                            	Membership/Registration No: <?php echo $this->session->userdata('result_mem_no')?><br />
                  				<?php echo $user_info->firstname." ".$user_info->middlename." ".$user_info->lastname?> <br />
                  				<?php echo $user_info->address1?><br />
                  				<?php echo $user_info->address2?><br />
								  <?php 
                                        if($user_info->address3 != $user_info->address2 && $user_info->address3!=''){
                                            echo $user_info->address3."<br/>";
                                        }
                                  ?>
								  <?php 
                                        if($user_info->address4 != $user_info->address3 && $user_info->address4!=''){
                                            echo $user_info->address4."<br/>";
                                        }
                                  ?>
                                  <?php 
                                        if($user_info->address5 != $user_info->address4 && $user_info->address5!=''){
                                            echo $user_info->address5."<br/>";
                                        }
                                  ?>
                                  <?php 
                                        if($user_info->address6 != $user_info->address5 && $user_info->address6!=''){
                                            echo $user_info->address6."<br/>";
                                        }
                                  ?>
                  					Pin :<?php echo $user_info->pincode?>
                            </td>
                        </tr>
                    </table>
                </td>
              </tr>
              <tr>
                <td style="border-bottom:3px solid #619fda; padding:5px 10px; font-family: Tahoma, Geneva, sans-serif; font-size:14px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                    <tr>
                      <td>
                      	 <strong>Examination<br />
                         <?php echo preg_replace("/\([^)]+\)/","",$exam_name)."  April 2018";?>
                         
                         </strong>
                         </td>
                      <td style="text-align:right;"><strong>MUMBAI, Dated</strong><br />
                      <?php  echo "26-APR-2018"; ?>
                      </td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td style="padding:10px; font-size:14px; font-family:'Times New Roman', Times, serif; font-weight:bold;">DEAR CANDIDATE,<br />
                  <br />
                  WE HERE UNDER ADVISE YOUR RESULT OF THE ABOVE EXAMINATION </td>
              </tr>
              <tr>
                <td style="padding:20px 0; font-family:'Times New Roman', Times, serif; font-size:14px;"><table style="border-collapse:collapse;" width="90%" align="center">
                    <tbody>
                      <tr style="border: 1px #6699CC solid;">
                        <th style="border: 1px #6699CC solid;font-weight:bold; padding:5px 0;" class="text2" bgcolor="#e2e2e2" align="center">Subject</th>
                        <th style="border: 1px #6699CC solid;font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Marks Obtained</th>
                        <th style="border: 1px #6699CC solid;font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Result</th>
                      </tr>
                      <?php foreach($printrecord as $record){ ?>
                      <tr>
                        <td style="border: 1px #6699CC solid; padding:5px 10px;" class="text2">
							<?php echo $record['subject_name']?>
                        </td>
                        <td style="border: 1px #6699CC solid;" class="text2" align="center">
                        	<?php
								if($record['status'] == 'A'){
									echo "-";	
								}
								if($record['status'] == 'F' || $record['status'] == 'P'){
									 echo $record['marks'];
								}
							
							 ?>
                        </td>
                        <td style="border: 1px #6699CC solid;" class="text21" align="center">
                        	<?php 
								if($record['status'] == 'P'){
									echo "PASS";
								}elseif($record['status'] == 'F'){
									echo "FAIL";
								}elseif($record['status'] == 'A'){
									echo "ABSENT";
								}elseif($record['status'] == 'C'){
									echo "CREDIT TRANSFER";	
								}elseif($record['status'] == 'E'){
									echo "EXEMPTED ";	
								}
							?>
                        </td>
                      </tr>
                      <?php }?>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
				  <?php if(in_array($exam_code,array("34"))){?>
                  <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                   <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 60</p>
                   <div style="float:right;margin-right:40px;text-align:center">
               <p><strong>Yours Faithfully</strong></p>
               <img src="<?php echo base_url();?>assets/images/iibf_officer_sign.jpg" style="max-width:40%" />
               <p>Joint Director<br />Examinations</p>
               </div>
                    </td>
                  <?php }?>
                  <?php if(in_array($exam_code,array("58"))){?>
                  <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
               <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
               <div style="float:right;margin-right:40px;text-align:center">
               <p><strong>Yours Faithfully</strong></p>
               <img src="<?php echo base_url();?>assets/images/iibf_officer_sign.jpg" style="max-width:40%" />
               <p>Joint Director<br />Examinations</p>
               </div>
                </td>
                  <?php }?>
                  <?php if(in_array($exam_code,array("160"))){?>
                  <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
               <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">Passing marks - 50</p>
               <div style="float:right;margin-right:40px;text-align:center">
               <p><strong>Yours Faithfully</strong></p>
               <img src="<?php echo base_url();?>assets/images/iibf_officer_sign.jpg" style="max-width:40%" />
               <p>Joint Director<br />Examinations</p>
               </div>
                </td>
                  <?php }?>
                  <?php if(in_array($exam_code,array("177"))){?>
                  <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
               <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">Passing marks - 50</p>
               <div style="float:right;margin-right:40px;text-align:center">
               <p><strong>Yours Faithfully</strong></p>
               <img src="<?php echo base_url();?>assets/images/iibf_officer_sign.jpg" style="max-width:40%" />
               <p>Joint Director<br />Examinations</p>
               </div>
                </td>
                  <?php }?>
              </tr>
              <tr>
                <td style="padding:10px; font-size:14px; border-bottom:3px solid #619fda;">
                
                <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">This result advice is issued subject to what is stated below which may please be noted</p>
                  <ol class="result">
                    <li>As per the rules, no request for revaluation of answer paper will be entertained. The facility of verification of marks for online mode will not be available for Objective Type (Multiple Choice Questions) examinations since the evaluation is computerized. </li>
                    <li>In case a candidate has adopted unfair practices, the declaration of results is only provisional and is subject to cancellation in the event of adoption of unfair practices being established. </li>
                    <li>The result of this examination shall also be liable to be cancelled in the event of it being established that the candidate has adopted unfair practices in any previous examination. </li>
                    <li><strong>Candidates passing the examination will be issued a Certificate</strong></li>
                    <li>For Important Announcements/Notices & Examination schedule/rules/syllabus visit Institute's website: www.iibf.org.in </li>
                  </ol></td>
              </tr>
              <tr>
                <td style="padding:20px 0;"><table style="border-collapse:collapse;" width="90%" align="center">
                    <tbody>
                      <tr>
                        <td colspan="4" style="border:1px;border-style:solid;border-color:#6699CC;text-align:center; padding:5px 0;" bgcolor="#e2e2e2">Please Write to us at the following E-mail in case of any change in your address:</td>
                      </tr>
                      <tr>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Members Support Services</td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre <br>
                          Eastern Zone</td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre<br>
                          Southern Zone</td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre<br>
                          Northern Zone</td>
                      </tr>
                      <tr>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;"><a style="text-decoration:underline" href="mailto:care@iibf.org.in">care@iibf.org.in</a></td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;"><a style="text-decoration:underline" href="mailto:iibfez@iibf.org.in">iibfez@iibf.org.in</a></td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;"><a style="text-decoration:underline" href="mailto:iibfsz@iibf.org.in">iibfsz@iibf.org.in</a></td>
                        <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;"><a style="text-decoration:underline" href="mailto:iibfnz@iibf.org.in">iibfnz@iibf.org.in</a></td>
                      </tr>
                    </tbody>
                  </table>
                  </td>
              </tr>
              <tr>
                <td class="footer" colspan="2" width="43%" valign="middle" height="24">Copyright &copy; 2012. INDIAN INSTITUTE OF BANKING AND FINANCE. All rights reserved. 
              </tr>
            </table>
            <!--Table with Border--></td>
        </tr>
      </table>
      <!--Logo Table--></td>
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
