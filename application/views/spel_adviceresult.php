<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
      <title>Welcome to Indian Institute of Banking &amp; Finance</title>
      <link href="<?php echo  base_url()?>assets/css/exam-result.css" rel="stylesheet" type="text/css" />
      <script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
   </head>
   <body>
      <table border="0" cellpadding="0" cellspacing="0" width="900px" align="center" style=" margin-bottom:10px;">
         <tr>
            <td style="padding:10px 0;">
               <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
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
               <!--Logo Table-->
            </td>
         </tr>
         <tr>
            <td>
               <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                  <tr>
                     <td style="border:3px solid #619fda;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                           <tr>
                              <td style="padding:10px 0; border-bottom:3px solid #619fda;">
                                 <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                    <tr>
                                       <td style="padding-left:20px; vertical-align:top;"><img src="<?php echo  base_url()?>assets/images/iibf_logo_short.png"></td>
                                       <td class="login-logo" align="center"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<sup>®</sup><br>
                                          <small>An ISO 21001:2018 Certified </small><br />
                                          <span style="font-size:20px;">KOHINOOR CITY, COMMERCIAL - II, TOWER - 1, 2ND FLOOR<BR />
                                          KIROL ROAD, KURLA (WEST), MUMBAI - 400 070</span></a>
                                       </td>
                                       <td valign="top">
                                          <!--    <img src="<?php echo base_url()?>assets/images/register-trademark.png" width="40" height="40" />-->
                                       </td>
                                       <td>
                                          <img src="<?php echo base_url()?>assets/images/90 Year of Service [IIBF]_Logo _Final_08062017.png" width="100" height="100" />
                                       </td>
                                    </tr>
                                 </table>
                                 <!--Logo Table-->
                              </td>
                           </tr>
                           <tr>
                              <!-- remove Final Marks Sheet heading by Nikita as per clients requirment.  -->
                              <!-- <td style="text-align:center; padding:10px 0; font-size:16px; font-weight:bold; color:#b1b1b1;">Final Mark Sheet</td>  -->
                              <!-- (only Final marksheet needed as pe client requirment) -->
                              <!-- <td style="text-align:center; padding:10px 0; font-size:16px; font-weight:bold; color:#b1b1b1;">Final Mark Sheet - <?php //echo preg_replace("/\([^)]+\)/","",$exam_name);?> Examination </td> -->

                           </tr>
                           <tr>
                              <td style="border-bottom:3px solid #619fda; padding:10px; font-family:'Times New Roman', Times, serif; font-size:15px;">
                                 <table width="100%" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <td rowspan="2" width="75%">
                                          <?php ?>
                                          Membership/Registration No: <?php echo $this->session->userdata('result_mem_no')?><br />
                                          <?php 
                                             $firstname=$middlename=$lastname='';
                                              if(isset($user_info->firstname)){
                                              $firstname=$user_info->firstname;
                                              }
                                             if(isset($user_info->middlename)){
                                              $middlename=$user_info->middlename;
                                             }
                                             if(isset($user_info->lastname)){
                                              $lastname=$user_info->lastname;
                                             }
                                             echo '<strong>'.$firstname." ".$middlename." ".$lastname.'</strong>';
                                             ?> 
                                          <br />
                                          <?php 
                                             if(isset($user_info->address1)){
                                              echo $user_info->address1;
                                             }
                                             ?>
                                          <br />
                                          <?php 
                                             if(isset($user_info->address2)){
                                              echo $user_info->address2;
                                             }
                                             ?>
                                          <br />
                                          <?php 
                                             if(isset($user_info->address3)){
                                              if($user_info->address3 != $user_info->address2 && $user_info->address3!=''){
                                                 echo $user_info->address3."<br/>";
                                              }
                                             }?>
                                          <?php 
                                             if(isset($user_info->address4)){
                                              if($user_info->address4 != $user_info->address3 && $user_info->address4!=''){
                                                 echo $user_info->address4."<br/>";
                                              }
                                             } ?>
                                          <?php 
                                             if(isset($user_info->address5)){
                                               if($user_info->address5 != $user_info->address4 && $user_info->address5!=''){
                                                  echo $user_info->address5."<br/>";
                                               }
                                             } ?>
                                          <?php 
                                             if(isset($user_info->address6)){
                                               if($user_info->address6 != $user_info->address5 && $user_info->address6!=''){
                                                   echo $user_info->address6."<br/>";
                                               }
                                             } ?>
                                          <?php 
                                             if(isset($user_info->pincode)){
                                              echo  "Pin :". $user_info->pincode;
                                             }
                                             ?>
                                       </td>
                                    <!-- <td align="center" valign="top">
                                       <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('result_mem_no'),'p');?><?php echo '?'.time(); ?>" style="max-width:40%; border:2px solid #ddd;" />
                                    </td> -->
                                 </tr>
                                 <!-- <tr>
                                    <td align="center" valign="top"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('result_mem_no'),'s');?><?php echo '?'.time(); ?>" style="max-width:40%" /></td>
                                 </tr> -->
                              </table>
                                
                              </td>
                           </tr>
                           <tr>
                              <td style="border-bottom:3px solid #619fda; padding:5px 10px; font-family: Tahoma, Geneva, sans-serif; font-size:14px;">
                                 <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                    <tr>
                                       <td>
                                          <?php
                                             $exam_conduct_date = explode("/",$exam_conduct);
                                             if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008  || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 1017 || $exam_code == 1019 || $exam_code == 1020){
                                             ?>
                                          <strong>Remote Proctored Examination<br />
                                          <?php }else{?>
                                          <strong>Examination<br />
                                          <?php }?>
                                          <?php 
                                             //  echo preg_replace("/\([^)]+\)/","",$exam_name)."- ". $exam_conduct_date[0]." ".$exam_conduct_date[1];//Removed date as per client request 24-11-2023.
                                             // echo preg_replace("/\([^)]+\)/","",$exam_name);
                                             echo $exam_name;
                                             ?>
                                          </strong>
                                       </td>
                                       <td style="text-align:right;"><strong>MUMBAI, Dated</strong><br />
                                          <?php echo $result_date;?>
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td style="padding:10px; font-size:14px; font-family:'Times New Roman', Times, serif; font-weight:bold;">DEAR CANDIDATE,<br />
                                 <br />
                                 WE HERE UNDER ADVISE YOUR RESULT OF THE ABOVE EXAMINATION 
                              </td>
                           </tr>
                           <tr>
                              <td style="padding:20px 0; font-family:'Times New Roman', Times, serif; font-size:14px;">
                                 <table style="border-collapse:collapse;" width="90%" align="center">
                                    <tbody>
                                       <tr style="border: 1px #6699CC solid;">
                                          <th style="border: 1px #6699CC solid;font-weight:bold; padding:5px 0;" class="text2" bgcolor="#e2e2e2" align="center">Subject</th>
                                          <th style="border: 1px #6699CC solid;font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Marks Obtained</th>
                                          <th style="border: 1px #6699CC solid;font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Result</th>
                                       </tr>
                                       <?php 
                                          $scnt = 0;
                                          //echo '<pre>';
                                          //print_r($record);
                                          foreach($record as $record){ 
                                          ?>
                                       <tr>
                                          <td style="border: 1px #6699CC solid; padding:5px 10px;" class="text2">
                                             <?php echo $record['subject_name']?>
                                          </td>
                                          <td style="border: 1px #6699CC solid;" class="text2" align="center">
                                             <?php
                                                if($record['status'] == 'A' || $record['status'] == 'E'){
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
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <?php 
                                 if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1012 || $exam_code == 1014 || $exam_code == 528 || $exam_code == 529 || $exam_code == 534 ){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 60</p>
                                 <div style="float:right;margin-right:40px;text-align:center">
                                    <p><strong>Yours Faithfully</strong></p>
                                    <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                    <p>Joint Director<br />Examinations</p>
                                 </div>
                              </td>
                              <?php }?>
                              <?php 
                                 if( $exam_code == 530 || $exam_code == 531 ){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 25</p>
                                 <div style="float:right;margin-right:40px;text-align:center">
                                    <p><strong>Yours Faithfully</strong></p>
                                    <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                    <p>Joint Director<br />Examinations</p>
                                 </div>
                              </td>
                              <?php }?>
                              <?php if($exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1013){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                              </td>
                              <?php }?>
                              <?php if($exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 ){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                 Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level -2). Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                              </td>
                              <?php }?>
                              <?php 
                                 if(in_array($exam_code,array("20"))){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                 Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level -2). Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                              </td>
                              <?php }?>
                              <?php if($exam_code == '34'){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 60</p>
                              </td>
                              <?php }?>
                              <?php if($exam_code == '58'){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                              </td>
                              <?php }?>
                              <?php if($exam_code == '59'){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                 Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level -2). Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                              </td>
                              <?php }?>
                              <?php if($exam_code == '74'){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                              </td>
                              <?php }?>
                              <?php if($exam_code == '81'){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                 Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning . Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                              </td>
                              <?php }?>
                              <?php if($exam_code == '164'){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                 Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level -2). Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                              </td>
                              <?php }?>
                              <?php if($exam_code == '8'){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p>MINIMUM MARKS FOR PASS IN SUBJECT-50.</p>
                                 <p>CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN A SINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED.
                                    Certificate for completion will be issued only after successfully completing the project work as per the rules of the examination.
                                 </p>
                              </td>
                              <?php }?>
                              <?php if($exam_code == '18'){?> 
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p>MINIMUM MARKS FOR PASS IN SUBJECT-50.</p>
                                 <p>CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN A SINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED.
                                    Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level-2). Please note that as per the time limit rule candidate need to complete the Classroom Learning within 15 months from the above result declaration date of Level-1 examination.
                                 </p>
                              </td>
                              <?php }?>
                              <?php if($exam_code == '19'){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p>MINIMUM MARKS FOR PASS IN SUBJECT-50.</p>
                                 <p>CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN A SINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED.
                                    Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level-2). Please note that as per the time limit rule candidate need to complete the Classroom Learning within 15 months from the above result declaration date of Level-1 examination.
                                 </p>
                              </td>
                              <?php }?>
                              <?php if(in_array($exam_code,array("32","33","47","52","11","51"))){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p>MINIMUM MARKS FOR PASS IN SUBJECT-50</p>
                                 <p>CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN A SINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED</p>
                              </td>
                              <?php }?>
                              <?php if(in_array($exam_code,array("151","153","156","158"))){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">Passing marks - 50</p>
                                 <?php if(in_array($exam_code,array("151","153","156","158"))){?>
                                 <p>"CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN ASINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED"</p>
                                 <?php }?>
                              </td>
                              <?php }?>
                              <?php if(in_array($exam_code,array('50','78','79','148','149','154','160','162','163','165','166','177','175'))){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                              </td>
                              <?php } ?>
                              <?php if(in_array($exam_code,array('24','25','26','135','161'))){?>
                              <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                 <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 60</p>
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
                                 </ol>
                              </td>
                           </tr>
                           <tr>
                              <td style="padding:20px 0;">
                                 <table style="border-collapse:collapse;" width="90%" align="center">
                                    <tbody>
                                       <tr>
                                          <td colspan="4" style="border:1px;border-style:solid;border-color:#6699CC;text-align:center; padding:5px 0;" bgcolor="#e2e2e2">Please Write to us at the following E-mail in case of any change in your address:</td>
                                       </tr>
                                       <tr>
                                          <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Members Support Services</td>
                                          <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre <br>
                                             Eastern Zone
                                          </td>
                                          <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre<br>
                                             Southern Zone
                                          </td>
                                          <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre<br>
                                             Northern Zone
                                          </td>
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
                                          <?php 
                                             if($exam_code == 1015){ ?>
                                          <a href="<?php echo base_url()?>marksheet/naarresult/<?php echo base64_encode($exam_period);?>" style="background-color:#ddd; color:#000; text-align:center; padding:10px 15px; text-decoration:none;">Back</a>
                                          <?php }else{
                                             if($exam_period > 807 || ($exam_period == 706 || $exam_period == 707  || $exam_period == 708 || $exam_period == 709 || $exam_period == 710 || $exam_period == 711 || $exam_period == 712 || $exam_period == 713 || $exam_period == 714) ){
                                             ?>
                                          <a href="<?php echo base_url()?>marksheet/dipcertresult/<?php echo base64_encode($exam_period);?>" style="background-color:#ddd; color:#000; text-align:center; padding:10px 15px; text-decoration:none;">Back</a>
                                          <?php }elseif($exam_period != 777){?>
                                          <a href="<?php echo base_url()?>marksheet/dipcert<?php echo base64_encode($exam_period);?>" style="background-color:#ddd; color:#000; text-align:center; padding:10px 15px; text-decoration:none;">Back</a>
                                          <?php }else{?>
                                          <a href="<?php echo base_url()?>marksheet/dipcertresult/<?php echo base64_encode($exam_period);?>" style="background-color:#ddd; color:#000; text-align:center; padding:10px 15px; text-decoration:none;">Back</a>
                                          <?php } }?>
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td class="footer" colspan="2" width="43%" valign="middle" height="24">Copyright &copy; 2012. INDIAN INSTITUTE OF BANKING AND FINANCE. All rights reserved. 
                           </tr>
                        </table>
                        <!--Table with Border-->
                     </td>
                  </tr>
               </table>
               <!--Logo Table-->
            </td>
         </tr>
      </table>
      <!--Main Outer Table-->
      <!-- Print copy start here -->
      <div id="print_div" style="display: none;">
         <table border="0" cellpadding="0" cellspacing="0" width="900px" align="center" style=" margin-bottom:10px;">
            <tr>
               <td style="padding:10px 0;">
                  <!--Logo Table-->
               </td>
            </tr>
            <tr>
               <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                     <tr>
                        <td style="border:3px solid #619fda;">
                           <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                              <tr>
                                 <td style="padding:10px 0; border-bottom:3px solid #619fda;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                       <tr>
                                          <td style="padding-left:20px; vertical-align:top;"><img src="<?php echo  base_url()?>assets/images/iibf_logo_short.png"></td>
                                          <td class="login-logo" align="center"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
                                             <small>An ISO 21001:2018 Certified </small><br />
                                             <span style="font-size:20px;">KOHINOOR CITY, COMMERCIAL - II, TOWER - 1, 2ND FLOOR<BR />
                                             KIROL ROAD, KURLA (WEST), MUMBAI - 400 070</span></a>
                                          </td>
                                          <td valign="top">
                                             <img src="<?php echo base_url()?>assets/images/register-trademark.png" width="40" height="40" />
                                          </td>
                                          <td>
                                             <img src="<?php echo base_url()?>assets/images/90 Year of Service [IIBF]_Logo _Final_08062017.png" width="100" height="100" />
                                          </td>
                                       </tr>
                                    </table>
                                    <!--Logo Table-->
                                 </td>
                              </tr>
                              <tr>
                                 <td style="border-bottom:3px solid #619fda; padding:10px; font-family:'Times New Roman', Times, serif; font-size:15px;">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <td rowspan="2" width="75%">
                                          <?php ?>
                                          Membership/Registration No: <?php echo $this->session->userdata('result_mem_no')?><br />
                                          <?php 
                                             $firstname=$middlename=$lastname='';
                                              if(isset($user_info->firstname)){
                                              $firstname=$user_info->firstname;
                                              }
                                             if(isset($user_info->middlename)){
                                              $middlename=$user_info->middlename;
                                             }
                                             if(isset($user_info->lastname)){
                                              $lastname=$user_info->lastname;
                                             }
                                             echo '<strong>'.$firstname." ".$middlename." ".$lastname.'</strong>';
                                             ?> 
                                          <br />
                                          <?php 
                                             if(isset($user_info->address1)){
                                              echo $user_info->address1;
                                             }
                                             ?>
                                          <br />
                                          <?php 
                                             if(isset($user_info->address2)){
                                              echo $user_info->address2;
                                             }
                                             ?>
                                          <br />
                                          <?php 
                                             if(isset($user_info->address3)){
                                              if($user_info->address3 != $user_info->address2 && $user_info->address3!=''){
                                                 echo $user_info->address3."<br/>";
                                              }
                                             }?>
                                          <?php 
                                             if(isset($user_info->address4)){
                                              if($user_info->address4 != $user_info->address3 && $user_info->address4!=''){
                                                 echo $user_info->address4."<br/>";
                                              }
                                             } ?>
                                          <?php 
                                             if(isset($user_info->address5)){
                                               if($user_info->address5 != $user_info->address4 && $user_info->address5!=''){
                                                  echo $user_info->address5."<br/>";
                                               }
                                             } ?>
                                          <?php 
                                             if(isset($user_info->address6)){
                                               if($user_info->address6 != $user_info->address5 && $user_info->address6!=''){
                                                   echo $user_info->address6."<br/>";
                                               }
                                             } ?>
                                          <?php 
                                             if(isset($user_info->pincode)){
                                              echo  "Pin :". $user_info->pincode;
                                             }
                                             ?>
                                       </td>
                                    <!-- <td align="center" valign="top">
                                       <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('result_mem_no'),'p');?><?php echo '?'.time(); ?>" style="max-width:40%; border:2px solid #ddd;" />
                                    </td> -->
                                 </tr>
                                 <!-- <tr>
                                    <td align="center" valign="top"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('result_mem_no'),'s');?><?php echo '?'.time(); ?>" style="max-width:40%" /></td>
                                 </tr> -->
                              </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td style="border-bottom:3px solid #619fda; padding:5px 10px; font-family: Tahoma, Geneva, sans-serif; font-size:14px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                       <tr>
                                          <td>
                                             <?php
                                                if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 1017 || $exam_code == 1019 || $exam_code == 1020){
                                                ?>
                                             <strong>Remote Proctored Examination<br />
                                             <?php }else{?>
                                             <strong>Examination<br />
                                             <?php }?>
                                             <?php 
                                                /*if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 1017 || $exam_code == 1019 || $exam_code == 1020){
                                                  $time=strtotime($result_date);
                                                                      $month=date("F",$time);
                                                                      $year=date("Y",$time);
                                                  echo preg_replace("/\([^)]+\)/","",$exam_name)."- ". $month." ".$year;
                                                  //echo preg_replace("/\([^)]+\)/","",$exam_name);
                                                }else{*/
                                                //   echo preg_replace("/\([^)]+\)/","",$exam_name);
                                                //}
                                                echo $exam_name;
                                                ?>
                                             </strong>
                                          </td>
                                          <td style="text-align:right;"><strong>MUMBAI, Dated</strong><br />
                                             <?php echo $result_date;?>
                                          </td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td style="padding:10px; font-size:14px; font-family:'Times New Roman', Times, serif; font-weight:bold;">DEAR CANDIDATE,<br />
                                    <br />
                                    WE HERE UNDER ADVISE YOUR RESULT OF THE ABOVE EXAMINATION 
                                 </td>
                              </tr>
                              <tr>
                                 <td style="padding:20px 0; font-family:'Times New Roman', Times, serif; font-size:14px;">
                                    <table style="border-collapse:collapse;" width="90%" align="center">
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
                                                   if($record['status'] == 'A'  || $record['status'] == 'E'){
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
                                    </table>
                                 </td>
                              </tr>
                              <tr>
                                 <?php 
                                    if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1012 || $exam_code == 1014 || $exam_code == 528 || $exam_code == 529 || $exam_code == 534 ){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 60</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php 
                                    if( $exam_code == 530 || $exam_code == 531){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 25</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1013 || $exam_code == 1017){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                    Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level -2). Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if(in_array($exam_code,array("20"))){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">Passing marks - 50</p>
                                    Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level -2). Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if(in_array($exam_code,array("34"))){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 60</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if(in_array($exam_code,array("58"))){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == '59'){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">Passing marks - 50</p>
                                    Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level -2). Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == '74'){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == '81'){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">Passing marks - 50</p>
                                    Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning . Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == '164'){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">Passing marks - 50</p>
                                    Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level -2). Please note that as per time limit rule candidates need to complete the Classroom Learning within 15 months from the above result declaration date of Level - 1 examination
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == '8'){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p>MINIMUM MARKS FOR PASS IN SUBJECT-50.</p>
                                    <p>CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN A SINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED.
                                       Certificate for completion will be issued only after successfully completing the project work as per the rules of the examination.
                                    </p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == '18'){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p>MINIMUM MARKS FOR PASS IN SUBJECT-50.</p>
                                    <p>CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN A SINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED.
                                       Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level-2). Please note that as per the time limit rule candidate need to complete the Classroom Learning within 15 months from the above result declaration date of Level-1 examination.
                                    </p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if($exam_code == '19'){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p>MINIMUM MARKS FOR PASS IN SUBJECT-50.</p>
                                    <p>CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN A SINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED.
                                       Candidates who have completed Level-1 of the examination are required to enrol for Classroom Learning (Level-2). Please note that as per the time limit rule candidate need to complete the Classroom Learning within 15 months from the above result declaration date of Level-1 examination.
                                    </p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if(in_array($exam_code,array("32","33","47","52","11","51"))){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p>MINIMUM MARKS FOR PASS IN SUBJECT-50</p>
                                    <p>CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN A SINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if(in_array($exam_code,array("151","153","156","158"))){?>
                                 <td style="padding:0px 0 25px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">Passing marks - 50</p>
                                    <?php if(in_array($exam_code,array("151","153","156","158"))){?>
                                    <p>"CANDIDATES SECURING ATLEAST 45 MARKS IN EACH SUBJECT WITH AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS IN ASINGLE ATTEMPT WILL ALSO BE DECLARED AS HAVING COMPLETED"</p>
                                    <?php }?>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if(in_array($exam_code,array('50','78','79','148','149','154','160','162','177','175'))){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if(in_array($exam_code,array('24','25','26','135','161'))){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 60</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
                                       <p>Joint Director<br />Examinations</p>
                                    </div>
                                 </td>
                                 <?php }?>
                                 <?php if(in_array($exam_code,array('163','165','166'))){?>
                                 <td style="padding:0px 0 15px 10px; font-family:'Times New Roman', Times, serif; font-size:13px;  border-bottom:3px solid #619fda;font-weight:bold">
                                    <p style="font-family:'Times New Roman', Times, serif; font-weight:bold;  font-size:16px;">MINIMUM MARKS FOR PASS IN SUBJECT - 50</p>
                                    <div style="float:right;margin-right:40px;text-align:center">
                                       <p><strong>Yours Faithfully</strong></p>
                                       <img src="<?php echo base_url();?>assets/images/iibf_officer_sign2.png" style="max-width:35%" />
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
                                    </ol>
                                 </td>
                              </tr>
                              <tr>
                                 <td style="padding:20px 0;">
                                    <table style="border-collapse:collapse;" width="90%" align="center">
                                       <tbody>
                                          <tr>
                                             <td colspan="4" style="border:1px;border-style:solid;border-color:#6699CC;text-align:center; padding:5px 0;" bgcolor="#e2e2e2">Please Write to us at the following E-mail in case of any change in your address:</td>
                                          </tr>
                                          <tr>
                                             <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Members Support Services</td>
                                             <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre <br>
                                                Eastern Zone
                                             </td>
                                             <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre<br>
                                                Southern Zone
                                             </td>
                                             <td style="border:1px;border-style:solid;border-color:#6699CC; padding:5px; text-align:center;">Professional Development Centre<br>
                                                Northern Zone
                                             </td>
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
                           <!--Table with Border-->
                        </td>
                     </tr>
                  </table>
                  <!--Logo Table-->
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