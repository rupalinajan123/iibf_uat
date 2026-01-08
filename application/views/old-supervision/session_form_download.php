<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>

<html lang="en">

<head>



  <meta charset="utf-8">

  <title>Welcome to IIBF</title>

    

</head>

<body style="background-color:#fff; margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:12px;">

<div cellpadding="0" cellspacing="0" width="100%" border="0" align="center" >

    <div>

        <div style="background-color:#fff;">

            <!--table-1-->

            <div cellpadding="0" cellspacing="0" width="100%" border="0" align="center">

                <div>

                    <div style="border:1px solid #1287c0; padding:5px;">

                        <div cellpadding="0" cellspacing="0" width="100%" border="0" align="center">

                            <div>

                            <div align="center" style="text-align: center;">
                            <div>
                            <table align="center">
                                <tr><td>
                                <span style=" color:#00bdd5; font-weight:bold; font-size: 20px;text-align:center;">Indian Institute of Banking and Finance</span>
                                </td>  <td>
                                <img src="<?php echo base_url()?>assets/images/ninty_year_new.png" width="70" height="70" style="margin-left: 100px; " />
                                </td></tr></table></div>
                            </div>

                            </div>

                            <div>

                            <div height="5"></div>

                            </div>

                            <div>

                            <div style="background-color:#00bdd5; color:#fff; text-align:center; font-weight:bold; padding:7px 0;font-size: 15px;">

                            Supervision Session Form Details
                            </div>

                            </div>

                            <div>

                            <div height="5"></div>

                            </div>

                            <div>

                            <div style="background-color:#1c84c6; color:#fff; text-align:center; font-weight:bold;  padding:7px 0;font-size: 15px;">Basic Details</div>

                            </div>

                            <div>

                            <div style="background-color:#dcf1fc; padding:7px 0;">

                                <div cellpadding="0" cellspacing="0" width="100%" border="0">

                                <div>

                                    <div style="padding:0 10px;">

                                    <table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-left:1px solid #fff; border-top:1px solid #fff;">

                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Candidate Name : </strong>    <?php echo $form_details[0]['candidate_name']; ?>                         
                                            </td>
                                            
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                            <strong>Email : </strong>    <?php echo $form_details[0]['email']; ?>  
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Mobile : </strong><?php echo $form_details[0]['mobile']; ?>                            
                                            </td>
                                            
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Bank : </strong>   <?php echo $form_details[0]['bank']; ?>    
                                            </td>
                                        </tr>



                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Branch : </strong>        <?php echo $form_details[0]['branch']; ?>                    
                                            </td>
                                            
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                            <strong>PDC : </strong>   <?php echo $form_details[0]['pdc_zone']; ?>     
                                            </td>
                                        </tr>



                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Center : </strong>     <?php echo $form_details[0]['center_name']; ?>                       
                                            </td>
                                            
                                        
                                        </tr>



                                        </table><!--table-5-->

                                    </div>
                                </div>
                                
                                </div>
                            </div>

                            <div>

                                <div style="background-color:#1c84c6; color:#fff; text-align:center; font-weight:bold; font-size:15px; padding:7px 0;">Supervision Details</div>

                                </div>

                                <div>

                                <div style="background-color:#dcf1fc; padding:7px 0;">

                                    <div cellpadding="0" cellspacing="0" width="100%" border="0">

                                    <div>

                                        <div style="padding:0 10px;">

                                        <div cellpadding="0" cellspacing="0" width="100%" border="0" style="border-left:1px solid #fff; border-top:1px solid #fff;">

                                            <div>
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Exam: </strong>    <?php echo $form_details[0]['exam_name']; ?>                         
                                                </div>
                                                
                                               
                                            </div>

                                            <div>
                                            <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Exam Date : </strong>    <?php echo $form_details[0]['exam_date']; ?>  
                                                </div>
                                              
                                            </div>

                                            <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Exam Time : </strong>   <?php echo $form_details[0]['exam_time']; ?>    
                                                </div>
                                            </div>

                                        

                                            <div>
                                                <div style=" border-right:1px solid #fff; padding:7px;">
                                                    <strong>Venue : </strong>        <?php echo $form_details[0]['venue_name'].' '.$form_details[0]['venueadd1'].' '.$form_details[0]['venueadd2'].' '.$form_details[0]['venueadd3'].' '.$form_details[0]['venueadd4'].' '.$form_details[0]['venueadd5'].' '.$form_details[0]['venpin']; ?>                    
                                                </div>                                                
                                               
                                            </div>
                                            <div>   
                                                <div style="    padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Location of venue whether suitable and convenient : </strong>   <?php echo $form_details[0]['suitable_venue_loc']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['suitable_venue_loc']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['suitable_venue_loc_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether venue was opened before the examination time : </strong>   <?php echo $form_details[0]['venue_open_bef_exam']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['venue_open_bef_exam']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['venue_open_bef_exam_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Number Of PCs in the venue : </strong>   <?php echo $form_details[0]['no_of_pc']; ?>    
                                                </div>
                                            </div>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether the venue was exclusively reserved for IIBF  : </strong>   <?php echo $form_details[0]['venue_reserved']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['venue_reserved']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['venue_reserved_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Was there a power problem in venue (alternate arrangement made and the duration of power problem): </strong>   <?php echo $form_details[0]['venue_power_problem']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['venue_power_problem']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Solution : </strong>   <?php echo $form_details[0]['venue_power_problem_sol']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Number of test supervisors in the venue ( details room/lasb wise)  : </strong>   
                                                </div>
                                            </div>
                                            <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <?php echo $form_details[0]['no_of_supervisors']; ?>    
                                                </div>
                                            </div>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether registration process was completed before the examination time: </strong>   <?php echo $form_details[0]['registration_process']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['registration_process']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['registration_process_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether frisking was done before the candidate were allowed to enter in computer lab? (To ensure that candidate do not carry mobile, any electronic gadgets, papers, chits etc.)   : </strong>   <?php echo $form_details[0]['frisking']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['frisking']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['frisking_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether lady frisking staff was available for frisking the lady candidates : </strong>   <?php echo $form_details[0]['frisking_lady']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['frisking_lady']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['frisking_lady_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether mobile phone,text materials etc. were allowed in venue  : </strong>   <?php echo $form_details[0]['mobile_allowed']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['mobile_allowed']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['mobile_allowed_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether candidate admit letter was checked and verified before permitting to sit for examination be the supervisors  : </strong>   <?php echo $form_details[0]['admit_letter_checked']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['admit_letter_checked']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['admit_letter_checked_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether any candidate were permitted to appear for the examination without proper admit letter and ID card ( details) : </strong>   <?php echo $form_details[0]['exam_without_admit_letter']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['exam_without_admit_letter']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['exam_without_admit_letter_detils']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether seat numbers were written againts each PC  : </strong>   <?php echo $form_details[0]['seat_no_written']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['seat_no_written']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['seat_no_written_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong> Whether candidates were seated in the seat number mentioned in the admit letter  : </strong>   <?php echo $form_details[0]['candidate_seated']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['candidate_seated']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['candidate_seated_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether separate arrangments was made available for PWD(Person with Disabilities) candidates using scribe : </strong>   <?php echo $form_details[0]['scribe_arrange']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['scribe_arrange']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['scribe_arrange_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether rules of examination are announced to the candidates by the Invigilators  : </strong>   <?php echo $form_details[0]['announcement']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['announcement_gap']!='') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['announcement_gap']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong> Whether examination started as scheduled : </strong>   <?php echo $form_details[0]['exam_started']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['exam_started']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['exam_started_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>No. of candidates appeared session wise : </strong>     
                                                </div>
                                            </div>
                                            <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <?php echo $form_details[0]['candidate_appeared']; ?>     
                                                </div>
                                            </div>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Whether any candidate were allowed to start the examination after 15 minutes of scheduled examination : </strong>   <?php echo $form_details[0]['started_late']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['started_late']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['started_late_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Was any unfair means was adopted by the candidates during the examination : </strong>   <?php echo $form_details[0]['unfair_candidates']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['unfair_candidates']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['unfair_candidates_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Rough sheet given to candidates were collected back and destroyed : </strong>   <?php echo $form_details[0]['rough_sheet']; ?>    
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['rough_sheet']=='Yes') { ?>
                                                <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Reason : </strong>   <?php echo $form_details[0]['rough_sheet_reason']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php if($form_details[0]['action_for_unfair']!='') { ?>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>What is the action taken for unfair means adopted by the candidates? Whether the Format for reporting UNFAIR Practices is duly filled: </strong>   <?php echo $form_details[0]['action_for_unfair']; ?>    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Name & Mobile No. of Examination Controller - Sify/NSEIT: </strong>  
                                                </div>
                                            </div>

                                            <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <?php echo $form_details[0]['name_mob_exam_contro']; ?>      
                                                </div>
                                            </div>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Any issue reported/faced by candidates: </strong>  
                                                </div>
                                            </div>
                                            <?php if($form_details[0]['issue_reported']!='') { ?>
                                            <div>   
                                                <div style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <?php echo $form_details[0]['issue_reported']; ?>      
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div>   
                                                <div style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                    <strong>Any other observation /Suggestion if any: </strong>   <?php if($form_details[0]['observation']!='') echo $form_details[0]['observation']; else echo'NA'; ?>      
                                                </div>
                                            </div>
                                        </div><!--table-5-->
                                        <table style="margin-top: 50px;" width="100%">
                                            <tr>
                                                <td width="60%" style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;"><strong>Date : </strong><?php echo $form_details[0]['filled_date'] ?></td>
                                                <td style="padding-top: 18px;border-top:1px solid #fff;border-right:1px solid #fff;padding:7px;float: right;"><strong>Sign : </strong> _______________________________________</td>
                                            </tr>
                                           
                                            </table> 
                                        </div>
                                    </div>
                                    
                                    </div>
                                </div>




                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
  
</body>

</html>