<html>
  <head><meta http-equiv=Content-Type content="text/html; charset=UTF-8">


    <style type="text/css">
    /*Table with outline Classes*/
    table.tbl-2 { outline: none; width: 100%; border-right:1px solid #cccaca; border-top: 1px solid #cccaca;}
    table.tbl-2 th { background: #222D3A; border-bottom: 1px solid #cccaca; border-left:1px solid #dbdada; color: #fff; padding: 5px; text-align: center;}
    table.tbl-2 th.head { background: #CECECE; text-align:left;}
    table.tbl-2 td.tda2 { background: #f7f7f7; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
    table.tbl-2 td.tdb2 { background: #ebeaea; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
    table.tbl-2 td.tda2 a { color: #0d64a0;}
    table.tbl-2 td.tda2 a:hover{ color: #0d64a0; text-decoration:none;}
    table.tbl-2 td.tdb2 a { color: #0d64a0;}
    table.tbl-2 td.tdb2 a:hover{ color: #0d64a0; text-decoration:none;}
    .align_class_table{text-align:center !important;}
    .align_class_table_right{text-align:right !important;}
  </style>
  </head>

  <body>
    <table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">         
      <tbody>
        <tr><td colspan="4" align="left">&nbsp;</td> </tr>
        <tr>
          <td colspan="4" align="center" height="25">
          <span id="1001a1" class="alert"></span>
          </td>
        </tr>

        <tr style="border-bottom:solid 1px #000;"> 
          <td colspan="4" height="1" align="center" ><img src="<?php echo base_url('assets/images/logo1.png'); ?>"></td>
        </tr>
        <tr></tr>
        <tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>     
        <tr><td style="text-align:right"><img src="<?php echo $imagePath; ?>" height="100" width="100" /></td>
        </tr>
        <tr>
          <td colspan="4">
          </hr>

          <table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
            <tbody>
            <tr>
              <td class="tablecontent2" width="51%">Enrolment No : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['regnumber']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Name : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['name']; ?></td>
            </tr>
            <tr>
              <td class="tablecontent2" width="51%">IIBF Registration No: </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['iibf_membership_no']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Date of Birth : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo date('d-M-Y',strtotime($user_info_details['dob'])); ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Address : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['address1']; ?> <?php echo $user_info_details['address2'].' '.$user_info_details['address3'].' '.$user_info_details['address4']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Pincode : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['pincode_address']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Mobile Number : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['mobile_no']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Email ID : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['email_id']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Payment : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $payment; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Amount : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['amount']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Sponsor : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo ucfirst($user_info_details['sponsor']); ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Transaction Number : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['transaction_no']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Transaction Status : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $status; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Transaction Date : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info_details['date']; ?></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Id Proof : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="<?php echo $imagePath2; ?>" height="100" width="100" /></td>
            </tr>
            
            <tr>
              <td class="tablecontent2" width="51%">Signature : </td>
              <td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="<?php echo $imagePath1; ?>" height="100" width="100" /></td>
            </tr>
            
            </tbody>
          </table>
          
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>