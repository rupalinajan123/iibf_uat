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
                                </td> </tr></table></div>
                            </div>

                            </div>

                            <div>

                            <div height="5"></div>

                            </div>

                            <div>

                            <div style="background-color:#00bdd5; color:#fff; text-align:center; font-weight:bold; padding:7px 0;font-size: 15px;">

                            Honorarium form Details
                            </div>

                            </div>

                            <div>

                            <div height="5"></div>

                            </div>


                            <div>

                            <div style="background-color:#dcf1fc; padding:7px 0;">

                                <div cellpadding="0" cellspacing="0" width="100%" border="0">

                                <div>

                                    <div style="padding:0 10px;">
                                    <table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-left:1px solid #fff; border-top:1px solid #fff;">

                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Exam : </strong>    <?php echo $form_data[0]['exam_name']; ?>                         
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                            <strong>Venue : </strong>    <?php echo $form_data[0]['venue_name'].' '.$form_data[0]['venueadd1'].' '.$form_data[0]['venueadd2'].' '.$form_data[0]['venueadd3'].' '.$form_data[0]['venueadd4'].' '.$form_data[0]['venueadd5'].' '.$form_data[0]['venpin'];; ?>  
                                            </td>
                                        </tr>

                                      


                                        </table><!--table-5-->
                                        <table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-top: 25px;margin-bottom: 25px; border-left:1px solid #fff; border-top:1px solid #fff;">
                                        <tr>
                                        <td style="padding:10px;" class="text-center nowrap"><strong>Exam Date</strong></td> 
                                            <td style="padding:10px;" class="text-center nowrap"><strong>Name / Designation of the official visited</strong></td> 
                                            <?php if(isset($sessions)) { 
                                                foreach($sessions as $key=>$session ){
                                                if($key==0) $session_text = '1st';
                                                if($key==1) $session_text = '2nd';
                                                if($key==2) $session_text = '3rd';
                                                ?>
                                            <td style="padding:10px;" class="text-center nowrap"> <strong><?php echo $session_text ?> session</strong></td> 
                                            <?php } } ?>
                                            
                                            <td style="padding:10px;" class="text-center nowrap"><strong>Total Amount</strong></td> 
                                                </tr>
                                                <tr>
                                                    <td style="padding:10px;" class="text-center nowrap"><?php echo $form_data[0]['exam_date']; ?></td>
                                                    <td style="padding:10px;" class="text-center nowrap"><?php echo $candidate_data[0]['candidate_name']; ?></td>
                                                    <?php if(isset($session_wise_amount)) { 
                                                    foreach($session_wise_amount as $key=>$session ){ ?>
                                                    <td style="padding:10px;" class="text-center nowrap">Rs. <?php echo $session; ?></td>
                                                    <?php } } ?>
                                                    <td style="padding:10px;" class="text-center nowrap">Rs. <?php echo $form_data[0]['total_amount']; ?></td>
                                                </tr>

                                       

                                            </table><!--table-5-->
                                            
                                    <table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-left:1px solid #fff; border-top:1px solid #fff;">

                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Beneficiary Name : </strong>    <?php echo $claim_details[0]['beneficiary_name']; ?>                         
                                            </td>
                                            
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                            <strong>Account Number : </strong>    <?php echo $claim_details[0]['account_no']; ?>  
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Bank/Branch : </strong><?php echo $claim_details[0]['bank_branch_name']; ?>                            
                                            </td>
                                            
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>IFSC Code : </strong>   <?php echo $claim_details[0]['ifsc_code']; ?>    
                                            </td>
                                        </tr>



                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Email : </strong>        <?php echo $claim_details[0]['email']; ?>                    
                                            </td>
                                            
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                            <strong>Mobile : </strong>   <?php echo $claim_details[0]['mobile']; ?>     
                                            </td>
                                        </tr>



                                        <tr>
                                            <td style="border-bottom:1px solid #fff; border-right:1px solid #fff; padding:7px;">
                                                <strong>Pan Card : </strong>     <?php echo $claim_details[0]['pan_card']; ?>                       
                                            </td>
                                            
                                        
                                        </tr>



                                        </table><!--table-5-->
                                        <table style="margin-top: 50px;" width="100%">
                                            <tr>
                                                <td width="32%" style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;"><strong>Date : </strong>____________________________</td>
                                                <td width="32%" style="padding-top: 18px;border-top:1px solid #fff; border-right:1px solid #fff; padding:7px;"><strong>Place : </strong>____________________________</td>
                                                <td style="padding-top: 18px;border-top:1px solid #fff;border-right:1px solid #fff;padding:7px;float: right;"><strong>Sign : </strong> ________________________________</td>
                                            </tr>
                                           
                                            </table> 

                                    </div>
                                </div>
                                
                                </div>
                            </div>


                                <div>



                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
  
</body>

</html>