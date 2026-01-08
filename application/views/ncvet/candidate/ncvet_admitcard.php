<?php

defined('BASEPATH') or exit('No direct script access allowed');

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

      <td style="background-color:#fff;">

        <!--table-1-->

        <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">

          <tr>

            <td style="border:1px solid #1287c0; padding:5px;">

              <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">

                <tr>

                  <td align="center">

                    <img src="<?php echo base_url(); ?>assets/images/admit_logo.jpg" width="400" height="66" />

                    <img src="<?php echo base_url() ?>assets/images/ninty_year_new.png" width="70" height="70" style="margin-left: 100px; margin-right: -190px;" />

                  </td>

                </tr>

                <tr>

                  <td height="5"></td>

                </tr>

                <tr>

                  <td style="background-color:#1287c0; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:10px 0;">


                        Admit Letter for <?php echo $member_result->mode ?> <?php echo $exam_name; ?> Examination


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

                                <strong>Membership / Registration No. : <?php echo $member_result->mem_mem_no; ?></strong>
                                
                              </td>

                            </tr>
                            
                            <tr>

                              <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">

                                <?php echo $member_result->mam_nam_1; ?>
                                &nbsp;&nbsp; <b>DOB:</b>&nbsp; <?php echo date('d-m-Y', strtotime($memberDetails[0]['dob'])); ?> 
                              </td>

                            </tr>
                            <tr>

                              <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase;">

                                <?php if ($member_result->mem_adr_1 != '') { ?>

                                  <?php echo $member_result->mem_adr_1 . "<br/> "; ?>

                                <?php } ?>

                                <?php if ($member_result->mem_adr_2 != '') { ?>

                                  <?php echo $member_result->mem_adr_2 . "<br/> "; ?>

                                <?php } ?>

                                <?php if ($member_result->mem_adr_3 != '' && ($member_result->mem_adr_3 != $member_result->mem_adr_2)) { ?>

                                  <?php echo $member_result->mem_adr_3 . "<br/>"; ?>

                                <?php } ?>

                                <?php if ($member_result->mem_adr_4 != '' && ($member_result->mem_adr_4 != $member_result->mem_adr_3)) { ?>

                                  <?php echo str_replace(";", "", $member_result->mem_adr_4) . "<br/> "; ?>

                                <?php } ?>

                                <?php if ($member_result->mem_adr_5 != '' && ($member_result->mem_adr_5 != $member_result->mem_adr_4)) { ?>

                                  <?php echo str_replace(";", "", $member_result->mem_adr_5) . "<br/> "; ?>

                                <?php } ?>

                                <?php if ($member_result->mem_pin_cd != '' && strlen($member_result->mem_pin_cd) == 6) { ?>

                                  <?php echo "Pincode: " . str_replace(";", "", $member_result->mem_pin_cd) . "<br/>"; ?>

                                <?php } ?>

                              </td>

                            </tr>

                            <tr>

                              <td>

                                <table>

                                  <tr>

                                    <td style="border:0px solid #333; padding:7px; text-transform:uppercase;"><img src="<?php echo base_url(); ?>assets/images/phone-icon.png" width="220" alt="Phone" style="float:left; margin-top:8px; margin-right:15px;" /></td>

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
                                
                                <img src="<?php echo ncvet_img_p($member_id, 'photo'); ?>" width="100" height="125" />

                              </td>

                            </tr>

                            <tr>

                              <td height="5"></td>

                            </tr>

                            <tr>

                              <td>
                              
                                
                                <img src="<?php echo ncvet_img_p($member_id, 'sign'); ?>" width="100" height="50" />

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

                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">

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

                        foreach ($subject as $subject) {

                        ?>

                          <tr style="background-color:#dcf1fc;">

                            <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subject['subject_description']; ?></td>

                            <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $medium; ?></td>

                            <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">

                              <?php

                              $exam_print_date = explode("-", $subject['exam_date']);

                              $edate = $exam_print_date[0] . "-" . $exam_print_date[1] . "-" . $exam_print_date[2];

                              echo date('d-M-Y', strtotime($edate));
                              $ap = '';
                              //echo $subject->exam_date;
                              if ($subject['time'] == '08:.00:00') {
                                $ap = 'AM';
                              } elseif ($subject['time'] == '10:.45:00') {
                                $ap = 'AM';
                              } elseif ($subject['time'] == '01:.30:00') {
                                $ap = 'PM';
                              } elseif ($subject['time'] == '04:.15:00') {
                                $ap = 'PM';
                              }

                              ?>

                            </td>

                            <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject['time'] . " " . $ap; ?></td>



                          </tr>

                        <?php } ?>

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

                    <p>Please note that you need to appear for examination at the above mentioned Date/Time only.</p>


                  </td>

                </tr>
               
                  <tr>
                    <td><br>
                      <b>Kind attention candidates would be continuously monitored during Remote Protected Exam and the entire session will be recorded using webcam and mic. In case of any suspicious activity, test will be paused/cancelled as per exam rules. In case candidates are found resorting to unfair means they will be debarred as per the rules of institutes examination.</b>
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

                          <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">Exam Password : <?php echo $member_result->pwd; ?></td>

                        </tr>
                        
                        <tr>
                          <td style="border-right:1px solid #198cc3;">
                            
                          </td>
                        </tr>

                        <tr>
                          <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">


                          </td>
                        </tr>


                        <tr>
                          <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">
                            

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

</body>

</html>