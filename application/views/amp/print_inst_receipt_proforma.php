<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IIBF</title>
<style>
body,ol,ul,li,p,div,table,{ margin:0px; padding:0px;}
p{ margin:0px; padding:8px;}
body{ font-family:Arial, Helvetica, sans-serif; color:#000;}
.bordeTable{ border: solid 1px #000;} 
.bordeTable th,td,tr{ border: solid 1px #000;} 
.addInfo{ font-family:Arial, Helvetica, sans-serif; font-size:19px; color:#000; padding:0 8px; margin:0px;}
.invoiceInfo{font-family:Arial, Helvetica, sans-serif; font-size:29px; font-weight:bold;  color:#000; padding:8px 0; margin:0px; text-align:center;}
.uppercaseText{ text-transform:uppercase;}
</style>
</head>

<body id="print_div">
<!--<a class="right" href="javascript:void(0);" onclick="window.history.go(-1)" >Back </a>-->
<a class="right" href="<?php echo base_url('amp/bank_payment'); ?>">Back </a>
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
			  <a href="<?php echo base_url();?>Amp/getpdf_proforma/<?php echo base64_encode($invoice_details[0]['invoice_id']);?>">
          		<img src="<?php echo  base_url()?>assets/images/image_preview.png"> Save as pdf
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
	<p><strong>GSTIN / Unique Id:</strong> <?php echo '27AAATT3309D1ZS';?></p>
    <p><strong>Address:</strong> Kohinoor City Commercial - II Tower-I, 2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg Kurla- West Mumbai - 400 070 </p>
    <p><strong>State:</strong><span class="uppercaseText"> Maharashtra</span></p>
    <p><strong>State Code:</strong> 27</p>
    <p><strong>Invoice No:</strong> <?php echo $invoice_details[0]['invoice_id'];?></p>
    <p><strong>Date Of Invoice:</strong> <?php echo date("d/m/Y");?></p>
    </td>
    <td width="584">&nbsp;</td>
  </tr>
  <tr>
    <td><p><strong>Details of service recipient</strong></p>
    <p><strong>Name of the Recipient: </strong> <span class="uppercaseText"><?php if(isset($invoice_info[0]['sponsor_bank_name'])){echo $invoice_info[0]['sponsor_bank_name'];}?></span></p>
       <p><strong>Address:</strong> <?php if(isset($invoice_info[0]['bank_address1']) || isset($invoice_info[0]['bank_address2']) || isset($invoice_info[0]['bank_address3']) || isset($invoice_info[0]['bank_address4'])) {echo $invoice_info[0]['bank_address1']." ".$invoice_info[0]['bank_address2']." ".$invoice_info[0]['bank_address3']." ".$invoice_info[0]['address4'];}?> </p>
	   
    <p><strong>City:</strong><span class="uppercaseText"><?php if(isset($invoice_info[0]['bank_city'])){echo $invoice_info[0]['bank_city'];}?></span></p>

	<p><strong>State:</strong><span class="uppercaseText"><?php if(isset($invoice_details[0]['state_name'])){echo $invoice_details[0]['state_name'];}?></span></p>
    <p><strong>State Code:</strong> <?php echo $invoice_details[0]['state_code']?></p>
    <p><strong>GSTIN / Unique Id: </strong><span class="uppercaseText"><?php echo $invoice_info[0]['gstin_no'];?></span></p>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="7%" style=" border-top: none; border-left: none;"><p><strong>Sr.No</strong></p></td>
        <td width="48%" style=" border-top: none;"><p><strong>Description Of Service</strong></p></td>
        <td width="16%" style=" border-top: none;"><p><strong>Accounting Code Of Service</strong></p></td>
        <td width="13%" style=" border-top: none;"><p><strong>Rate Per Unit</strong></p></td>
        <td width="8%" style=" border-top: none;"><p><strong>Unit</strong></p></td>
        <td width="8%" style=" border-top: none; border-right: none;"><p><strong>Total</strong></p></td>
      </tr>
      <tr>
        <td style="border-left: none; border-bottom: none;"><p>1</p></td>
        <td style="border-bottom: none;"><p>Advanced Management Programme course fee</p></td>
        <td style="border-bottom: none;"><p><?php echo $invoice_details[0]['service_code']; ?></p></td>
        <td style="border-bottom: none;"><p><?php echo $this->config->item('amp_full_course_fee');?></p></td>
        <td style=" border-bottom: none;"><p>1</p></td>
        <td style="border-right: none; border-bottom:none; "><p><?php echo $this->config->item('amp_full_course_fee'); ?></p></td>
      </tr>
	  <tr>
        <td style="border-left: none; border-bottom: none; border-top: none;"><p>2</p></td>
        <td style="border-bottom: none; border-top: none;"><p>Travel Expenses</p></td>
        <td style="border-bottom: none; border-top: none;"><p><?php echo $invoice_details[0]['service_code']; ?></p></td>
        <td style="border-bottom: none; border-top: none;"><p><?php  echo $this->config->item('amp_full_travel_fee');?></p></td>
        <td style=" border-bottom: none; border-top: none;"><p>1</p></td>
        <td style="border-right: none; border-bottom:none; border-top: none; "><p><?php echo $this->config->item('amp_full_travel_fee'); ?></p></td>
      </tr>
      <tr>
       <td style=" border-bottom: none; border-left: none;  border-top: none;"><p></p></td>
       <td style=" border-bottom: none;  border-top: none;"><p></p></td>
       <td style=" border-bottom: none;  border-top: none;">&nbsp;</td>
       <td style=" border-bottom: none;  border-top: none;"><p></p></td>
       <td style=" border-bottom: none; border-top: none; ">&nbsp;</td>
       <td style=" border-bottom: none; border-right: none; border-top: none;"><p><?php //echo $discount_amt;?></p></td>
      </tr>
      <tr>
        <td style=" border-bottom: none; border-left: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p></p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p></p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-right: none; border-top: none;"><p><?php //echo $net_amt;?></p></td>
      </tr>
      
      <?php if($invoice_details[0]['state_of_center'] == 'MAH'){?>
      
      <tr>
        <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top:none;"><p style="text-align:left;float:left; display: inline-block;">For intra-state supply -</p><p style="text-align:right; float:right; display: inline-block;">Central Tax :</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p><?php if(isset($invoice_details[0]['cgst_rate'])){echo $invoice_details[0]['cgst_rate'];} ?>%</p></td>
        <td style=" border-bottom: none; border-top:none;">&nbsp;</td>
        <td style=" border-bottom: none; border-right: none; border-top:none;"><p><?php if(isset($invoice_details[0]['cgst_amt'])){echo $invoice_details[0]['cgst_amt'];} ?></p></td>
      </tr>
      <tr>
        <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p style="text-align:right;">State Tax:</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p><?php if(isset($invoice_details[0]['sgst_rate'])){echo $invoice_details[0]['sgst_rate'];}?>%</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-right: none; border-top:none; border-bottom: none"><p><?php if(isset($invoice_details[0]['sgst_amt'])){echo $invoice_details[0]['sgst_amt'];}?></p></td>
      </tr>
	   <tr>
        <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p style="text-align:left;float:left; display: inline-block;">inter-state supply</p><p style="text-align:right;">Integrated Tax :</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p>18%</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-right: none; border-top:none;"><p> - </p></td>
      </tr>
      
      <?php }?>
       <?php  if($invoice_details[0]['state_of_center'] != 'MAH'){?>
	    <tr>
        <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top:none;"><p style="text-align:left; float:left; display: inline-block;">For intra-state supply -</p><p style="text-align:right; float:right; display: inline-block;">Central Tax :</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p>9%</p></td>
        <td style=" border-bottom: none; border-top:none;">&nbsp;</td>
        <td style=" border-bottom: none; border-right: none; border-top:none;"><p> - </p></td>
      </tr>
      <tr>
        <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p style="text-align:right;">State Tax:</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p>9%</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-right: none; border-top:none; border-bottom: none;"><p> - </p></td>
      </tr>
      <tr>
        <td style=" border-bottom: none; border-left: none; border-top:none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p style="text-align:left;float:left; display: inline-block;">inter-state supply</p><p style="text-align:right;">Integrated Tax :</p></td>
        <td style=" border-bottom: none; border-top: none;">&nbsp;</td>
        <td style=" border-bottom: none; border-top: none;"><p><?php if(isset($invoice_details[0]['igst_rate'])){echo $invoice_details[0]['igst_rate'];}?>%</p></td>
        <td style=" border-bottom: none; border-top: none; ">&nbsp;</td>
        <td style=" border-right: none; border-top:none;"><p><?php   if(isset($invoice_details[0]['igst_amt'])){echo $invoice_details[0]['igst_amt'];} ?></p></td>
      </tr>
      
      <?php }?>
      
      <tr>
        <td style=" border-left: none; border-top:none;">&nbsp;</td>
        <td style=" border-top:none;"><p style="text-align:right;"><strong>Total</strong></p></td>
        <td style=" border-top:none;">&nbsp;</td>
        <td style=" border-top:none;">&nbsp;</td>
        <td style=" border-top:none;">&nbsp;</td>
        <?php if($invoice_details[0]['state_of_center'] == 'MAH'){?>
        <td style=" border-right: none;"><p><strong><?php if(isset($invoice_details[0]['cs_total'])){echo $invoice_details[0]['cs_total'];}?></strong></p></td>
        <?php }elseif($invoice_details[0]['state_of_center']!= 'MAH'){?>
        <td style=" border-right: none;"><p><strong><?php if(isset($invoice_details[0]['igst_total'])){echo $invoice_details[0]['igst_total'];}?></strong></p></td>
        <?php }?>
        
      </tr>
      <tr>
        <td colspan="3" style=" border-bottom: none; border-left: none; border-right: none;"><p style="text-decoration:underline;"><strong>Amount in Words: <?php echo $in_words;?> only. </strong></p>
          <p>&nbsp;</p>
          <p style="padding-bottom:0px;">
          <strong>Reverse Change Applicable: </strong> 
          <strong style="margin-left:250px;">Y/N</strong>
          <strong style="margin-left:85px;">No</strong>
         </p>
        <p style="padding-top:15px;">
        <strong>% of Tax payable under Reverse Charge by recipient:</strong> 
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
      
    </table></td>
  </tr>
 
  
</table>
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
