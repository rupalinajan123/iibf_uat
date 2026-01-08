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

                  <img src="<?php echo base_url()?>assets/images/ninty_year_new.png" width="70" height="70" style="margin-left: 100px; margin-right: -190px;" />

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

                  Admit Letter for <?php echo $member_result->mode?> <?php echo $exam_name;?>

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

                                <img src="<?php echo dra_img_p($member_id);?>" width="100" height="125" />

                              </td>

                              </tr>

                              <tr>

                  <td height="5"></td>

                </tr>

                              <tr>

                              <td>

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

                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Time<sup>@</sup></th>

                      

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

					  		$exam_print_date = explode("-",$subject->exam_date);

							$edate = $exam_print_date[0]."-".$exam_print_date[1]."-".$exam_print_date[2];

							echo date('d-M-Y',strtotime($edate));

					   		//echo $subject->exam_date;

					  ?>

                      </td>

                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->time;?></td>

                      

                    </tr>

                  <?php }?>  

                  </tbody>

                  </table>

                  </td>

                </tr>

                <tr>

                	<td style="text-align:left; line-height:24px; font-size:13px;">

                     @ Candidate should login in the system half an hour before examination start.

                    </td>

                </tr>

                <tr>

                    <td height="10"><strong>Candidate are advised to check Institute's Website, a day before the Examination Date for any important updates/information's regarding the Examination .</strong></td>

                </tr>

                

                <tr>

                <td>

                

                

					<?php if($vcenter == 3){?>

                    <!--<p><strong>Kindly go through the Exam Rules & Regulations carefully before appearing for the Examination
</strong></p>-->

                    <?php }?>

                    <?php if($vcenter == 1){?>

                   <!-- <p>

                    <strong>Kindly go through the Exam Rules & Regulations carefully before appearing for the Examination</strong>

                    </p>-->

                    <?php }?>

                    

                



<p>Please note that you need to appear for examination at the above mentioned Date/Time only.</p>

<?php if($exam_code !=34 && $exam_code !=160 && $exam_code !=58 && $exam_code !=81){?>

<p></p>

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
                
                <p><strong>Kindly go through the Exam Rules & Regulations carefully before appearing for the Examination
</strong></p>

				 <tr>
                <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">
                
                <p><strong>Kindly go through the Exam Rules & Regulations carefully before appearing for the Examination.
</strong></p>
               
                </td>
                </tr>

                
                <tr>
                <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">
                For detailed INSTRUCTIONS, RULES AND REGULATIONS of Remote Proctored Examinations click below link
                <a href="http://www.iibf.org.in/documents/pdf/Rules%20and%20regulation%20of%20RP%20exam_20200525.pdf" target="_blank">Click here</a>
                </td>
                </tr>
                
                <tr>
                <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">
                For Frequently Asked Questions (FAQs) click the below link 
                <a href="http://www.iibf.org.in/documents/pdf/20200713_FAQ%20-%20Remote%20Proctored%20Exam%20CSC%20final.pdf" target="_blank">Click here</a>
                </td>
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

  



  

</body>

</html>