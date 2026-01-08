<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Welcome to Indian Institute of Banking &amp; Finance</title>
      <link href="<?php echo base_url()?>assets/css/exam-result.css" rel="stylesheet" type="text/css" />
      <script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
   </head>
   <body>
      <table border="0" cellpadding="0" cellspacing="0" width="900px" align="center" style=" margin-bottom:10px;">
      <tr>
         <td style="padding-top:15px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
               <tr>
                  <td align="right">
                     <a onclick="javascript:printDiv();">
                     <img src="<?php echo  base_url()?>assets/images/print-icon.png" style="cursor:pointer">
                     </a>
                  </td>
               </tr>
               <tr>
                  <td style="border:3px solid #619fda;">
                     <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                        <tr>
                           <td style="padding:10px 0; border-bottom:3px solid #619fda;">
                              <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                 <tr>
                                    <td style="padding-left:20px; vertical-align:top;"><img src="<?php echo base_url()?>assets/images/iibf_logo_short.png"></td>
                                    <td class="login-logo" align="center"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>An ISO 21001:2018 Certified </small><br /><span style="font-size:20px;">KOHINOOR CITY, COMMERCIAL - II, TOWER - 1, 2ND FLOOR<BR />KIROL ROAD, KURLA (WEST), MUMBAI - 400 070</span></a></td>
                                    <td><img src="<?php echo base_url()?>assets/images/90 Year of Service [IIBF]_Logo _Final_08062017.png" width="100" height="100" /></td>
                                 </tr>
                              </table>
                              <!--Logo Table-->
                           </td>
                        </tr>
                        <?php if($is_conso == ''){?> 
                        <tr>
                           <td style="text-align:center; padding:10px 0; font-size:16px; font-weight:bold; color:#b1b1b1;">Due to technical issue consolidated marks sheet not available. The same will be available by next week.</td>
                        </tr>
                        <?php }else{?>
                        <tr>
                           <td style="text-align:center; padding:10px 0; font-size:16px; font-weight:bold; color:#b1b1b1;">Consolidated Mark Sheet - <?php echo preg_replace("/\([^)]+\)/","",$exam_name);?> Examination </td>
                        </tr>
                        <tr>
                           <td style="border-bottom:1px solid #619fda; padding:10px; font-family:Verdana, Geneva, sans-serif; font-size:13px;">
                              <table width="100%" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <td rowspan="2" width="75%">
                                       Membership/Registration No: <?php echo $this->session->userdata('result_mem_no')?><br /><br />
                                       <strong><?php echo $this->session->userdata('result_name')?></strong> <br /> <BR />
                                       <?php echo $user_info->add_1?><br />
                                       <?php echo $user_info->add_2?><br />
                                       <?php 
                                          if($user_info->add_3 != $user_info->add_2 && $user_info->add_3!=''){
                                             echo $user_info->add_3."<br/>";
                                                          }
                                                    ?>
                                       <?php 
                                          if($user_info->add_4 != $user_info->add_3 && $user_info->add_4!=''){
                                             echo $user_info->add_4."<br/>";
                                          }
                                          ?>
                                       <?php 
                                          if($user_info->add_5 != $user_info->add_4 && $user_info->add_5!=''){
                                          echo $user_info->add_5."<br/>";
                                          }
                                          ?>
                                       <?php 
                                          if($user_info->add_6 != $user_info->add_5 && $user_info->add_6!=''){
                                          echo $user_info->add_6."<br/>";
                                          }
                                          ?>
                                       Pin :<?php echo $user_info->pin_code?>
                                    </td>
                                    <td align="center" valign="top">
                                       <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('result_mem_no'),'p');?><?php echo '?'.time(); ?>" style="max-width:40%; border:2px solid #ddd;" />
                                    </td>
                                 </tr>
                                 <tr>
                                    <td align="center" valign="top"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('result_mem_no'),'s');?><?php echo '?'.time(); ?>" style="max-width:40%" /></td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td style=" font-family: Tahoma, Geneva, sans-serif; font-size:13px;">
                              <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                 <tr>
                                    <td style="border-right:1px solid #28aae2;  padding:5px 10px;"><strong> &nbsp;&nbsp;&nbsp;This is to certify that <?php echo $this->session->userdata('result_name'); ?> has passed <?php echo preg_replace("/\([^)]+\)/","",$exam_name);?> Examination.Following are the details of  the same </strong></td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td style=" font-family: Tahoma, Geneva, sans-serif; font-size:13px;">
                              <table width="98%">
                                 <tr>
                                    <td>&nbsp;<strong> <?php echo preg_replace("/\([^)]+\)/","",$exam_name).' '.'Examination';?> </strong></td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td style="padding:20px 0; font-family:Verdana, Geneva, sans-serif; font-size:12px;">
                              <table style="border-collapse:collapse;" width="98%" align="center">
                                 <tbody>
                                    <tr style="border: 1px #6699CC solid;">
                                       <th style="border: 1px #6699CC solid; font-weight:bold; padding:5px 0;" class="text2" bgcolor="#e2e2e2" align="center">Subject</th>
                                       <th style="border: 1px #6699CC solid; font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Marks Obtained</th>
                                       <th style="border: 1px #6699CC solid; font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center" width="5%">*</th>
                                       <th style="border: 1px #6699CC solid; font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Date of Examination</th>
                                       <th style="border: 1px #6699CC solid; font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Date of Result Declaration</th>
                                    </tr>
                                    <?php foreach($result as $result){?>
                                    <tr>
                                       <td style="border: 1px #6699CC solid; padding:5px 10px;" class="text2"><?php echo $result->subject_name;?></td>
                                       <td style="border: 1px #6699CC solid;" class="text2" align="center"><?php if($result->marks) echo $result->marks; else echo'0';?></td>
                                       <td style="border: 1px #6699CC solid;" class="text21" align="center"><?php if($result->result_flag == 'P') {echo 'PASS' ; }else if($result->result_flag == 'F') {echo 'FAIL' ; }else if($result->result_flag == 'A') {echo 'ABSENT' ; }else if($result->result_flag == 'C') {echo 'Credit Transfer' ; }else if($result->result_flag == 'E') {echo '* P=PASS, E=Qualification Exemption, C=Credit Transfer ' ; }else { echo $result->result_flag;} ?></td>
                                       <td style="border: 1px #6699CC solid;" class="text2" align="center"><?php 
                                          $hold = explode("-",$result->exam_hold_on);
                                          if(count($hold) == 3 && !is_numeric($hold[1]))
                                          {
                                             $dateval= $hold[0]."-".$hold[1]."-".$hold[2];
                                             echo date('d-M-Y',strtotime($dateval));
                                          }
                                          
                                          if(count($hold) == 3 && is_numeric($hold[1]))
                                          {
                                             $dateval= $hold[2]."-".$hold[1]."-".$hold[0];
                                             echo date('d-M-Y',strtotime($dateval));
                                          }
                                                                  
                                          ?>
                                       </td>
                                       <td style="border: 1px #6699CC solid;" class="text21" align="center">
                                          <?php
                                             /*$r = explode("-",$result->exam_result_date);
                                             $rd= $r[0]."-".$r[1]."-20".$r[2];
                                             echo date('d-M-Y',strtotime($rd));*/
                                             echo date('d-M-Y',strtotime($result->exam_result_date));
                                             ?>
                                       </td>
                                    </tr>
                                    <?php }?>
                                    <tr>
                                       <td style="border: 1px #6699CC solid; padding:5px 10px;" class="text2" colspan="5"><strong>* P=PASS, E=Qualification Exemption, C=Credit Transfer</strong> </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td style="border-bottom:1px solid #619fda; padding:10px; font-family:Verdana, Geneva, sans-serif; font-size:12px;">
                              <table width="100%" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <td style="border-bottom:1px solid #619fda; padding:10px; font-family:Verdana, Geneva, sans-serif; font-size:12px;">
                                       <table width="100%" cellpadding="0" cellspacing="0">
                                          <tr>
                                             <td  width="90%" valign="top" style="line-height:16px; color:#777"><strong>Examination Passing Criteria <br />
                                                <br />
                                                <?php if($this->session->userdata('result_examcode') != 19){?>
                                                Minimum Marks for pass is - 50 out of 100.<br />
                                                <?php }elseif($this->session->userdata('result_examcode') == 19){?>
                                                Minimum Marks for pass is - 60 out of 100.<br />
                                                <?php }?>
                                                <br />
                                                Post Examination Training:<br />
                                                a)Physical Classroom Learning <br />
                                                &nbsp;&nbsp;Minimum Marks for Physical Classroom Learning - 25 out of 50<br />
                                                <br />
                                                b)Virtual Classroom Learning<br />
                                                &nbsp;&nbsp;- Minimum marks for pass in Classroom Learning is 50% out of 25.<br />
                                                &nbsp;&nbsp;- Minimum marks for attendance is 50% out of 25<br />
                                                &nbsp;&nbsp;- The overall aggregate (attendance + marks in MCQs) to be obtained by a candidate will therefore be 50 (50%. However, if a candidate gets overall 50% with 30 marks in attendance (60%) and 40% (i.e 20 marks) in MCQs, s/he will also be considered as passed. But vice Versa is not permitted. In other words, a candidate should get minimum 20 marks in the MCQs.
                                                <br />
                                                * P = PASS E = Qualification Exemption<br />
                                                <br />
                                                All successful candidates who have completed Examination and Training will be issued a certificate. </strong><br />
                                                <br />
                                             </td>
                                             <td align="center" valign="top">
                                                <?php /*?>
                                                <p><strong>Yours Faithfully</strong></p>
                                                <img src="<?php echo base_url();?>assets/images/iibf_officer_sign.jpg" style="max-width:40%" />
                                                <p>Joint Director<br />Examinations</p>
                                                <?php */?>
                                             </td>
                                          </tr>
                                          <table width="10%" align="center" border="0" cellpadding="0" cellspacing="0" style="padding:30px 0 0;">
                                             <tr>
                                                <td><a href="#" onclick="javascript:printDiv();" style="background-color:#09F; color:#fff; text-align:center; padding:10px 15px; text-decoration:none; margin-right:5px;">Print</a></td>
                                                <td>
                                                   <a href="<?php echo base_url();?>blended_result" style="background-color:#ddd; color:#000; text-align:center; padding:10px 15px; text-decoration:none;">Back</a>
                                                </td>
                                             </tr>
                                          </table>
                                       </table>
                                    </td>
                                 </tr>
                                 <?php }?>
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
            <div id="print_div" style="display: none;">
               <table border="0" cellpadding="0" cellspacing="0" width="900px" align="center" style=" margin-bottom:10px;">
                  <tr>
                     <td style="padding-top:15px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                           <tr>
                              <td style="border:3px solid #619fda;">
                                 <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                    <tr>
                                       <td style="padding:10px 0; border-bottom:3px solid #619fda;">
                                          <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                             <tr>
                                                <td style="padding-left:20px; vertical-align:top;"><img src="<?php echo base_url()?>assets/images/iibf_logo_short.png"></td>
                                                <td class="login-logo" align="center"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>An ISO 21001:2018 Certified </small><br /><span style="font-size:20px;">KOHINOOR CITY, COMMERCIAL - II, TOWER - 1, 2ND FLOOR<BR />KIROL ROAD, KURLA (WEST), MUMBAI - 400 070</span></a></td>
                                             </tr>
                                          </table>
                                          <!--Logo Table-->
                                       </td>
                                    </tr>
                                    <?php if($is_conso == '')
                                       {?>
                                    <tr>
                                       <td style="text-align:center; padding:10px 0; font-size:16px; font-weight:bold; color:#b1b1b1;">You have not passed this examination, so consolidated mark sheet is not available</td>
                                    </tr>
                                    <?php }else
                                       {?>
                                    <tr>
                                       <td style="text-align:center; padding:10px 0; font-size:16px; font-weight:bold; color:#b1b1b1;">Consolidated Mark Sheet - <?php echo preg_replace("/\([^)]+\)/","",$exam_name);?> Examination</td>
                                    </tr>
                                    <tr>
                                       <td style="border-bottom:1px solid #619fda; padding:10px; font-family:Verdana, Geneva, sans-serif; font-size:13px;">
                                          <table width="100%" cellpadding="0" cellspacing="0">
                                             <tr>
                                                <td rowspan="2" width="75%">
                                                   Membership/Registration No: <?php echo $this->session->userdata('result_mem_no')?><br /><br />
                                                   <strong><?php echo $this->session->userdata('result_name')?></strong> <br /> <BR />
                                                   <?php echo $user_info->add_1?><br />
                                                   <?php echo $user_info->add_2?><br />
                                                   <?php 
                                                      if($user_info->add_3 != $user_info->add_2 && $user_info->add_3!=''){
                                                         echo $user_info->add_3."<br/>";
                                                                      }
                                                                ?>
                                                   <?php 
                                                      if($user_info->add_4 != $user_info->add_3 && $user_info->add_4!=''){
                                                         echo $user_info->add_4."<br/>";
                                                      }
                                                      ?>
                                                   <?php 
                                                      if($user_info->add_5 != $user_info->add_4 && $user_info->add_5!=''){
                                                      echo $user_info->add_5."<br/>";
                                                      }
                                                      ?>
                                                   <?php 
                                                      if($user_info->add_6 != $user_info->add_5 && $user_info->add_6!=''){
                                                      echo $user_info->add_6."<br/>";
                                                      }
                                                      ?>
                                                   Pin :<?php echo $user_info->pin_code?>
                                                </td>
                                                <td align="center" valign="top">
                                                   <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('result_mem_no'),'p');?><?php echo '?'.time(); ?>" style="max-width:40%; border:2px solid #ddd;" />
                                                </td>
                                             </tr>
                                             <tr>
                                                <td align="center" valign="top"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('result_mem_no'),'s');?><?php echo '?'.time(); ?>" style="max-width:40%" /></td>
                                             </tr>
                                          </table>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td style="border-bottom:1px solid #619fda; font-family: Tahoma, Geneva, sans-serif; font-size:12px;">
                                          <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                             <tr>
                                                <td style=" font-family: Tahoma, Geneva, sans-serif; font-size:13px;">
                                                   <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                                      <tr>
                                                         <td style="border-right:1px solid #28aae2;  padding:5px 10px;"><strong> &nbsp;&nbsp;&nbsp;This is to certify that <?php echo $this->session->userdata('result_name'); ?> has passed <?php echo preg_replace("/\([^)]+\)/","",$exam_name);?> Examination.Following are the details of  the same </strong></td>
                                                      </tr>
                                                   </table>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style=" font-family: Tahoma, Geneva, sans-serif; font-size:13px;">
                                                   <table width="98%">
                                                      <tr>
                                                         <td>&nbsp;<strong> <?php echo preg_replace("/\([^)]+\)/","",$exam_name).' '.'Examination';?> </strong></td>
                                                      </tr>
                                                   </table>
                                                </td>
                                             </tr>
                                          </table>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td style="padding:20px 0; font-family:Verdana, Geneva, sans-serif; font-size:12px;">
                                          <table style="border-collapse:collapse;" width="98%" align="center">
                                             <tbody>
                                                <tr style="border: 1px #6699CC solid;">
                                                   <th style="border: 1px #6699CC solid; font-weight:bold; padding:5px 0;" class="text2" bgcolor="#e2e2e2" align="center">Subject</th>
                                                   <th style="border: 1px #6699CC solid; font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Marks Obtained</th>
                                                   <th style="border: 1px #6699CC solid; font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center" width="5%">*</th>
                                                   <th style="border: 1px #6699CC solid; font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Date of Examination</th>
                                                   <th style="border: 1px #6699CC solid; font-weight:bold;" class="text2" bgcolor="#e2e2e2" align="center">Date of Result Declaration</th>
                                                </tr>
                                                <?php foreach($printresult as $result){?>
                                                <tr>
                                                   <td style="border: 1px #6699CC solid; padding:5px 10px;" class="text2"><?php echo $result->subject_name;?></td>
                                                   <td style="border: 1px #6699CC solid;" class="text2" align="center"><?php echo $result->marks;?></td>
                                                   <td style="border: 1px #6699CC solid;" class="text21" align="center"><?php if($result->result_flag == 'P') {echo 'PASS' ; }else if($result->result_flag == 'F') {echo 'FAIL' ; }else if($result->result_flag == 'A') {echo 'ABSENT' ; }else if($result->result_flag == 'C') {echo 'Credit Transfer' ; }else if($result->result_flag == 'E') {echo '* P=PASS, E=Qualification Exemption, C=Credit Transfer ' ; }else { echo $result->result_flag;} ?></td>
                                                   <td style="border: 1px #6699CC solid;" class="text2" align="center"><?php 
                                                      $hold = explode("-",$result->exam_hold_on);
                                                      if(count($hold) == 3 && !is_numeric($hold[1]))
                                                      {
                                                         $dateval= $hold[0]."-".$hold[1]."-".$hold[2];
                                                         echo date('d-M-Y',strtotime($dateval));
                                                      }
                                                      
                                                      if(count($hold) == 3 && is_numeric($hold[1]))
                                                      {
                                                         $dateval= $hold[2]."-".$hold[1]."-".$hold[0];
                                                         echo date('d-M-Y',strtotime($dateval));
                                                      }
                                                      ?></td>
                                                   <td style="border: 1px #6699CC solid;" class="text21" align="center">
                                                      <?php
                                                         /*$r = explode("-",$result->exam_result_date);
                                                         $rd= $r[0]."-".$r[1]."-20".$r[2];
                                                         echo date('d-M-Y',strtotime($rd));*/
                                                         echo date('d-M-Y',strtotime($result->exam_result_date));
                                                         ?>
                                                   </td>
                                                </tr>
                                                <?php }?>
                                                <tr>
                                                   <td style="border: 1px #6699CC solid; padding:5px 10px;" class="text2" colspan="5"><strong>* P=PASS, E=Qualification Exemption, C=Credit Transfer</strong> </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td style="border-bottom:1px solid #619fda; padding:10px; font-family:Verdana, Geneva, sans-serif; font-size:12px;">
                                          <table width="100%" cellpadding="0" cellspacing="0">
                                             <tr>
                                                <td  width="75%" valign="top" style="line-height:16px; color:#777">
                                                   <strong>MINIMUM MARKS FOR PASS IN ANY SUBJECT ­ 50
                                                   CANDIDATES SECURING AT LEAST 45 MARKS IN EACH SUBJECT WITH 
                                                   AN AGGREGATE OF 50% MARKS IN ALL THE SUBJECTS OF THE 
                                                   EXAMINATION IN A SINGLE ATTEMPT WOULD BE DECLARED
                                                   AS HAVING COMPLETED THE EXAMINATION. </strong><br /><br />
                                                   In case a candidate has adopted unfair practices in the examination, the declaration of 
                                                   results is only provisional and is subject to cancellation in the event of adoption of
                                                   unfair practices being established.
                                                </td>
                                                <td align="center" valign="top">
                                                   <p><strong>Yours Faithfully</strong></p>
                                                   <img src="<?php echo base_url();?>assets/images/iibf_officer_sign1.png" style="max-width:40%" />
                                                   <p>Joint Director<br />Examinations</p>
                                                </td>
                                             </tr>
                                          </table>
                                       </td>
                                    </tr>
                                    <?php }?>
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
   </body>
</html>
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
<script>
   $(document).ready(function () {
      
      $("body").on("contextmenu",function(e){
           return false;
       });
      });
      
</script>