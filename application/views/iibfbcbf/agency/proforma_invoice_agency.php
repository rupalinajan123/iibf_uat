<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Proforma Invoice</title>
    <style>
      body.proforma_invoice_body_cls, .proforma_invoice_body_cls ol, .proforma_invoice_body_cls ul, .proforma_invoice_body_cls li, .proforma_invoice_body_cls p, .proforma_invoice_body_cls div, .proforma_invoice_body_cls table{ margin:0px; padding:0px;}
      .proforma_invoice_body_cls p{ margin:0px; padding:8px;}
      body.proforma_invoice_body_cls { font-family:Arial, Helvetica, sans-serif; color:#000;}
      .proforma_invoice_body_cls .bordeTable{ border: solid 1px #000; margin:8px auto;} 
      .proforma_invoice_body_cls .bordeTable th, .proforma_invoice_body_cls .bordeTable td, .proforma_invoice_body_cls .bordeTable tr{ border: solid 1px #000;} 
      .proforma_invoice_body_cls .addInfo{ font-family:Arial, Helvetica, sans-serif; font-size:19px; color:#000; padding:0 8px; margin:0px;}
      .proforma_invoice_body_cls .invoiceInfo{font-family:Arial, Helvetica, sans-serif; font-size:29px; font-weight:bold;  color:#000; padding:8px 0; margin:0px; text-align:center;}
      .proforma_invoice_body_cls .uppercaseText{ text-transform:uppercase;}
    </style>
  </head>
  
  <body id="print_div" class="proforma_invoice_body_cls">
    <table width="980px" border="0" cellspacing="0" cellpadding="0" class="bordeTable" align="center">
      <tr>
        <td colspan="2">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr>
              <td style="border: none;" >&nbsp;</td>
              <td style="border: none;" align="right">
                <a onclick="javascript:printDiv();">
                  <img src="<?php echo  base_url()?>assets/images/print-icon.png"> 
                </a>
              </td>
            </tr>
            <tr>
              <td width="50%" style="border: none;" >
                <p class="addInfo">Tel: 91-022-2503 9604 / 9746 / 9907</p>
                <p class="addInfo">Fax: 91-022-2503 7332</p>
              <p class="addInfo"> Web-site: www.iibf.org.in</p></td>
              <td width="50%" style="border: none; padding:15px;"><img src="<?php echo  base_url()?>assets/images/p_iibflogo.png" alt="IIBF" style=" float:right;" /></td>
            </tr>
            <tr>
              <td style="border: none;" >&nbsp;</td>
              <td style="border: none;">&nbsp;</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="2"><p class="invoiceInfo">Proforma Invoice</p>
        </td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="584"><p><strong>Name of the Assessee:</strong> <span class="uppercaseText">Indian Institute of Banking & Finance .</span></p>
          <p><strong>Address:</strong> Kohinoor City, Commercial - II, Tower 1, 3rd Floor, Kirol Road, Kurla(W), Mumbai - 400070, INDIA </p>
          <p><strong>State:</strong><span class="uppercaseText"> Maharashtra</span></p>
          <p><strong>State Code:</strong> 27</p>
          <p><strong>GSTIN / Unique Id:</strong> <?php echo '27AAATT3309D1ZS';?></p>
          <p><strong>PAN Number:</strong> <?php echo 'AAATT3309D';?></p>
          <p><strong>Invoice No:</strong> <?php echo $invoice_number;?></p>
          <p><strong>Date Of Invoice:</strong> <?php echo $date_of_invoice;?></p>
          <p><strong>Transaction No:</strong> <?php echo $transaction_no;?></p>
        </td>
        <td width="584">&nbsp;</td>
      </tr>
      <tr>
        <td><p><strong>Details of service recipient</strong></p>
          <p><strong>Name of the Recipient: </strong> <span class="uppercaseText"><?php echo $recepient_name;?>,</span></p>
          <p><strong>Address:</strong> <?php echo $address;?> </p>
          <p><strong>State:</strong><span class="uppercaseText"> <?php echo $centre_state?></span></p>
          <p><strong>State Code:</strong> <?php echo $centre_state_code?></p>
          <p><strong>GSTIN / Unique Id: </strong><span class="uppercaseText"><?php echo $centre_gstn?></span></p>
        </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="7%" style=" border-top: none; border-left: none;"><p><strong>Sr. No</strong></p></td>
              <td width="48%" style=" border-top: none;"><p><strong>Description Of Service</strong></p></td>
              <td width="16%" style=" border-top: none;"><p><strong>Accounting Code Of Service</strong></p></td>
              <td width="13%" style=" border-top: none;"><p><strong>Rate Per Unit</strong></p></td>
              <td width="8%" style=" border-top: none;"><p><strong>Unit</strong></p></td>
              <td width="8%" style=" border-top: none; border-right: none;"><p><strong>Total</strong></p></td>
            </tr>
            
            <?php if($fresh_count > 0){ ?>
              <tr>
                <td style="border-left: none; border-bottom: none;"><p>01</p></td>
                <td style="border-bottom: none;"><p>Conduction of Exam</p></td>
                <td style="border-bottom: none;"><p>999294</p></td>
                <td style="border-bottom: none;"><p><?php echo $fresh_fee_amount;?></p></td>
                <td style=" border-bottom: none;"><p><?php echo $fresh_count;?></p></td>
                <td style="border-right: none; border-bottom:none; "><p><?php echo $total_fresh_fee;?></p></td>
              </tr>
            <?php  } ?>
            <?php if($rep_count > 0){ ?>
              <tr>
                <td style="border-left: none; border-bottom: none;"><p>02</p></td>
                <td style="border-bottom: none;"><p>Conduction of Exam</p></td>
                <td style="border-bottom: none;"><p>999294</p></td>
                <td style="border-bottom: none;"><p><?php echo $rep_fee_amount;?></p></td>
                <td style=" border-bottom: none;"><p><?php echo $rep_count;?></p></td>
                <td style="border-right: none; border-bottom:none; "><p><?php echo $total_rep_fee;?></p></td>
              </tr>
            <?php  } ?>
            <tr>
              <td style=" border-bottom: none; border-left: none;  border-top: none;"><p>Less</p></td>
              <td style=" border-bottom: none;  border-top: none;"><p>Discount-</p></td>
              <td style=" border-bottom: none;  border-top: none;">&nbsp;</td>
              <td style=" border-bottom: none;  border-top: none;"><p>-</p></td>
              <td style=" border-bottom: none; border-top: none; ">&nbsp;</td>
              <td style=" border-bottom: none; border-right: none; border-top: none;"><p><?php echo $discount_amt;?></p></td>
            </tr>
            <tr>
              <td style=" border-bottom: none; border-left: none; border-top: none;">&nbsp;</td>
              <td style=" border-bottom: none; border-top: none;"><p>NET-</p></td>
              <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
              <td style=" border-bottom: none; border-top: none;"><p>-</p></td>
              <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
              <td style=" border-bottom: none; border-right: none; border-top: none;"><p><?php echo $net_amt;?></p></td>
            </tr>
            
            <?php if($ste_code == 'MAH'){?>
              
              <tr>
                <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
                <td style=" border-bottom: none; border-top:none;"><p style="text-align:right;">CGST</p></td>
                <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
                <td style=" border-bottom: none; border-top: none;"><p><?php echo $cgst_rate;?>%</p></td>
                <td style=" border-bottom: none; border-top:none;">&nbsp;</td>
                <td style=" border-bottom: none; border-right: none; border-top:none;"><p><?php echo $cgst_amt;?></p></td>
              </tr>
              <tr>
                <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
                <td style=" border-bottom: none; border-top: none;"><p style="text-align:right;">SGST</p></td>
                <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
                <td style=" border-bottom: none; border-top: none;"><p><?php echo $sgst_rate;?>%</p></td>
                <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
                <td style=" border-right: none; border-top:none;"><p><?php echo $sgst_amt;?></p></td>
              </tr>
              
            <?php }?>
            <?php if($ste_code != 'MAH'){?>
              <tr>
                <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
                <td style=" border-bottom: none; border-top: none;"><p style="text-align:right;">IGST</p></td>
                <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
                <td style=" border-bottom: none; border-top: none;"><p><?php echo $igst_rate;?>%</p></td>
                <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
                <td style=" border-right: none; border-top:none;"><p><?php echo $igst_total;?></p></td>
              </tr>
              
            <?php }?>
            
            <tr>
              <td style=" border-left: none; border-top:none;">&nbsp;</td>
              <td style=" border-top:none;"><p style="text-align:right;"><strong>Total</strong></p></td>
              <td style=" border-top:none;">&nbsp;</td>
              <td style=" border-top:none;">&nbsp;</td>
              <td style=" border-top:none;">&nbsp;</td>
              <td style=" border-right: none;"><p><strong><?php echo $final_total;?></strong></p></td>
            </tr>
            <tr>
              <td colspan="3" style=" border-bottom: none; border-left: none; border-right: none;"><p style="text-decoration:underline;"><strong>Amount in Words: <?php echo $amount_in_word;?> only. </strong></p>
                <p>&nbsp;</p>
                <p style="padding-bottom:0px;">
                  <strong>Reverse change applicable: </strong> 
                  <strong style="margin-left:250px;">Y/N</strong>
                  <strong style="margin-left:85px;">No</strong>
                </p>
                <p style="padding-top:15px;">
                  <strong>% of Tax payable under Reverse Charge by recepient:</strong> 
                  <strong style="margin-left:55px;">%--- </strong>
                  <strong style="margin-left:85px;">Rs.---</strong>
                </p>
              </td>
              <td colspan="3" style=" border-bottom: none; border-left: none; text-align: center;">
                <p>
                  <img src="<?php echo  base_url()?>assets/images/sign.jpg" alt="stamp" style=" padding:15px;" width="50" height="50" />
                </p>
                <p>
                  Authorised Signatory
                </p>
              </td>
            </tr>          
          </table>
        </td>
      </tr>
    </table>
    
    <script>
      function printDiv(divName) 
      {
        var opt = confirm("Do you want to print a copy?"); 
        if(opt == false)
        {
          return false;	
        }
        else
        {
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
