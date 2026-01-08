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
                  <img src="<?php echo base_url();?>assets/images/logo.jpg" width="400" height="66" />
                  <sup style="position: relative;right: 3px;top: -30px;">®</sup>
                  <img src="<?php echo base_url()?>assets/images/90 Year of Service [IIBF]_Logo _Final_08062017.png" width="70" height="70"  style="margin-left: 100px; margin-right: -190px;"/>
                  </td>
                  
                     
                    
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td style="background-color:#1287c0; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:10px 0;">
                  Admit Letter for <?php echo $record->mode?> <?php echo " ". $exam_name;?> 
                  <?php
						echo "Examination";
				  ?>
                   - 
				  <?php 
						echo $examdate;
				  ?>
                  
                  <div align="right">
                   
                  	<a href="<?php echo base_url();?>admitcard/caiib218dashboard">Home</a> &nbsp; 
                   
                  	<a href="<?php echo base_url();?>admitcard/getadmitcardpdfjdres/<?php echo base64_encode($exam_code)?>" style="color:#F00">Save as pdf</a>&nbsp;
                    
                    
                  	<!--<a href="javascript:void(0);" onclick="javascript:printDiv();" style="color:#F00">Print</a>-->&nbsp;
                  	<a href="<?php echo base_url()?>admitcard/logout" style="color:#F00">Logout</a>
                    
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
                            <strong>Membership / Registration No. : <?php echo $record->mem_mem_no;?></strong>
                            </td>
                          </tr>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">
                            <?php echo $record->mam_nam_1;?>
                            </td>
                          </tr>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase;">
                            <?php 
								$userinfo = $this->master_model->getRecords('member_registration',array("regnumber"=>$mid ),'address1,address2,address3,address4,district,city,state,pincode');
								
							?>
                            <?php if($userinfo[0]['address1'] != ''){?>
                            	<?php echo $userinfo[0]['address1']."<br/> ";?>
                            <?php }?>
                            <?php if($userinfo[0]['address2'] != ''){?>
								<?php echo $userinfo[0]['address2']."<br/> ";?>
                            <?php }?>
                            <?php if($userinfo[0]['address3'] != '' && ($userinfo[0]['address3'] != $userinfo[0]['address2'])){?>
                            	<?php echo $userinfo[0]['address3']."<br/>";?>
                            <?php }?>
                            <?php if($userinfo[0]['address4'] != '' && ($userinfo[0]['address4'] != $userinfo[0]['address3'])){?>
                            	<?php echo str_replace(";","",$userinfo[0]['address4'])."<br/> ";?>
                            <?php }?>
                            <?php if($userinfo[0]['pincode'] != '' && strlen($userinfo[0]['pincode']) == 6){?>
                            	<?php echo "Pincode: ". str_replace(";","",$userinfo[0]['pincode'])."<br/>";?>
                            <?php }?>
                            <?php if($userinfo[0]['city'] != ''){ echo $userinfo[0]['city']; }?>
                            <?php if($userinfo[0]['state'] != ''){ echo $userinfo[0]['state']; }?>
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
                                <img src="<?php echo base_url();?><?php echo get_img_name($mid,'p');?>" width="100" height="125" />
                              </td>
                              </tr>
                              <tr>
                  <td height="5"></td>
                </tr>
                              <tr>
                              <td>
                                <img src="<?php echo base_url();?><?php echo get_img_name($mid,'s');?>" width="100" height="50" />
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
                      <th width="25%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Subject Name</th>
                      <th width="11%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Medium</th>
                      <th width="11%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Date</th>
                      <th width="11%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Time<sup>@</sup></th>
                      <th width="12%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
                      <th width="30%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;" >Exam Seat No.</th>
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
				  ?>
                  <tr style="background-color:#dcf1fc;">
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subject->subject_description;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $medium;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo date('d-M-Y',strtotime($subject->date)) ;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->time;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->venueid;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->seat_identification;?></td>
                    </tr>
                  <?php }?>  
                  </tbody>
                  </table>
                  </td>
                </tr>
                <tr>
                	<td style="text-align:right; line-height:24px; font-size:13px;">
                    <?php if($exam_code!=34 && $exam_code!=160 && $exam_code!=58 && $exam_code!=101){?>
                    @ Refer Reporting Time in the Important Instructions
                    <?php }?>
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                  <td>
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
                  <thead>
                    <tr style="background-color:#7fd1ea;">
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
                      <th style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Address</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach($records as $records){?>
                  <tr style="background-color:#dcf1fc;">
                  
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $records->venueid;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;">
					  	<?php echo $records->venueadd1;?>
						<?php echo $records->venueadd2;?>
						<?php 
							if($records->venueadd3 != $records->venueadd2){
								echo $records->venueadd3;
							}
						?>
                      	<?php 
							if($records->venueadd4 != $records->venueadd3){
								echo $records->venueadd4;
							}
						?>
						<?php
							if($records->venueadd5 != $records->venueadd4){ 
								echo $records->venueadd5;
							}
						?>
						<?php 
							if($records->venpin != 0){
								echo $records->venpin;
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
                
                <?php if($vcenter == 3){?>
                    <p><strong>in case of any queries regarding venue, please dial 022-62507716 (available on exam day and previous day only). for all other queries email to care@iibf.org.in
</strong></p>
                    <?php }?>
                    <?php if($vcenter == 1){?>
                    <p>
                    <strong>in case of any queries regarding venue, please dial 1800 419 2929 and press option 7.(available on exam day and previous day only). for all other queries email to care@iibf.org.in</strong>
                    </p>
                    <?php }?>
                

<p>Please note that you need to appear for examination at the above mentioned 
venue only and will not be permitted from any other venue</p>
<?php if($exam_code !=34 && $exam_code !=160 && $exam_code !=58 && $exam_code !=81){?>
<p>(Your Admit Letter consists of 2 pages which includes Important Instructions. Kindly go through the instructions carefully, print
both the pages and carry the same to the examination venue)</p>
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
                  <td style="text-align:left; padding:7px; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">Exam Password : <?php echo $record->pwd;?>
                  
                  </td>
                </tr>
                
                <tr>
				<td style="text-align:left; padding:7px;"><p>The cut-off date of guidelines / instructions issued by the regulator(s) and important developments in banking and finance up to 30th June, 2021 will only be considered for the purpose of inclusion in the question papers.</p></td>
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
  
  
<?php if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF')){?>

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
    <td >8.00</td>
    <td >10.45</td>
    <td >1.30</td>
  </tr>
  <tr>
    <td >Candidate Entry to computer Lab    </td>
    <td >8.00 to 8.15</td>
    <td >10.45 to 11.00</td>
    <td >1.30 to 1.45</td>
  </tr>
  <tr>
    <td >Gate Closing </td>
    <td >8.15</td>
    <td >11.00</td>
    <td >1.45</td>
  </tr>
  <tr>
    <td >Candidate Login start time for sample test</td>
    <td >8.20</td>
    <td >11.05</td>
    <td >1.50</td>
  </tr>
  <tr>
    <td >Exam Start Time </td>
    <td >8.30</td>
    <td >11.15</td>
    <td >2.00</td>
  </tr>
  <tr>
    <td >Exam Close Time</td>
    <td >10.30</td>
    <td >1.15</td>
    <td >4.00</td>
  </tr>
</table>


                    </td>
                </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Candidates are advised to report to the Examination Venue as per the timing mentioned above. <strong>No candidate/s will be permitted to enter the Examination Venue/hall after the gate closing time for any reason whatsoever. Institute has instructed the examination conducting authorities of all the venues to strictly follow the timelines (CANDIDATE PLEASE NOTE).</strong></td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>No candidate will be permitted to leave the hall in the first 60 minutes from the scheduled start time of the examination</strong> </td>
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
                <td style="font-size:13px;">Candidates are required to <strong> produce printed copy of admit letter </strong> along with Membership identity card or any other valid photo ID card in original (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>In the absence of printed copy of Admit Letter and Original Photo Identity Card, candidates will be denied permission to write Examination. </strong></td>
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
                <td style="font-size:13px;"><strong>After submitting of the question paper provisional score card/result will be displayed on the computer screen. This Score card will be sent to the Candidate's email ID as registered with the Institute.</strong></td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;">Candidate can provide alternate email ID if he wishes to receive the score card on different email ID</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">c.</td>
                <td style="font-size:13px;">For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institute's website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same.</td>
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
                <td style="font-size:13px;"><strong>PLEASE REFER INSTITUTE'S WEBSITE UNDER THE MENU "EXAM RELATED" FOR DETAILS OF DEBARMENT PERIOD FOR :</strong>
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


<?php if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72){?>

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
    <td >8.00</td>
    <td >10.45</td>
    <td >1.30</td>
  </tr>
  <tr>
    <td >Candidate Entry to computer Lab    </td>
    <td >8.00 to 8.15</td>
    <td >10.45 to 11.00</td>
    <td >1.30 to 1.45</td>
  </tr>
  <tr>
    <td >Gate Closing </td>
    <td >8.15</td>
    <td >11.00</td>
    <td >1.45</td>
  </tr>
  <tr>
    <td >Candidate Login start time for sample test</td>
    <td >8.20</td>
    <td >11.05</td>
    <td >1.50</td>
  </tr>
  <tr>
    <td >Exam Start Time </td>
    <td >8.30</td>
    <td >11.15</td>
    <td >2.00</td>
  </tr>
  <tr>
    <td >Exam Close Time</td>
    <td >10.30</td>
    <td >1.15</td>
    <td >4.00</td>
  </tr>
</table>


                    </td>
                </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Candidates are advised to report to the Examination Venue as per the timing mentioned above.<strong> No candidate/s will be permitted to enter the Examination Venue/hall after the gate closing time for any reason whatsoever. Institute has instructed the examination conducting authorities of all the venues to strictly follow the timelines (CANDIDATE PLEASE NOTE).</strong></td>
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
                <td style="font-size:13px;">Candidates are required to produce printed copy of admit letter along with Membership identity card or any other valid photo ID card (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue.</td>
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
                <td style="font-size:13px;">Candidates will be allowed to use battery operated portable calculator in the examination. The calculator can be of any type up to 6 functions, 12 digits.</td>
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
          <td><strong style="font-size:14px;">Rough Sheet :</strong></td>
        </tr><!--3.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">For any rough work Candidate can press 'Rough Sheet' button on the computer screen during the examination for doing any rough work. Blank paper/rough sheet will not be provided to the candidate for rough work during the examination.</td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:3-->
        
        
        <tr>
          <td width="5" style="padding-left:15px;font-size:14px;">7.</td>
          <td><strong style="font-size:14px;">Provisional Score Card/Result :</strong></td>
        </tr><!--3.-->
        <tr>
          <td colspan="2" style="padding-left:30px;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">After submitting of the question paper provisional score card/result will be displayed on the computer screen. This Score card will be sent to the Candidate's email ID as registered with the Institute.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;">Candidate can provide alternate email ID if he wishes to receive the score card on different email ID.
                </td>
              </tr>
            </table>
          </td>
        </tr><!--Content of No.:3-->
        
        
        <tr>
          <td width="10" style="padding-left:15px;font-size:14px;">8.</td>
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
                <td style="font-size:13px;">This examination is confidential. It is made available to the candidates solely for the purpose of assessing qualifications in the discipline referenced in the title of this examination. Candidates are expressly prohibited from disclosing, publishing, reproducing, or transmitting the questions/options of the examinations, in whole or in part, in any form or by any means, verbal or written, electronic or mechanical, for any purpose, without the prior express written permission from IIBF. Candidates found doing so, shall be considered as unlawful act and attract the rules relating to unfair practices.
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
                <td style="font-size:13px;"><strong>PLEASE REFER INSTITUTE'S WEBSITE UNDER THE MENU "EXAM RELATED" FOR DETAILS OF DEBARMENT PERIOD FOR :</strong>
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

<?php
	 $ex_arr = array(20,34,58,74,78,79,135,148,149,153,158,160,161,162,163,164,166,175,177,8,11,18,19,24,25,26,59,81,151,156,165,200,590,5800,3400,177,1600,810);
	if(in_array($exam_code,$ex_arr)&& $idate !='10-Mar-19'){
?>

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
                <td style="font-size:13px;">Candidates are advised to report to the Examination Venue as per the timing mentioned above. <strong> No candidate/s will be permitted to enter the Examination Venue/hall after the gate closing time for any reason whatsoever. Institute has instructed the examination conducting authorities of all the venues to strictly follow the timelines (CANDIDATE PLEASE NOTE).</strong></td>
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
                <td style="font-size:13px;">Candidates are required to produce <strong>printed copy of admit letter </strong> along with Membership identity card or any other valid photo ID card <strong> in original </strong> (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>In the absence of printed copy of Admit Letter and Original Photo Identity Card, candidates will be denied permission to write Examination</strong></td>
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
                <td style="font-size:13px;"><strong>Institute will not make any arrangement for safe keep of Mobile Phones, electronic/smart gadgets, bags or any other item pertaining to the candidates..</strong></td>
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
                <td style="font-size:13px;"><strong>After submitting of the question paper provisional score card/result will be displayed on the computer screen. This Score card will be sent to the Candidate's email ID as registered with the Institute.</strong></td>
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
                <td style="font-size:13px;">No candidate shall impersonate others or allow others to impersonate himself/herself at the examination
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">i.</td>
                <td style="font-size:13px;">No candidate shall misbehave/argue with the Examination Conducting Authorities at the centre
                </td>
              </tr>
              
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">j.</td>
                <td style="font-size:13px;">Candidates have to compulsory return any papers given including that given for rough work to invigilator
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">k.</td>
                <td style="font-size:13px;">If the examination could not commence on scheduled time or there is delay due to Failure of power, Technical snag of whatsoever nature or for any such reason having bearing upon the conduct of examination; candidates have to   :-
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
                <td style="font-size:13px;"><strong>PLEASE REFER INSTITUTE'S WEBSITE UNDER THE MENU "EXAM RELATED" FOR DETAILS OF DEBARMENT PERIOD FOR :</strong>
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

<?php if($exam_code == 74 && $idate =='10-Mar-19' ){?>

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
    <td >8:30</td>
    <td >11:00</td>
    <td >1:30</td>
  </tr>
  <tr>
    <td >Candidate Entry to computer Lab    </td>
    <td >8:30 to 8:45</td>
    <td >11:00 to 11:15</td>
    <td >1:30 to 1:45</td>
  </tr>
  <tr>
    <td >Gate Closing </td>
    <td >8:45</td>
    <td >11:15</td>
    <td >1:45</td>
  </tr>
  <tr>
    <td >Candidate Login start time for sample test</td>
    <td >8:50</td>
    <td >11:20</td>
    <td >1:50</td>
  </tr>
  <tr>
    <td >Exam Start Time </td>
    <td >9:00</td>
    <td >11:30</td>
    <td >2:00</td>
  </tr>
  <tr>
    <td >Exam Close Time</td>
    <td >11:00</td>
    <td >1:30</td>
    <td >4:00</td>
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
                <td style="font-size:13px;">Candidates are required to <strong> produce printed copy of admit letter </strong> along with Membership identity card or any other valid photo ID card in original (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>In the absence of printed copy of Admit Letter and Original Photo Identity Card, candidates will be denied permission to write Examination</strong></td>
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
                <td style="font-size:13px;"><strong>After submitting of the question paper provisional score card/result will be displayed on the computer screen. This Score card will be sent to the Candidate's email ID as registered with the Institute.</strong></td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;">Candidate can provide alternate email ID if he wishes to receive the score card on different email ID.
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">c.</td>
                <td style="font-size:13px;">For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institute's website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same.
                </td>
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
                <td style="font-size:13px;">Candidates should not write Questions/Options etc. on the Admit Letter or use it like rough sheet. If Candidate is found doing so, he/she shall be deemed to be resorting to adoption of unfair means in the examination.
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

<?php }?>

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
                  <td align="center">
                  <img src="<?php echo base_url();?>assets/images/logo.jpg" width="400" height="66" />
                  <sup style="position: relative;right: 3px;top: -30px;">®</sup>
                  <img src="<?php echo base_url()?>assets/images/90 Year of Service [IIBF]_Logo _Final_08062017.png" width="70" height="70"  style="margin-left: 100px; margin-right: -190px;"/>
                  </td>
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td style="background-color:#1287c0; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:10px 0;">
                  
                  Admit Letter for <?php echo $record->mode?> <?php echo " ". $exam_name;?> 
                  <?php  echo "Examination"; ?>
                   - 
				  <?php echo $examdate;?>
                  
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
                           <strong> Membership / Registration No. : <?php echo $record->mem_mem_no;?></strong>
                            </td>
                          </tr>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">
                            <?php echo $record->mam_nam_1;?>
                            </td>
                          </tr>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase;">
                            <?php 
								$userinfo = $this->master_model->getRecords('member_registration',array("regnumber"=>$mid ),'address1,address2,address3,address4,district,city,state,pincode');
								
							?>
                            <?php if($userinfo[0]['address1'] != ''){?>
                            	<?php echo $userinfo[0]['address1']."<br/> ";?>
                            <?php }?>
                            <?php if($userinfo[0]['address2'] != ''){?>
								<?php echo $userinfo[0]['address2']."<br/> ";?>
                            <?php }?>
                            <?php if($userinfo[0]['address3'] != '' && ($userinfo[0]['address3'] != $userinfo[0]['address2'])){?>
                            	<?php echo $userinfo[0]['address3']."<br/>";?>
                            <?php }?>
                            <?php if($userinfo[0]['address4'] != '' && ($userinfo[0]['address4'] != $userinfo[0]['address3'])){?>
                            	<?php echo str_replace(";","",$userinfo[0]['address4'])."<br/> ";?>
                            <?php }?>
                            <?php if($userinfo[0]['pincode'] != '' && strlen($userinfo[0]['pincode']) == 6){?>
                            	<?php echo "Pincode: ". str_replace(";","",$userinfo[0]['pincode'])."<br/>";?>
                            <?php }?>
                            <?php if($userinfo[0]['city'] != ''){ echo $userinfo[0]['city']; }?>
                            <?php if($userinfo[0]['state'] != ''){ echo $userinfo[0]['state']; }?>
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
                                <img src="<?php echo base_url();?><?php echo get_img_name($mid,'p');?>" width="100" height="125" />
                              </td>
                              </tr>
                              <tr>
                  <td height="5"></td>
                </tr>
                              <tr>
                              <td>
                                <img src="<?php echo base_url();?><?php echo get_img_name($mid,'s');?>" width="100" height="50" />
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
                      <th width="25%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Subject Name</th>
                      <th width="11%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Medium</th>
                      <th width="11%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Date</th>
                      <th width="11%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Time<sup>@</sup></th>
                      <th width="12%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">Venue Code</th>
                      <th width="30%" style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;" >Exam Seat No.</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
				  	foreach($subjectprint as $subjectprint){
						
						/*$this->db->select('subject_description');
						$this->db->from('subject_master');
						$this->db->where(array('subject_code'=>$subjectprint->sub_cd));
						$subject_namep = $this->db->get();
						$subject_name_resp=$subject_namep->result() ;*/
						
						
				  ?>
                  <tr style="background-color:#dcf1fc;">
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;"><?php echo $subjectprint->subject_description;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $medium;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subjectprint->date;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subjectprint->time;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subjectprint->venueid;?></td>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subjectprint->seat_identification;?></td>
                    </tr>
                  <?php }?>  
                  </tbody>
                  </table>
                  </td>
                </tr>
                <tr>
                	<td style="text-align:right; line-height:24px; font-size:13px;">
                    
                     <?php if($exam_code!=34 && $exam_code!=160 && $exam_code!=58 && $exam_code!=101){?>
                    @ Refer Reporting Time in the Important Instructions
                    <?php }?>
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                  <td>
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
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
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; text-transform:uppercase;">
					  	<?php echo $recordsp->venueadd1;?>
						<?php echo $recordsp->venueadd2;?>
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
                
                <?php
				 $chkcenter = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
				 if(in_array($exam_code,$chkcenter)){ ?>
					<?php if($vcenter == 'NSEIT'){?>
                    <p><strong>in case of any queries regarding venue, please dial 022-42547558 (available on exam day and previous day only). for all other queries email to care@iibf.org.in
</strong></p>
                    <?php }?>
                    <?php if($vcenter == 'SIFY'){?>
                    <p>
                    <strong>in case of any queries regarding venue, please dial 1800 419 2929 and press option 7.(available on exam day and previous day only). for all other queries email to care@iibf.org.in</strong>
                    </p>
                    <?php }?>
                <?php }else{?>
                	<p>
                    <strong>in case of any queries regarding venue, please dial 1800 419 2929 and press option 7.(available on exam day and previous day only). for all other queries email to care@iibf.org.in</strong>
                    </p>
                <?php }?>

<p>Please note that you need to appear for examination at the above mentioned 
venue only and will not be permitted from any other venue</p>
<?php if($exam_code !=34 && $exam_code !=160 && $exam_code !=58 && $exam_code !=81){?>
<p>(Your Admit Letter consists of 2 pages which includes Important Instructions. Kindly go through the instructions carefully, print
both the pages and carry the same to the examination venue)</p>
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
  
  
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
   <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
     <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
      
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
  </tr>
  <tr>
    <td >Candidate reporting at the venue of examination </td>
    <td >8.00</td>
    <td >10.45</td>
    <td >1.30</td>
  </tr>
  <tr>
    <td >Candidate Entry to computer Lab  </td>
    <td >8.00 to 8.15</td>
    <td >10.45 to 11.00</td>
    <td >1.30 to 1.45</td>
  </tr>
  <tr>
    <td >Gate Closing </td>
    <td >8.15</td>
    <td >11.00</td>
    <td >1.45</td>
  </tr>
  <tr>
    <td >Candidate Login start time for sample    test</td>
    <td >8.20</td>
    <td >11.05</td>
    <td >1.50</td>
  </tr>
  <tr>
    <td >Exam Start Time </td>
    <td >8.30</td>
    <td >11.15</td>
    <td >2.00</td>
  </tr>
  <tr>
    <td >Exam Close Time</td>
    <td >10.30</td>
    <td >1.15</td>
    <td >4.00</td>
  </tr>
</table>


                    </td>
                </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">a.</td>
                <td style="font-size:13px;">Candidates are advised to report to the Examination Venue as per the timing mentioned above.<strong> No candidate/s will be permitted to enter the Examination Venue/hall after the gate closing time. </strong></td>
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
                <td style="font-size:13px;">Candidates are required to <strong> produce printed copy of admit letter </strong> along with Membership identity card or any other valid photo ID card (Aadhaar card/ Employer's card/PAN Card/Driving License/Election voter's card/Passport etc.) at the examination venue.</td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">b.</td>
                <td style="font-size:13px;"><strong>In the absence of printed copy of Admit Letter and Photo Identity Card, candidates will be denied permission to write Examination.</strong></td>
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
                <td style="font-size:13px;">Candidates will be allowed to use battery operated portable calculator in the examination. The calculator can be of any type up to 6 functions, 12 digits.</td>
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
          <td width="10" style="padding-left:15px;font-size:14px;">6.</td>
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
                    </table>
                </td>
              </tr>
              <tr>
                <td width="2%" valign="top" style="font-size:13px;">l.</td>
                <td style="font-size:13px;">l.	For all Examinations printed result advices (original as well as duplicate) will not be issued but the same will be available on the Institute's website www.iibf.org.in in printable form once the results are declared. Candidates are requested to download the same.
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
                <td style="font-size:13px;"><strong>PLEASE REFER INSTITUTE'S WEBSITE UNDER THE MENU "EXAM RELATED" FOR DETAILS OF DEBARMENT PERIOD FOR UNFAIR PRACTICES ADOPTED BY CANDIDATES DURING CONDUCT OF INSTITUTE'S EXAMINATIONS.</strong>
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