<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('google_analytics_script_common'); ?>
	<meta charset="utf-8">
	<title>Welcome to IIBF</title>
  <?php $bulk_array = array(1051, 1005, 1006, 1007, 1008, 1059, 1034, 1048, 1049, 1050, 1061); ?>
  <style media="print">
    @media print
    {  
        /*body {
          padding-top: 72px;
          padding-bottom: 72px ;
        }*/ 
        a[href]:after {
          content: none !important;
        }
        .remove_in_print {
          display: none !important;
        }
        @page {
          margin-top: 10px !important;
          margin-bottom: 0;
        }
    } 

    /*@media print {
      a[href]:after {
          content: none !important;
      }
    } 
    @page {
        size: auto;   // auto is the initial value
        margin: 0;  // this affects the margin in the printer settings 
    }*/
  </style>  
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
                  
                  <?php
                  	$caiib = array($this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
					if(!in_array($exam_code,$caiib)){
				  ?>
                  
                  <?php
                  		if($exam_code == 101){
							echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS - Nov 2017";
						}elseif($exam_code == 991){
							echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENT";
						}else{
				  ?>
                  
                  Admit Letter for <?php echo "Online"; //echo $record->mode; ?> <?php //echo $exam_name;?> <?php if($exam_code == '20' )
						{ 
						echo preg_replace("/\([^)]+\)/","",$exam_name); 
						}
						else
						{
							//echo preg_replace("/\([^)]+\)/","",$exam_name);
							echo $exam_name;
						}
						?> 
                  <?php if($exam_code != 101 && $exam_code != 991){ echo "Examination"; }?>
                   - 
				  <?php echo $examdate;?>
                  
                  
                  <?php } }?>
                  <?php
						$elective = array("62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
						if($exam_code == $this->config->item('examCodeCaiib')){
							echo "Admit letter for Online CAIIB Examination – Jun 2021";
						}
						if(in_array($exam_code,$elective)){
							echo "Admit letter for Online CAIIB Electives – Jun 2021";
						}

            //print_r($record);
            //echo '---------------';
					?>
                  
                  
                  <div align="right">
                   
                  	<!-- <a href="javascript:void(0);" onclick="window.print();" style="color:#F00">Print</a>&nbsp; -->
                   
                  	<a class="remove_in_print" href="<?php echo base_url();?>admitcard/getadmitcardpdfsp_new/<?php echo base64_encode($exam_code)?>/<?php echo $mem_exam_id;?>" style="color:#F00">Save as pdf</a>&nbsp;
                    
                    
                  	&nbsp;
                  	<a class="remove_in_print" href="<?php echo base_url()?>admitcard/logout" style="color:#F00">Logout</a>
                    
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
                          <?php if($exam_code == 1009 || $exam_code == 1027) { ?>
                            <tr>
                              <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                <strong>Institute : <?php echo $record->insname; ?></strong>
                              </td>
                            </tr>
                          <?php } elseif (in_array($exam_code, $bulk_array)) { //elseif added by pooja mane 2024-10 
                            ?>
                              <tr>
                                <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                  <strong>Institute : <?php
                                                      if (empty($instituteDetails[0]['associatedinstitute'])) {
                                                        echo $instituteDetails1[0]['associatedinstitute'];
                                                      } else {
                                                        echo $instituteDetails[0]['associatedinstitute'];
                                                      }
                                                      ?>
                                  </strong>
                                </td>
                              </tr>
                            <?php } ?>
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">
                            <?php echo $record->mam_nam_1;?>
                            </td>
                          </tr>
                          <?php if($exam_code == 1009 || $exam_code == 1027) { ?>  
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase; font-weight:bold;">
                                <strong>DOB : <?php echo date('d-m-Y',strtotime($memberDetails[0]['dateofbirth'])); ?></strong>
                            </td>
                          </tr>
                          <?php } ?>  
                          <tr>
                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px; text-transform:uppercase;">
                            <?php if($record->mem_adr_1 != ''){?>
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
                                <?php $this->master_model->resize_admitcard_images(get_img_name($member_id,'p')); ?>
                                <img src="<?php echo base_url();?><?php echo get_img_name($member_id,'p');?>" width="100" height="125" />
                              </td>
                              </tr>
                              <tr>
                  <td height="5"></td>
                </tr>
                              <tr>
                              <td>
                                <?php $this->master_model->resize_admitcard_images(get_img_name($mid,'s')); ?>
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
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo date('d-M-Y',strtotime($subject->exam_date)) ;?></td>
                      <?php
                      	$ap = '';
					   		//echo $subject->exam_date;
							if($subject->time == '08:.00:00'){
								$ap = 'AM';
							}elseif($subject->time == '10:.45:00'){
								$ap = 'AM';
							}elseif($subject->time == '01:.30:00'){
								$ap = 'PM';
							}elseif($subject->time == '04:.15:00'){
								$ap = 'PM';
							}
					  ?>
                      <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;"><?php echo $subject->time." ".$ap;?></td>
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
                    <td height="10"><strong>Candidate are advised to check Institute's Website, a day before the Examination Date for any important updates/information's regarding the Examination.</strong></td>
                </tr>
                
                <tr>
                <td>
                
              
					<?php if($vcenter == 3){?>
                   
                    <?php }?>
                    <?php if($vcenter == 1){?>
                    
                    <?php }?>
                
                

<p>Please note that you need to appear for examination at the above mentioned Date/Time only.</p>
<?php if($exam_code !=34 && $exam_code !=160 && $exam_code !=58 && $exam_code !=81){?>
<p></p>
<?php }?>
                </td>
                </tr>

                <?php 
            if($exam_code == 1001 || $exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027 || $exam_code == 1019 || $exam_code == 1020 || $exam_code == 1031 || $exam_code == 1032 || $exam_code == 1058 || $exam_code == 1059 || $exam_code == 1051 || $exam_code == 1034 || $exam_code == 1048 || $exam_code == 1049 || $exam_code == 1050 || $exam_code == 1061 || $exam_code == 1062 || $exam_code == 1063 || $exam_code == 1064 || $exam_code == 1065 || $exam_code == 1066 || $exam_code == 1067 || $exam_code == 1068 || $exam_code == 1069){
          ?>
                <tr><td><strong>Kind attention candidates would be continuously monitored during Remote Protected Exam and the entire session will be recorded using webcam and mic. In case of any suspicious activity, test will be paused/cancelled as per exam rules. In case candidates are found resorting to unfair means they will be debarred as per the rules of institutes examination.</strong></td></tr>

              <?php } ?> 

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
                 <tr>
				<td style="text-align:left; padding:7px; border-right:1px solid #198cc3;"><p>The cut-off date of guidelines / instructions issued by the regulator(s) and important developments in banking and finance up to <?php echo '30th June, 2025'; ?> will only be considered for the purpose of inclusion in the question papers.</p></td>
				</tr>
                
                <tr>
                        <td style="border-right:1px solid #198cc3;">
                          <?php 
						if($exam_code == 1001 || $exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008  || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 ||  $exam_code == 2027 || $exam_code == 1017 || $exam_code == 1019 || $exam_code == 1020 || $exam_code == 1031 || $exam_code == 1032 || $exam_code == 1058 || $exam_code == 1027  || $exam_code == 1059 || $exam_code == 1051 || $exam_code == 1034 || $exam_code == 1048 || $exam_code == 1049 || $exam_code == 1050 || $exam_code == 1061 || $exam_code == 1062 || $exam_code == 1063 || $exam_code == 1064 || $exam_code == 1065 || $exam_code == 1066 || $exam_code == 1067 || $exam_code == 1068 || $exam_code == 1069){

					?>
                    <p><strong>Kindly go through the below documents before appearing for examination</strong></p>
                    <ul>
                    
                    <!-- <li><a href="http://www.iibf.org.in/documents/pdf/20210301_Rules%20and%20regulation%20of%20RP%20exam%2013-jul-20.pdf" target="_blank">Click here for Rules and Regulations for Remote Proctored Examinations.</a></li> -->
                    <li><a href="https://iibf.org.in/documents/pdf/20210301_Rules%20and%20regulation%20of%20RP%20exam%2013-jul-20%20(1).pdf?<?php echo time(); ?>" target="_blank">Click here for Rules and Regulations for Remote Proctored Examinations.</a></li>
 
                    <!-- <li><a href="http://www.iibf.org.in/documents/210531RPE_NSEIT_Important_Instructions7-14.pdf" target="_blank">Click here for Important Instructions/ Examination Links for Remote Proctored Examinations.</a></li> -->
                    <?php if($exam_code == 1001 || $exam_code == 1002 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027 || $exam_code == 1019 || $exam_code == 1020 || $exam_code == 1058 || $exam_code == 1059 || $exam_code == 1051 || $exam_code == 1034 || $exam_code == 1048 || $exam_code == 1049 || $exam_code == 1050 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1009 || $exam_code == 1061 || $exam_code == 1062 || $exam_code == 1063 || $exam_code == 1064 || $exam_code == 1065 || $exam_code == 1066 || $exam_code == 1067 || $exam_code == 1068 || $exam_code == 1069 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1031 || $exam_code == 1032 || $exam_code == 1027 ) { ?> 
                      <li><a href="https://www.iibf.org.in/documents/pdf/Important_Instructions_TL_30052025.pdf" target="_blank">Click here for Important Instructions/ Examination Links for Remote Proctored Examinations.</a></li>
                    <?php }else if($exam_code == 1027 ){ /*$exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1009 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1031 || $exam_code == 1032 || $exam_code == 1027  */ ?>
                      <!-- <li><a href="https://www.iibf.org.in/documents/pdf/RPE_CSC_Important_Instructions_30052025.pdf" target="_blank">Click here for Important Instructions/ Examination Links for Remote Proctored Examinations.</a></li> -->
                    <?php } else { ?>
                    <li><a href="https://www.iibf.org.in/documents/pdf/210531RPE_NSEIT_Important_Instructions7-14(09062023).pdf" target="_blank">Click here for Important Instructions/ Examination Links for Remote Proctored Examinations.</a></li>
                    <?php }?>
                  

                    <!-- <li><a href="http://www.iibf.org.in/documents/210531RPE_FAQ_(New)_July%202021.pdf">Click here for FAQs for Remote Proctored Examinations. </a></li> -->
                    <?php /*if($exam_code == 1020) {?>
                    <?php }
                    else{*/ ?>
                    <!-- <li><a href="https://iibf.org.in/documents/pdf/210531RPE_FAQ_New_July%202021_161023.pdf">Click here for FAQs for Remote Proctored Examinations. </a></li> -->
                    <!-- <li><a href="https://iibf.org.in/documents/pdf/210531RPE_FAQ_New_July_2021_09062023.pdf">Click here for FAQs for Remote Proctored Examinations. </a></li> -->
                    <li><a href="https://iibf.org.in/documents/pdf/070324RPE_FAQ_New_July_2021.pdf">Click here for FAQs for Remote Proctored Examinations. </a></li>
                    <?php //}?>
                    </ul>
                    

                          
                          <?php }?> 
                          <?php 
					if($exam_code == 1029 || $exam_code == 1028 || $exam_code == 1030){
				?>
                
                	<p><strong>Kindly go through the below documents before appearing for examination</strong></p>
                    <ul>
                    
                   <!--  <li><a href="http://www.iibf.org.in/documents/pdf/20210301_Rules%20and%20regulation%20of%20RP%20exam%2013-jul-20.pdf" target="_blank">Click here for Rules and Regulations for Remote Proctored Examinations.</a></li> -->

                   <li><a href="https://www.iibf.org.in/documents/pdf/20210301_Rules%20and%20regulation%20of%20RP%20exam%2013-jul-20(09062023).pdf" target="_blank">Click here for Rules and Regulations for Remote Proctored Examinations.</a></li>
                    
                    
                    <!-- <li><a href="http://www.iibf.org.in/documents/210531RPE_CSC_Important_Instructions1-7.pdf" target="_blank">Click here for Important Instructions/ Examination Links for Remote Proctored Examinations.</a></li> -->
                    <li><a href="https://www.iibf.org.in/documents/pdf/210531RPE_CSC_Important_Instructions1-7(09062023).pdf" target="_blank">Click here for Important Instructions/ Examination Links for Remote Proctored Examinations.</a></li>
                     
                    <!-- <li><a href="http://www.iibf.org.in/documents/210531RPE_FAQ_(New)_July%202021.pdf">Click here for FAQs for Remote Proctored Examinations. </a></li> -->
                    <li><a href="https://www.iibf.org.in/documents/pdf/210531RPE_FAQ_(New)_July%202021(09062023).pdf">Click here for FAQs for Remote Proctored Examinations. </a></li>
                   
                    </ul>
                          
                          <?php }?></td>
                      </tr>
                
                <tr>
                <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3;">
                
                
                </td>
                </tr>

                
                <tr>
                <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">
                <?php 
						if($exam_code == 1010){ //$exam_code == 1002 || $exam_code == 1014 || $exam_code == 1013 || $exam_code == 1017 || $exam_code == 1019 ||  $exam_code == 2027 || $exam_code == 1011 || $exam_code == 1012
					?>
                   For technical support write an email to iibftechsupport@nseitonline.com or call on(022)62507760
(between 07:30am - 05:30pm)
               
<?php }?>

<?php 
if($exam_code == 1001 || $exam_code == 1002 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027 || $exam_code == 1019 || $exam_code == 1020 || $exam_code == 1058 || $exam_code == 1059 || $exam_code == 1051 || $exam_code == 1034 || $exam_code == 1048 || $exam_code == 1049 || $exam_code == 1050 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1009 || $exam_code == 1061 || $exam_code == 1062 || $exam_code == 1063 || $exam_code == 1064 || $exam_code == 1065 || $exam_code == 1066 || $exam_code == 1067 || $exam_code == 1068 || $exam_code == 1069 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1031 || $exam_code == 1032){
  ?>
  For technical support write an email to iibfexamsupport@digivarsity.com or call on(020)47170126
(between 08:00am - 06:30pm on the exam day and 09:30am - 06:30pm for the rest of the working days.)
  <?php
}

//if($exam_code == 1020) { ?>
  <!-- For technical support write an email to iibftechsupport@nseitonline.com or call on(022)62507760 (between 09:30am - 05:00pm) -->
        <?php //} ?>

        <?php // if($exam_code == 1014) { ?>
      <!-- For technical support write an email to iibfexamsupport@digivarsity.com or call on 020-47170126 (between 08:00am - 06:30pm on the exam day and 09:30am - 06:30pm for the rest of the working days.) -->
        <?php // } ?>

<?php 
					if($exam_code == 103200){  /*$exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1009 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1031 || $exam_code == 1032 */
				?>
                 <!-- For technical support you may please write an email to iibfexam@cscacademy.org or call on 08956684119 / +918065241899. From 2 days prior to exam till the exam day, candidates may please contact between 09:00 AM to 10:00 PM. For rest of the working days, you may please contact between 10:00 AM to 06:00 PM. -->
                <?php } ?>

                
                </td>

                <?php if($exam_code == 1001 || $exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027 || $exam_code == 1019 || $exam_code == 1020 || $exam_code == 1031 || $exam_code == 1032 || $exam_code == 1058 || $exam_code == 1059 || $exam_code == 1051 || $exam_code == 1034 || $exam_code == 1048 || $exam_code == 1049 || $exam_code == 1050 || $exam_code == 1061 || $exam_code == 1062 || $exam_code == 1063 || $exam_code == 1064 || $exam_code == 1065 || $exam_code == 1066 || $exam_code == 1067 || $exam_code == 1068 || $exam_code == 1069){  ?>
                  <table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-top:1px solid #198cc3; border-left:1px solid #198cc3;">
                    <tbody>
                      <tr style="background-color:#dcf1fc;">
                        <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;"><?php echo $record->created_on.' '.$transaction_no; ?> </td>
                      </tr>
                    
                      <!-- <tr style="background-color:#dcf1fc;">
                        <td style="text-align:left; padding:7px; border-bottom:1px solid #198cc3; border-right:1px solid #198cc3; font-weight:bold; color:#0000ff;">The payment reference number : <?php// echo $transaction_no; ?></td>
                      </tr> -->
                    </tbody>
                  </table>
                <?php }?>

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
  


<!--Printable DIV-->
  

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
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script> 
        $(document).keydown(function(event) {
          //alert(event.which);
            // Check if Ctrl (event.ctrlKey) and P (event.which === 80) are pressed
            if (event.ctrlKey && event.which === 80) {
 
              var last_pos = '';
              var page_nm = '';
              var final_url = '';
              var url = window.location.href;
              var urlArray = url.split("/");
              
              if(urlArray){
                var last_pos = (urlArray.length - 3);
                if(last_pos >= 0){
                  var page_nm = urlArray[last_pos];
                  page_nm = page_nm.trim();
                  //alert(myArray[last_pos]);
                  if(page_nm && page_nm == "getadmitcardsp_new"){
                      //alert(final_url);
                      final_url = url.replace("getadmitcardsp_new", "getadmitcardpdfsp_new");
                      
                      event.preventDefault() // disabled ctrl + p event

                      window.location = final_url;
                      setTimeout(function() { 
                          //window.print();
                          //self.close();
                        //window.location.reload();
                     }, 1000);
                  }
                } 
              }
              
                
            }
        });
    </script>
</body>
</html>